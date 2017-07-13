<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

/**
 * Autoloader.
 */
function facebookrss_autoload($className)
{
  $classPath = explode('\\', $className);
  if ($classPath[0] != 'FacebookRSS') {
    return;
  }
  
  // drop the first and maximum depth is 3
  $classPath = array_slice($classPath, 1, 2);
  
  $filePath = dirname(__FILE__).'/'.implode('/', $classPath).'.php';
  if (file_exists($filePath)) {
    require_once($filePath);
  }
}
spl_autoload_register('facebookrss_autoload');