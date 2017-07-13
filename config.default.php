<?php

// FILL and RENAME to config.php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS;

/**
 * Database configuration.
 */
class DatabaseConfig
{
  const driver = 'mysql';
  const host = 'localhost';
  const username = 'admin';
  const password = '';
  const database = 'test';
  const table = 'facebookrss';
}

/**
 * Admin configuration.
 */
class AdminConfig
{
  const username = 'admin';
  const password = '';
}

/**
 * Facebook configuration.
 */
class FacebookConfig
{
  const appId = '';
  const secret = '';
  const locale = 'en_US';
}

/**
 * Application configuration.
 */
class AppConfig
{
  // Maximum number of posts taken from each Facebook page.
  const maxPageItems = 10;
  // Maximum number of returned RSS items.
  const maxItems = 200;
  
  // Developer mode (shows full details of errors).
  const devel = false;
  
  // This can remove emoji icons from the content for compatibility reasons.
  const removeEmoji = false;
}
