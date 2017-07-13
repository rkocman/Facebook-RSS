<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Model;

use FacebookRSS\Utils\Sessions,
    FacebookRSS\Utils\Passwords,
    FacebookRSS\Model\Db,
    FacebookRSS\Model\Facebook,
    FacebookRSS\Constants;

/**
 * User model.
 */
class User
{
  /* users's login status */
  private $logged;
  
  // user's data
  public  $id;
  public  $username;
  private $connected;
  public  $accessToken;
  
  public function __construct()
  {
    $this->logged = false;
    $this->connected = false;
  }
  
  /**
   * Is the user logged in?
   * @return bool
   */
  public function isLogged()
  {
    return $this->logged;
  }
  
  /**
   * Is the user connected with Facebook?
   * @return bool
   */
  public function isConnected()
  {
    return $this->connected;
  }
  
  /**
   * Is the user's connection expired?
   * @return bool
   */
  public function isExpired()
  {
    return !(Facebook::isValid($this->accessToken));
  }
  
  /**
   * Performs a login attempt.
   */
  public function login()
  {
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
      && !Sessions::$logout)
    {
      $data = Db::getUserData($_SERVER['PHP_AUTH_USER']);
      if (!empty($data)) {
        if (Passwords::verify($_SERVER['PHP_AUTH_PW'], $data['password'])) {
          $this->id = $data['id'];
          $this->username = $data['username'];
          $this->logged = true;
          if (!empty($data['accessToken'])) {
            $this->accessToken = $data['accessToken'];
            $this->connected = true;
            Facebook::init();
          }
          return;
        }
      }
    }
    
    Sessions::resetPHPAuth();
    Header('WWW-Authenticate: Basic realm="'.Constants::title.'"');
    Header('HTTP/1.0 401 Unauthorized');
    exit;
  }
  
  /**
   * Performs the logout.
   */
  public function logout()
  {
    Sessions::resetUser();
    Sessions::closePHPAuth();
  }
  
  /**
   * Signs up a new user.
   * @param string username
   * @param string password
   * @return bool
   */
  public function signUp($username, $password)
  {
    $id = Db::insertUser($username, Passwords::hash($password));
    if ($id < 0) {
      return false;
    }
    
    $this->id = $id;
    $this->username = $username;
    $this->logged = true;
    return true;
  }
  
  /**
   * Saves the Facebook access token.
   * @param string accessToken
   */
  public function saveToken($accessToken)
  {
    Db::updateToken($this->username, $accessToken);
    $this->accessToken = $accessToken;
    $this->connected = true;
  }
  
}
