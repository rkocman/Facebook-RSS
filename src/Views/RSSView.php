<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS\Views;

use FacebookRSS\Constants,
    FacebookRSS\Utils\Links,
    FacebookRSS\Utils\Printer;

/**
 * RSS template.
 */
class RSSView
{
  
  /**
   * Prints the basic rss structure.
   * @param string content
   */
  private static function printRSS($content)
  {
    header("Content-type: text/xml; charset=utf-8", true);
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '
      <rss version="2.0">
        <channel>
          <title>'.Constants::title.'</title>
          <pubDate>'.date("D, d M Y H:i:s O").'</pubDate>
          <link>https://facebook.com/</link>
    ';
    echo $content;
    echo '
        </channel>
      </rss>
    ';
  }
  
  /**
   * Prints the rss reconnect warning.
   */
  public static function printRSSReconnect()
  {
    $content = '
      <item>
        <title>Facebook connection expired!</title>
        <pubDate>'.date("D, d M Y H:i:s O").'</pubDate>
        <link>'.Links::generateFull().'</link>
        <description><![CDATA[
          Your Facebook connection expired! 
          Please, <a href="'.Links::generateFull().'">reconnect your account</a>.
        ]]></description>
      </item>
    ';
    self::printRSS($content);
  }
  
  /**
   * Prints the posts as rss items.
   * @param array data(name,username,picture,post(id,created_time,actions,
   *   message,type,picture,link,name,caption,description,story,attachments))
   */
  public static function printRSSPosts($data)
  {
    $content = '';
    
    foreach ($data as $item) {
      $content .= '<item>';
      
      $content .= '<title>'.Printer::xml($item['name']).': ';
      if (isset($item['post']['message'])) {
        $content .= Printer::xml($item['post']['message']);
      } else if (isset($item['post']['story'])) {
        $content .= Printer::xml($item['post']['story']);
      }
      $content .= '</title>';
      
      $content .= '<author>'.Printer::xml($item['name']).'</author>';
      $content .= '<pubDate>'
        .($item['post']['created_time']->format('D, d M Y H:i:s O'))
        .'</pubDate>';
      
      $content .= '<guid isPermaLink="false">'.$item['post']['id'].'</guid>';
      if (isset($item['post']['actions'][0]['link'])) {
        $content .= '<link>'.Printer::xml($item['post']['actions'][0]['link']).'</link>';
      } elseif ($item['post']['link']) {
        $content .= '<link>'.Printer::xml($item['post']['link']).'</link>';
      }
      $content .= '<category>'.Printer::xml($item['post']['type']).'</category>';
      
      $content .= '<description><![CDATA[';
      
      
      $content .= '<table><tr>
        <td><img src="'.Printer::xml($item['picture'])
        .'" alt="" style="width:50px;height:50px;"></td>'
        .'<td style="padding-left:7px;">';
      if (isset($item['post']['story'])) {
        $content .= '<span style="color:#787878;">'
          .Printer::xml($item['post']['story']).'</span><br>';
      } elseif (
        isset($item['post']['attachments']) 
        && $item['post']['type'] === 'status'
      ) {
        foreach ($item['post']['attachments'] as $attachment) {
          if (isset($attachment['title'])) {
            $content .= '<span style="color:#787878;">'
              .Printer::xml($attachment['title']).'</span><br>';
          }
        }
      }
      if (isset($item['post']['message'])) {
        $content .= Printer::xml($item['post']['message']);
      }
      $content .= '</td>
        </tr></table>';
      
      switch ($item['post']['type']) { 
       
        default: break;
          
        case 'video':
        case 'link':
        case 'event':
          $content .= '<table><tr><td>';
          if (isset($item['post']['picture'])) {
            $content .= '<a href="'.Printer::xml($item['post']['link']).'"
            ><img src="'.Printer::xml($item['post']['picture']).'"
              alt="" style="border:0"></a>';
            $content .= '</td><td>';
          }
          if (isset($item['post']['name'])) {
            $content .= '<strong><a href="'.Printer::xml($item['post']['link'])
              .'">'.Printer::xml($item['post']['name']).'</a></strong><br>';
            if (isset($item['post']['caption'])) {
              $content .= '<small>'.Printer::xml($item['post']['caption']).'</small><br>';
            }
            if (isset($item['post']['description'])) {
              $content .= Printer::xml($item['post']['description']);
            }
          } else {
            $content .= '<a href="'.Printer::xml($item['post']['link']).'"
              >'.Printer::xml($item['post']['link']).'</a>';
          }
          $content .= '</td></tr></table>';
          break;
          
        case 'photo':
          $content .= self::preparePostAttachements($item['post']);
          break;
        
      }
      
      $content .= ']]></description>';
        
      $content .= '</item>';
    }
    
    self::printRSS($content);
  }
  
  /**
   * Prepares attachements from the post.
   * @param object post(id,created_time,actions,
   *   message,type,picture,link,name,caption,description,story,attachments)
   * @return string
   */
  private static function preparePostAttachements($post)
  {
    $content = '';
    
    if (!isset($post['attachments'])) {
      return $content;
    }
    
    foreach ($post['attachments'] as $attachement) {

      // single attachment
      if (isset($attachement['media']) && isset($attachement['media']['image'])) {
        $content .= '<a href="'.Printer::xml($post['link']).'"
          ><img src="'.Printer::xml($attachement['media']['image']['src']).'"
            alt="" style="border:0"
            width="'.$attachement['media']['image']['width'].'" 
            height="'.$attachement['media']['image']['height'].'"></a>';
      }

      // multiple attachments
      if (isset($attachement['subattachments'])) {
        foreach ($attachement['subattachments'] as $subattachement) {
          if (isset($subattachement['media']) 
           && isset($subattachement['media']['image'])) {
            $content .= '<a href="'.Printer::xml($subattachement['url']).'"
              ><img src="'.Printer::xml($subattachement['media']['image']['src']).'"
                alt="" style="border:0"
                width="'.$subattachement['media']['image']['width'].'" 
                height="'.$subattachement['media']['image']['height'].'"></a> ';
          }
        }
      }

    }
    
    return $content;
  }
  
}
