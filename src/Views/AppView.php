<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Views;

use FacebookRSS\Utils\Links,
    FacebookRSS\Utils\Sessions,
    FacebookRSS\Utils\Printer;

/**
 * Main application views.
 */
class AppView extends BaseView
{
  
  /**
   * Page: Main page.
   */
  public static function mainPage()
  {
    $content = '
      <p><a href="'.Links::generate('admin').'">Admin</a></p>
      <br>
    ';
    
    if (!Sessions::$user->isLogged()) {
      $content .= '
        <h2>Log In</h2>
        <p>
          <a href="'.Links::generate('login').'">Log In</a><br>
          <a href="'.Links::generate('rss').'">RSS Feed</a><br>
        </p>
        <br>
        
        <h2>Sign Up</h2>
        <p>
          <a href="'.Links::generate('signup').'">Start</a><br>
        </p>
      ';
    }
    
    if (Sessions::$user->isLogged()) {
      $content .= '
        <h2>User: '.Printer::html(Sessions::$user->username).'</h2>
        <p>
          '.(!Sessions::$user->isConnected()?
              '<a href="'.Links::generate('connect').'">Connect with Facebook</a><br>' 
          : (Sessions::$user->isExpired()?
              '<a href="'.Links::generate('connect').'">Reconnect with Facebook</a><br>' 
          : '  <a href="'.Links::generate('liked-pages').'">List of liked pages</a><br>
               <a href="'.Links::generate('rss').'">RSS Feed</a><br>
          ' )).'
          <br>
          <a href="'.Links::generate('logout').'">Logout</a><br>
        </p>
      ';
    }

    self::template($content);
  }
  
  /**
   * Page: Sign Up.
   * @param array of strings of errors
   */
  public static function signUp(array $errors)
  {
    $content = '
      <script>
        function signupFormCheck() {
          var form = self.document.forms.signup;

          if (form.username.value.length < 3) {
            alert("Username has incorrect format.")
            return false; 
          }

          if (form.password.value.length <= 3) {
            alert("Password has incorrect format.")
            return false; 
          }

          if (!(form.password.value === form.passwordVerify.value)) {
            alert("Passwords does not match.")
            return false; 
          }
        }
      </script>
    ';
    
    $content .= "<h2>Sign Up</h2>";
    
    if (count($errors) > 0) {
      $content .= "<h3>Errors:</h3><ul>";
      foreach ($errors as $error) {
        $content .= "<li>".$error."</li>";
      }
      $content .= "</ul>";
    }
    
    $content .= '
      <form action="'.Links::generate('signup').'" method="post" id="signup"
        onsubmit="return signupFormCheck()">
        <table>
          <tr>
            <td>Username:</td>
            <td><input maxlength="50" name="username" type="text" value="'
              .(isset($_POST['username'])? Printer::html($_POST['username']) : '').'"></td>
            <td>(3-50 characters)</td>
          <tr>
          <tr>
            <td>Password:</td>
            <td><input name="password" type="password"></td>
            <td>(>3 characters)</td>
          <tr>
          <tr>
            <td>Verify password:</td>
            <td><input name="passwordVerify" type="password"></td>
            <td></td>
          <tr>
        <table>
        <input type="submit" value="Sign Up">
      </form>
    ';
    
    self::template($content);
  }
  
  /**
   * Page: Liked pages.
   * @param array data(name,picture,link,last_posted)
   */
  public static function likedPages(array $data)
  {
    $content = '<br><h2>User: '.Printer::html(Sessions::$user->username).'</h2>'
      .'<h3>List of liked pages</h3>';
    $content .= '
      <table>
        <tr>
          <th colspan="2">page</th>
          <th>last posted</th>
        </tr>
    ';
    foreach ($data as $row) {
      $content .= '
        <tr>
          <td><img src="'.$row['picture'].'">&nbsp;&nbsp;</td>
          <td>
            <a href="'.$row['link'].'">'.Printer::html($row['name']).'</a>
          </td>
          <td>'.((!empty($row['last_posted']))? 
              $row['last_posted']->format('Y-m-d H:i:s') : '-').'</td>
        </tr>
      ';
    }
    $content .= '</table><br>';
    self::template($content);
  }
  
}