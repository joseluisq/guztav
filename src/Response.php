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

    /**
     * Covert an array to SimpleXMLElement.
     *
     * @param  array $data
     * @param  string $basenode
     * @param  \SimpleXMLElement $xml
     * @return \SimpleXMLElement
     */
    private function arrayToXML($data = [], $basenode = 'response', $xml = null)
    {
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }

        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
        }

        if (!is_array($data)) {
            return $xml;
        }

        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = "item_" . (string) $key;
            }

            $key = preg_replace('/[^a-z]/i', '', $key);

            if (is_array($value)) {
                $node = $xml->addChild($key);
                $this->arrayToXML($value, $basenode, $node);
            } else {
                $value = htmlentities($value);
                $xml->addChild($key, $value);
            }
        }

        return $xml;
    }
}
