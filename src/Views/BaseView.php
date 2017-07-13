<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Views;

use FacebookRSS\Utils\Links,
    FacebookRSS\Constants;

/**
 * Basic template.
 */
class BaseView
{
  
  /**
   * Prints the basic template.
   * @param string page content
   */
  public static function template($content)
  {
    echo '
      <!DOCTYPE html>
      <html>
      <head>
        <title>'.Constants::title.'</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="author" content="'.Constants::author.'">
      </head>
      <body>
      <div>
        <h1><a href="'.Links::generateFull().'">'.Constants::title.'</a></h1>
        <h3>'.Constants::moto.'</h3>
        '.$content.'
      </div>
      </body>
      </html>
    ';
  }
  
}