<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Presenters;

use FacebookRSS,
    FacebookRSS\Utils\Params,
    FacebookRSS\Utils\Links,
    FacebookRSS\Utils\Sessions;

/**
 * Presenter for the main app.
 */
class AppPresenter
{
  
  /**
   * Selects an action.
   */
  public static function run()
  {
    
    // log in
    if (Params::$section === "login") {
      if (!Sessions::$user->isLogged()) {
        Sessions::$user->login();
      }
      die(header("Location: ".Links::generateFull()));
    }
    
    // log out
    if (Params::$section === "logout") {
      self::logout();
      die(header("Location: ".Links::generateFull()));
    }
    
    // sign up
    if (Params::$section === "signup") {
      if (Sessions::$user->isLogged()) {
        die(header("Location: ".Links::generateFull()));
      }
      self::signUp();
      return;
    }
    
    // connect
    if (Params::$section === "connect") {
      if (!Sessions::$user->isLogged()) {
        die(header("Location: ".Links::generateFull()));
      }
      self::connect();
      return;
    }
    
    // connect redirect
    if (isset(Params::$code)) {
      if (!Sessions::$user->isLogged()) {
        die(header("Location: ".Links::generateFull()));
      }
      self::connectRedirect();
      die(header("Location: ".Links::generateFull()));
    }
    
    // liked pages
    if (Params::$section === "liked-pages") {
      if (!Sessions::$user->isConnected() || Sessions::$user->isExpired()) {
        die(header("Location: ".Links::generateFull()));
      }
      self::likedPages();
      return;
    }
    
    // main page
    if (empty(Params::$section)) {
      self::mainPage();
      return;
    }
    
    die(header("Location: ".Links::generateFull()));
  }
  
  /**
   * Page: Main page.
   */
  public static function mainPage() 
  {
    FacebookRSS\Views\AppView::mainPage();
  }
  
  /**
   * Action: Logout.
   */
  public static function logout() {
    Sessions::$user->logout();
  }
  
  /**
   * Page: Sign Up.
   */
  public static function signUp() 
  {
    $errors = array();
    if (isset($_POST['username'])) {
      $errors = self::signUpFormCheck();
      if (count($errors) === 0) {
        $done = Sessions::$user->signUp($_POST['username'], $_POST['password']);
        if ($done) {
          die(header("Location: ".Links::generateFull()));
        } else {
          $errors[] = "Selected username is already used. Please try a different one.";
        }
      }
    }
    FacebookRSS\Views\AppView::signUp($errors);
  }
  
  /**
   * Action: Sign Up Form Check.
   * @return array of strings
   */
  public static function signUpFormCheck()
  {
    $errors = array();
    
    if (mb_strlen($_POST['username']) < 3) 
      $errors[] = "Username has incorrect format.";
    if (mb_strlen($_POST['username']) > 50) 
      $errors[] = "Username has incorrect format.";
    if (mb_strlen($_POST['password']) <= 3) 
      $errors[] = "Password has incorrect format.";
    if ($_POST['password'] !== $_POST['passwordVerify']) 
      $errors[] = "Passwords does not match.";
      
    return $errors;
  }
  
  /**
   * Action: Connect.
   */
  public static function connect()
  {
    $url = FacebookRSS\Model\Facebook::connectUrl();
    die(header("Location: ".$url));
  }
  
  /**
   * Action: Connect redirect.
   */
  public static function connectRedirect()
  {
    $accessToken = FacebookRSS\Model\Facebook::getAccessToken();
    Sessions::$user->saveToken($accessToken);
  }
  
  /**
   * Page: Liked pages.
   */
  public static function likedPages()
  {
    $data = FacebookRSS\Model\Facebook::getPages();
    FacebookRSS\Views\AppView::likedPages($data);
  }
  
}