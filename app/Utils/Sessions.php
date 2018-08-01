<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Utils;

use FacebookRSS;
use FacebookRSS\AppConfig;

/**
 * Session handler.
 */
class Sessions
{
  /** @var \FacebookRSS\Model\User */
  public static $user;
  
  /** @var \FacebookRSS\Model\Admin */
  public static $admin;
  
  /** Remembers if the user tried to log out. */
  public static $logout;
  
  /**
   * Initializes session variables.
   */
  public static function init()
  {
    session_start();

    if (!isset($_SESSION[AppConfig::sessionName])) {
      $_SESSION[AppConfig::sessionName] = [
        'user' => self::resetUser(),
        'admin' => self::resetAdmin(),
        'logout' => false
      ];
    }
    
    self::$user = $_SESSION[AppConfig::sessionName]['user'];
    self::$admin = $_SESSION[AppConfig::sessionName]['admin'];
    self::$logout = $_SESSION[AppConfig::sessionName]['logout'];
  }
  
  /**
   * Creates a new User.
   * @return User
   */
  public static function resetUser()
  {
    self::$user = new FacebookRSS\Model\User;
    $_SESSION[AppConfig::sessionName]['user'] = self::$user;
    return self::$user;
  }
  
  /**
   * Creates a new Admin.
   * @return Admin
   */
  public static function resetAdmin()
  {
    self::$admin = new FacebookRSS\Model\Admin;
    $_SESSION[AppConfig::sessionName]['admin'] = self::$admin;
    return self::$admin;
  }
  
  /**
   * Closes PHP_AUTH.
   */
  public static function closePHPAuth()
  {
    $_SESSION[AppConfig::sessionName]['logout'] = true;
  }
  
  /**
   * Resets PHP_AUTH.
   */
  public static function resetPHPAuth()
  {
    $_SESSION[AppConfig::sessionName]['logout'] = false;
  }
  
}