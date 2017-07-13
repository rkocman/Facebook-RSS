<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Views;

use FacebookRSS\Utils\Links,
    FacebookRSS\Utils\Sessions,
    FacebookRSS\Utils\Printer,
    FacebookRSS\Model\Db;

/**
 * Admin views.
 */
class AdminView extends BaseView
{
  
  /**
   * Prints the admin summary.
   * @param bool check table result
   * @param array(id,username,accessToken,counter,ts) user's summary
   */
  public static function summary($check, $summary)
  {
    $content = '
      <h2>Admin Summary</h2>
      <p>
        Database connection: '.((Db::$connected)? 'OK' : 'FAILED').'
        <br>
        Database table: '.(($check)? 'OK' : 
          'FAILED <a href="'.Links::generate('admin','create-table').'">Create table</a>').'
      </p>
      
      <h3>Users</h3>
    ';
    $content .= '
      <table>
        <tr>
          <th>id</th>
          <th>username</th>
          <th>connected</th>
          <th>counter</th>
          <th>timestamp</th>
        </tr>
    ';
    foreach ($summary as $line) {
      $content .= '
        <tr>
          <td>'.Printer::html($line['id']).'</td>
          <td>'.Printer::html($line['username']).'</td>
          <td>'.($line['accessToken']? 'Yes' : 'No').'</td>
          <td>'.Printer::html($line['counter']).'</td>
          <td>'.Printer::html($line['ts']).'</td>
        </tr>
      ';
    }
    $content .= '</table>';
    self::template($content);
  }
  
}