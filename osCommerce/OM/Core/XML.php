<?php
/**
 * osCommerce Online Merchant
 * 
 * @copyright Copyright (c) 2011 osCommerce; http://www.oscommerce.com
 * @license BSD License; http://www.oscommerce.com/bsdlicense.txt
 */

  namespace osCommerce\OM\Core;

  class XML {
    public static function toArray($xml) {
      if ( $xml instanceof \SimpleXMLElement ) {
        $attributes = $xml->attributes();

        foreach ( $attributes as $k => $v ) {
          if ( $v ) {
            $a[$k] = (string)$v;
          }
        }

        $x = $xml;
        $xml = get_object_vars($xml);
      }

      if ( is_array($xml) ) {
        if ( count($xml) == 0 ) {
          return (string)$x; // for CDATA
        }

        foreach ( $xml as $key => $value ) {
          $r[$key] = self::toArray($value);
        }

        if ( isset($a) ) {
          $r['@attributes'] = $a; // attributes
        }

        return $r;
      }

      return (string)$xml;
    }

    public static function fromArray($data, $encoding = 'UTF-8') {
      $xml = new \XMLWriter();
      $xml->openMemory();
      $xml->setIndent(true);
      $xml->setIndentString('  ');
      $xml->startDocument('1.0', $encoding);
      self::_write($xml, $data);
      $xml->endDocument();

      return $xml->outputMemory(true);
    }

    protected static function _write(\XMLWriter $xml, $data, $parent = null, $add_to_parent_element = false) {
      foreach ( $data as $key => $value ) {
        if ( is_array($value) ) {
          if ( is_int($key) ) {
            if ( $add_to_parent_element === false ) {
              $add_to_parent_element = true;

              self::_write($xml, $value, $parent, $add_to_parent_element);
              $xml->endElement();
            } else {
              $xml->startElement($parent);
              self::_write($xml, $value, $parent, $add_to_parent_element);
              $xml->endElement();
            }
          } else {
            $xml->startElement($key);
            self::_write($xml, $value, $key, $add_to_parent_element);
            $xml->endElement();
          }
         } else {
          if ( ($pos = strpos($key, '-CDATA')) !== false ) {
            $key = substr($key, 0, $pos);

            $xml->startElement($key);
            $xml->writeCData($value);
            $xml->endElement();
          } else {
            $xml->writeElement($key, $value);
          }
        }
      }
    }
  }
?>
