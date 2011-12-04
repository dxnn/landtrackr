<?php

/**
 * All things regional
 *
 * @package landtrackr
 * @author dann toliver
 * @version 1.0
 */

class Region
{
  
  /** 
  * Find some regions
  * @param string A region id
  * @param string A geohash
  * @param string Some tags
  * @param string Supports sort, limit, skip, fields, nofields, and count: {* (:limit 5 :skip "30" :sort {* (:name "-1")} :nofields (:pcache :scores) :fields :name)} or {* (:count :true)}
  * @return array 
  * @key __world
  */ 
  static function find($by_ids=NULL, $by_hash=NULL, $by_tags=NULL, $options=NULL)
  {
    if(isset($by_ids))
      $query['_id'] = array('$in' => MongoLib::fix_ids($by_ids));
    
    if(isset($by_hash))
      $query['hash'] = MongoLib::fix_id($by_hash);

    if($by_tags) {
      if(!is_array($by_tags))
        $by_tags = array($by_tags);
      
      $query['tags']['$all'] = $by_tags;
    }
    
    return MongoLib::find('regions', $query, NULL, $options);
  }
  
  
  /** 
  * Add a new region 
  * @param string A geohash
  * @return array 
  * @key __world
  */ 
  static function add($hash)
  {
    if((!$coords = Ghash::decode($hash)) || ($coords[0] == 0))
      return ErrorLib::set_error("That is not an appropriate hash");
    
    if(Region::find($hash))
      return ErrorLib::set_error("A region with that hash already exists");
    
    // all clear!
    
    $region['hash'] = $hash;
    $region['lat'] = $coords[0];
    $region['long'] = $coords[1];
    
    MongoLib::insert('regions', $region);
    
    return $region;
  }
  
  /** 
  * Set a region name 
  * @param string Region id
  * @param string New name
  * @return hash 
  * @key __world
  */ 
  static function set_name($for, $to)
  {
    $id = MongoLib::fix_id($for);
    $update['name'] = $to;
    MongoLib::set('regions', $id, $update);
    return $id;
  }
  
  /** 
  * Set a region description 
  * @param string Region id
  * @param string New description
  * @return hash 
  * @key __world
  */ 
  static function set_desc($for, $to)
  {
    $id = MongoLib::fix_id($for);
    $update['desc'] = $to;
    MongoLib::set('regions', $id, $update);
    return $id;
  }
  

}

// EOT