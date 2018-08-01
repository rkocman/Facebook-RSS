<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Utils;

/**
 * Link generator.
 */
class Links
{
  
  /**
   * Generates a link.
   * @param  string section
   * @param  string action
   * @param  string id
   * @return string link
   */
  public static function generate($section = NULL, $action = NULL, $id = NULL)
  {
    $link = "";
    if (isset($section)) {
      $link .= "?section=".rawurlencode($section);
    }
    if (isset($action)) {
      $link .= "&amp;action=".rawurlencode($action);
    }
    if (isset($id)) {
      $link .= "&amp;id=".rawurlencode($id);
    }
    return $link;
  }
  
  /**
   * Generates a full link.
   * @param  string section
   * @param  string action
   * @param  string id
   * @return string full link
   */
  public static function generateFull($section = NULL, $action = NULL, $id = NULL)
  {
    $params = self::generate($section, $action, $id);
    return 'http://'.$_SERVER['HTTP_HOST'].strtok($_SERVER['REQUEST_URI'],'?').$params;
  }
  
}