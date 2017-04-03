<?php
/*
 * This file is part of Guztav.
 *
 * (c) Jose Luis Quintana <https://git.io/joseluisq>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Guztav;

/**
 * Response class extented from \GuzzleHttp\Psr7\Response.
 */
class Response extends \GuzzleHttp\Psr7\Response
{
    /**
    * Convert \GuzzleHttp\Psr7\Response object into string.
    *
    * @return string
    */
    public function toString()
    {
        return (string) $this->getBody();
    }

    /**
     * Convert \GuzzleHttp\Psr7\Response object into json associative array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $content_type = '';

        if ($this->hasHeader('Content-Type')) {
            $content_type = $this->getHeader('Content-Type')[0];
        }

        // JSON: application/json
        if (preg_match('/application\/json/i', $content_type)) {
            $data = json_decode($this->toString(), true);
        }

        return $data;
    }

    public function to_xml($data, $basenode = 'response', $xml = null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }
        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
        }
        // loop through the data passed in.
        foreach ($data as $key => $value) {
            // no numeric keys in our xml please!
          if (is_numeric($key)) {
              // make string key...
            $key = "item_" . (string) $key;
          }
          // replace anything not alpha numeric
          $key = preg_replace('/[^a-z]/i', '', $key);
          // if there is another array found recrusively call this function
          if (is_array($value)) {
              $node = $xml->addChild($key);
            // recrusive call.
            $this->to_xml($value, $basenode, $node);
          } else {
              // add single node.
            $value = htmlentities($value);
              $xml->addChild($key, $value);
          }
        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }
}
