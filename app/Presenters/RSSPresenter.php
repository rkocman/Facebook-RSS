<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Presenters;

use FacebookRSS,
    FacebookRSS\AppConfig,
    FacebookRSS\Utils\Sessions,
    FacebookRSS\Utils\Links,
    FacebookRSS\Utils\Params,
    FacebookRSS\Model\Facebook,
    FacebookRSS\Model\Db;

/**
 * Presenter for RSS functions.
 */
class RSSPresenter
{
  
  /**
   * Page: RSS.
   */
  public static function run()
  {
    
    // login
    if (!Sessions::$user->isLogged()) {
      Sessions::$user->login();
    }
    
    // connected
    if (!Sessions::$user->isConnected()) {
      die(header("Location: ".Links::generateFull()));
    }
    
    // increase the counter
    Db::updateCounter(Sessions::$user->username);
    
    // check the expiration
    if (Sessions::$user->isExpired()) {
      FacebookRSS\Views\RSSView::printRSSReconnect();
      return;
    }
    
    // data
    $data = Facebook::getPagesPosts(); 
    
    // print raw data
    if (AppConfig::devel && Params::$action === "raw") {
      print_r($data);
      return;
    }
    
    // RSS
    FacebookRSS\Views\RSSView::printRSSPosts($data);
    
  }
  
}
