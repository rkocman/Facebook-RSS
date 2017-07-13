<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Utils;

/**
 * Password tools (Nette like).
 */
class Passwords
{
  const BCRYPT_COST = 10;
  
  /**
   * Computes a salt.
   * @return string
   */
  private static function salt()
  {
    $length = 22;
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./";
    $charsLen = strlen($chars);
    $salt = "";
    for ($i = 0; $i < $length; $i++) {
      $salt .= $chars[rand(0, $charsLen - 1)];
    }
    return $salt;
  }
  
  /**
   * Computes a salted password hash.
   * @param  string password
	 * @param  string salt (22 chars)
	 * @return string 60 chars long
   */
  public static function hash($password, $salt = null)
  {
    $cost = self::BCRYPT_COST;
    $salt = isset($salt)? $salt : self::salt();
    
    $hash = crypt($password, '$2y$'.$cost.'$'.$salt);
    if (strlen($hash) < 60) {
      throw new Exception('Hash is invalid.');
    }
    return $hash;
  }
  
  /**
   * Verifies that the password matches the hash.
   * @param  string password
   * @param  string hash
   * @return bool
   */
  public static function verify($password, $hash)
  {
    return preg_match('/^\$2y\$(?P<cost>\d\d)\$(?P<salt>.{22})/', $hash, $m)
      && $m['cost'] == self::BCRYPT_COST
      && self::hash($password, $m['salt']) === $hash;
  }
  
}
