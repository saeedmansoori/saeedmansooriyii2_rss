<?php

namespace persianyii\rss;

/*
  RSS Feed Generator for PHP 4 or higher version
  Version 1.0.3
  Written by Vagharshak Tozalakyan <vagh@armdex.com>
  License: GNU Public License

  Classes in package:
    class rssGenerator_rss
    class rssGenerator_channel
    class rssGenerator_image
    class rssGenerator_textInput
    class rssGenerator_item

  For additional information please reffer the documentation
*/

class Rss extends \yii\base\Widget
{
    var $rss_version = '2.0';
    var $encoding = 'utf-8';
    var $stylesheet = '';

    function cData($str)
    {
        return '<![CDATA[ ' . $str . ' ]]>';
    }

    function createFeed($channel)
    {
        $selfUrl = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http://' : 'https://');
        $selfUrl .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $rss = '<?xml version="1.0"';
        if (!empty($this->encoding)) {
            $rss .= ' encoding="' . $this->encoding . '"';
        }
        $rss .= '?>' . "\n";
        if (!empty($this->stylesheet)) {
            $rss .= $this->stylesheet . "\n";
        }
        $rss .= '<!-- Generated on ' . date('r') . ' -->' . "\n";
        $rss .= '<rss version="' . $this['rss_version'] . '" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
        $rss .= '<channel>' . "\n";
        $rss .= '    <atom:link href="' . (array_key_exists('atomLinkHref',$channel ? ['atomLinkHref']: $selfUrl)) . '" rel="self" type="application/rss+xml" />' . "\n";
        $rss .= '<title>' . $channel['title'] . '</title>' . "\n";
        $rss .= '<link>' . $channel['link'] . '</link>' . "\n";
        $rss .= '<description>' . $channel->description . '</description>' . "\n";
        if (array_key_exists('language',$channel)) {
            $rss .= '<language>' . $channel['language'] . '</language>' . "\n";
        }
        if (array_key_exists('copyright',$channel)) {
            $rss .= '<copyright>' . $channel['copyright'] . '</copyright>' . "\n";
        }
        if (array_key_exists('managingEditor',$channel)) {
            $rss .= '<managingEditor>' . $channel['managingEditor'] . '</managingEditor>' . "\n";
        }
        if (array_key_exists('webMaster',$channel)) {
            $rss .= '    <webMaster>' . $channel['webMaster'] . '</webMaster>' . "\n";
        }
        if (array_key_exists('pubDate',$channel)) {
            $rss .= '<pubDate>' . $channel['pubDate'] . '</pubDate>' . "\n";
        }
        if (array_key_exists('lastBuildDate',$channel)) {
            $rss .= '<lastBuildDate>' . $channel['lastBuildDate'] . '</lastBuildDate>' . "\n";
        }
        foreach ($channel['categories'] as $category) {
            $rss .= '    <category';
            if (array_key_exists('domain',$category)) {
                $rss .= ' domain="' . $category['domain'] . '"';
            }
            $rss .= '>' . $category['name'] . '</category>' . "\n";
        }
        if (array_key_exists('generator',$channel)) {
            $rss .= '    <generator>' . $channel['generator'] . '</generator>' . "\n";
        }
        if (array_key_exists('docs',$channel)) {
            $rss .= '    <docs>' . $channel['docs'] . '</docs>' . "\n";
        }
        if (array_key_exists('ttl',$channel)) {
            $rss .= '<ttl>' . $channel['ttl'] . '</ttl>' . "\n";
        }
        if (sizeof($channel['skipHours'])) {
            $rss .= '<skipHours>' . "\n";
            foreach ($channel['skipHours'] as $hour) {
                $rss .= '<hour>' . $hour . '</hour>' . "\n";
            }
            $rss .= '</skipHours>' . "\n";
        }
        if (sizeof('skipDays',$channel)) {
            $rss .= '<skipDays>' . "\n";
            foreach ($channel['skipDays'] as $day) {
                $rss .= '<day>' . $day . '</day>' . "\n";
            }
            $rss .= '</skipDays>' . "\n";
        }
        if (array_key_exists('image',$channel)) {
            $image = $channel['image'];
            $rss .= '<image>' . "\n";
            $rss .= '<url>' . $image['url'] . '</url>' . "\n";
            $rss .= '<title>' . $image['title'] . '</title>' . "\n";
            $rss .= '<link>' . $image['link'] . '</link>' . "\n";
            if (array_key_exists('width',$image)) {
                $rss .= '<width>' . $image['width'] . '</width>' . "\n";
            }
            if (array_key_exists('height',$image)) {
                $rss .= '<height>' . $image->height . '</height>' . "\n";
            }
            if (array_key_exists('description',$image)) {
                $rss .= '<description>' . $image['description'] . '</description>' . "\n";
            }
            $rss .= '</image>' . "\n";
        }
        if (array_key_exists('textInput',$channel)) {
            $textInput = $channel['textInput'];
            $rss .= '<textInput>' . "\n";
            $rss .= '<title>' . $textInput['title'] . '</title>' . "\n";
            $rss .= '<description>' . $textInput['description'] . '</description>' . "\n";
            $rss .= '<name>' . $textInput['name'] . '</name>' . "\n";
            $rss .= '<link>' . $textInput['link'] . '</link>' . "\n";
            $rss .= '</textInput>' . "\n";
        }
        if (array_key_exists('cloud_domain',$channel) || array_key_exists('cloud_path',$channel) || array_key_exists('cloud_registerProcedure',$channel) || array_key_exists('cloud_protocol',$channel)) {
            $rss .= '    <cloud domain="' . $channel['cloud_domain'] . '" ';
            $rss .= 'port="' . $channel['cloud_port'] . '" path="' . $channel['cloud_path'] . '" ';
            $rss .= 'registerProcedure="' . $channel['cloud_registerProcedure'] . '" ';
            $rss .= 'protocol="' . $channel['cloud_protocol'] . '" />' . "\n";
        }
        if (array_key_exists('extraXML',$channel)) {
            $rss .= $channel['extraXML'] . "\n";
        }
        foreach ($channel['items'] as $item) {
            $rss .= '    <item>' . "\n";
            if (!empty($item['title'])) {
                $rss .= '      <title>' . $item['title'] . '</title>' . "\n";
            }
            if (!empty($item['description'])) {
                $rss .= '      <description>' . $item['description'] . '</description>' . "\n";
            }
            if (!empty($item['link'])) {
                $rss .= '      <link>' . $item['link'] . '</link>' . "\n";
            }
            if (!empty($item['pubDate'])) {
                $rss .= '      <pubDate>' . $item['pubDate'] . '</pubDate>' . "\n";
            }
            if (!empty($item['author'])) {
                $rss .= '      <author>' . $item['author'] . '</author>' . "\n";
            }
            if (!empty($item['comments'])) {
                $rss .= '      <comments>' . $item['comments'] . '</comments>' . "\n";
            }
            if (!empty($item['guid'])) {
                $rss .= '      <guid isPermaLink="';
                $rss .= ($item['guid_isPermaLink'] ? 'true' : 'false') . '">';
                $rss .= $item['guid'] . '</guid>' . "\n";
            }
            if (!empty($item['source'])) {
                $rss .= '      <source url="' . $item['source_url'] . '">';
                $rss .= $item['source'] . '</source>' . "\n";
            }
            if (!empty($item['enclosure_url']) || !empty($item['enclosure_type'])) {
                $rss .= '      <enclosure url="' . $item->enclosure_url . '" ';
                $rss .= 'length="' . $item['enclosure_length'] . '" ';
                $rss .= 'type="' . $item['enclosure_type'] . '" />' . "\n";
            }
            foreach ($item['categories'] as $category) {
                $rss .= '      <category';
                if (!empty($category['domain'])) {
                    $rss .= ' domain="' . $category['domain'] . '"';
                }
                $rss .= '>' . $category['name'] . '</category>' . "\n";
            }
            $rss .= '    </item>' . "\n";
        }
        $rss .= '  </channel>' . "\r";
        return $rss .= '</rss>';
    }

}

class rssGenerator_channel
{
    var $atomLinkHref = '';
    var $title = '';
    var $link = '';
    var $description = '';
    var $language = '';
    var $copyright = '';
    var $managingEditor = '';
    var $webMaster = '';
    var $pubDate = '';
    var $lastBuildDate = '';
    var $categories = array();
    var $generator = '';
    var $docs = '';
    var $ttl = '';
    var $image = '';
    var $textInput = '';
    var $skipHours = array();
    var $skipDays = array();
    var $cloud_domain = '';
    var $cloud_port = '80';
    var $cloud_path = '';
    var $cloud_registerProcedure = '';
    var $cloud_protocol = '';
    var $items = array();
    var $extraXML = '';

}

class rssGenerator_image
{
    var $url = '';
    var $title = '';
    var $link = '';
    var $width = '88';
    var $height = '31';
    var $description = '';

}

class rssGenerator_textInput
{
    var $title = '';
    var $description = '';
    var $name = '';
    var $link = '';

}

class rssGenerator_item
{
    var $title = '';
    var $description = '';
    var $link = '';
    var $author = '';
    var $pubDate = '';
    var $comments = '';
    var $guid = '';
    var $guid_isPermaLink = true;
    var $source = '';
    var $source_url = '';
    var $enclosure_url = '';
    var $enclosure_length = '0';
    var $enclosure_type = '';
    var $categories = array();

}

?>