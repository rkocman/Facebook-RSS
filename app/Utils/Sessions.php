<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Utils;

use FacebookRSS;

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
    
    self::$user = (isset($_SESSION['user']))? $_SESSION['user'] : self::resetUser();
    self::$admin = (isset($_SESSION['admin']))? $_SESSION['admin'] : self::resetAdmin();
    self::$logout = (isset($_SESSION['logout']))? $_SESSION['logout'] : false;
  }
  
  /**
   * Creates a new User.
   * @return User
   */
  public static function resetUser()
  {
    $_SESSION['user'] = new FacebookRSS\Model\User();
    self::$user = $_SESSION['user'];
    return $_SESSION['user'];
  }
  
  /**
   * Creates a new Admin.
   * @return Admin
   */
  public static function resetAdmin()
  {
    $_SESSION['admin'] = new FacebookRSS\Model\Admin();
    self::$admin = $_SESSION['admin'];
    return $_SESSION['admin'];
  }
  
  /**
   * Closes PHP_AUTH.
   */
  public static function closePHPAuth()
  {
    $_SESSION['logout'] = true;
  }
  
  /**
   * Resets PHP_AUTH.
   */
  public static function resetPHPAuth()
  {
    $_SESSION['logout'] = false;
  }
  
}