<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS;

/**
 * Main engine.
 */
class Engine
{
  
  /**
   * Initializes components and handles the request.
   */
  public static function run() 
  {
    try {
      
      Utils\Params::init();
      Utils\Sessions::init();
      Model\Db::init();
      Model\Facebook::init();
      
      Presenters\Router::run();
    
    /// Exception handling
    } catch (\Dibi\Exception $e) {
      if (AppConfig::devel) {
        throw $e;
      } else {
        Views\BaseView::template("Some SQL error has occured!");
      }
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
      if (AppConfig::devel) {
        throw $e;
      } else {
        Views\BaseView::template("Graph API returned an error: ".$e->getMessage());
      }
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
      if (AppConfig::devel) {
        throw $e;
      } else {
        Views\BaseView::template("Facebook SDK returned an error: ".$e->getMessage());
      }
    } catch (\Exception $e) {
      if (AppConfig::devel) {
        throw $e;
      } else {
        Views\BaseView::template("Some unexpected error has occurred!");
      }
    }
    ///
  }
  
}