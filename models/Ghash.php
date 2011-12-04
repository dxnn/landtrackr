<?php

/**
 * Geohash wrapper
 *
 * @package landtrackr
 * @author dann toliver
 * @version 1.0
 */

include_once('/home/landtrackr.eddyapp.com/web/public/daimio/libs/geohash.class.php'); // ugh 

class Ghash
{
  
  /**
  * encode coords
  * @param string Latitude
  * @param string Longitude
  * @return string
  * @key __world
  */
  static function encode($lat, $long)
  {
    $geohash = new Geohash;
    return $geohash->encode($lat, $long);
  }


  /**
  * decode a geohash
  * @param string Geohash
  * @return array
  * @key __world
  */
  static function decode($hash)
  {
    $geohash = new Geohash;
    return $geohash->decode($hash);
  }
  
  /**
  * 42 is .5, 20.3 is .05, etc
  * @param number Some number
  * @return number
  * @key __world
  */
  static function precision($number)
  {
    $geohash = new Geohash;
    return $geohash->precision($number);
  }
  
}

// EOT