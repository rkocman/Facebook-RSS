<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Presenters;

use FacebookRSS,
    FacebookRSS\Utils\Params,
    FacebookRSS\Utils\Sessions,
    FacebookRSS\Utils\Links,
    FacebookRSS\Model\Db;

/**
 * Presenter for the admin section.
 */
class AdminPresenter
{
  
  /**
   * Selects an action.
   */
  public static function run()
  {
    
    // login
    if (!Sessions::$admin->isLogged()) {
      Sessions::$admin->login();
    }
    
    // summary
    if (empty(Params::$action)) {
      self::summary(); 
      return;
    }
    
    // createTable
    if (Params::$action === "create-table") {
      self::createTable(); 
      return;
    }
    
    die(header("Location: ".Links::generateFull('admin')));
  }
  
  /**
   * Page: Summary.
   */
  public static function summary()
  {
    $check = Db::checkTable();
    $summary = Db::getSummary();
    FacebookRSS\Views\AdminView::summary($check, $summary);
  }
  
  /**
   * Action: Create table.
   */
  public static function createTable()
  {
    Db::createTable();
    die(header("Location: ".Links::generateFull('admin')));
  }
  
}
