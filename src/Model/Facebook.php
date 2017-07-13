<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Model;

use FacebookRSS\FacebookConfig,
    FacebookRSS\AppConfig,
    FacebookRSS\Utils\Links,
    FacebookRSS\Utils\Sessions;

/**
 * Facebook model.
 */
class Facebook
{
  /** @var \Facebook\Facebook */
  private static $fb;
  
  /**
   * Inits the API objects.
   */
  public static function init()
  {
    self::$fb = new \Facebook\Facebook([
      'app_id' => FacebookConfig::appId,
      'app_secret' => FacebookConfig::secret,
      'default_graph_version' => 'v2.9',
    ]);
    
    if (!empty(Sessions::$user->accessToken)) {
      self::$fb->setDefaultAccessToken(Sessions::$user->accessToken);
    }
  }
  
  /**
   * Returns a connect url.
   * @return string
   */
  public static function connectUrl()
  {
    $helper = self::$fb->getRedirectLoginHelper();
    $permissions = ["user_likes"];
    return $helper->getLoginUrl(Links::generateFull(), $permissions);
  }
  
  /**
   * Gets an access token from the response.
   * @return string
   */
  public static function getAccessToken()
  {
    $helper = self::$fb->getRedirectLoginHelper();
    $accessToken = $helper->getAccessToken();
    
    $oAuth2Client = self::$fb->getOAuth2Client();
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    $tokenMetadata->validateAppId(FacebookConfig::appId);
    $tokenMetadata->validateExpiration();
    
    if (!$accessToken->isLongLived()) {
      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    }
    return $accessToken->getValue();
  }
  
  /**
   * Checks if the access token is valid.
   * @param string token
   * @return bool
   */
  public static function isValid($accessToken)
  {
    $oAuth2Client = self::$fb->getOAuth2Client();
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    return $tokenMetadata->getIsValid();
  }
  
  /**
   * Returns all liked pages.
   * @return array(name,picture,link,last_posted)
   */
  public static function getPages()
  {
    $response = self::$fb->get(
      '/me/likes?fields='
      .'name,picture{url},'
      .'link,posts.limit(1){created_time}'
      .'&locale='.FacebookConfig::locale
    );
    
    $pages = $response->getGraphEdge();
    $results = [];
    
    do {
      foreach ($pages as $page) {
        $results[] = [
          'name' => $page['name'],
          'picture' => $page['picture']['url'],
          'link' => $page['link'],
          'last_posted' => isset($page['posts'])? 
            $page['posts'][0]['created_time'] : null
        ];
      }
    } while ($pages = self::$fb->getPaginationResults($pages, 'next'));
    
    usort($results, function($a, $b) {
      return $a['name'] > $b['name'];
    });
    
    return $results;
  }
  
  /**
   * Returns all pages posts.
   * @return array(name,username,picture,post(id,created_time,actions,
   *   message,type,picture,link,name,caption,description,story,attachments))
   */
  public static function getPagesPosts()
  {
    $response = self::$fb->get(
      '/me/likes?fields='
      .'name,username,picture{url},'
      .'posts.limit('.AppConfig::maxPageItems.'){'
        .'id,created_time,actions,message,type,picture,'
        .'link,name,caption,description,story,attachments'
      .'}&locale='.FacebookConfig::locale
    );
    
    $pages = $response->getGraphEdge();
    $results = [];
    
    do {
      foreach ($pages as $page) {
        if (isset($page['posts'])) {
          foreach ($page['posts'] as $post) {
            $results[] = [
              'name' => $page['name'],
              'username' => isset($page['username'])? $page['username'] : null,
              'picture' => $page['picture']['url'],
              'post' => $post
            ];
          }
        }
      }
    } while ($pages = self::$fb->getPaginationResults($pages, 'next'));
    
    usort($results, function($a, $b) {
      return $a['post']['created_time'] < $b['post']['created_time'];
    });

    return array_slice($results, 0, AppConfig::maxItems);
  }
  
}
