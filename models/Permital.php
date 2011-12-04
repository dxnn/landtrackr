<?php

/**
 * Control land permits
 *
 * @package landtrackr
 * @author dann toliver
 * @version 1.0
 */

class Permital
{
  
  /** 
  * Find some permits
  * @param string Permit ids
  * @param string Region hash
  * @param string Partial region hash
  * @param string Some tags
  * @param string Supports sort, limit, skip, fields, nofields, and count: {* (:limit 5 :skip "30" :sort {* (:name "-1")} :nofields (:pcache :scores) :fields :name)} or {* (:count :true)}
  * @return array 
  * @key __world
  */ 
  static function find($by_ids=NULL, $by_region=NULL, $by_hash=NULL, $by_tags=NULL, $options=NULL)
  {
    if(isset($by_ids))
      $query['_id'] = array('$in' => MongoLib::fix_ids($by_ids));

    if(isset($by_region))
      $query['region'] = $by_region;
    
    if(isset($by_hash))
      $query['region'] = new MongoRegex("/^$by_hash/");
    
    if($by_tags) {
      if(!is_array($by_tags))
        $by_tags = array($by_tags);
      
      $query['tags']['$all'] = $by_tags;
    }
    
    return MongoLib::find('permits', $query, NULL, $options);
  }
  
  /** 
  * Add a new permit 
  * @param string A region hash
  * @return array 
  * @key __world
  */ 
  static function add($region)
  {
    if(!$region = MongoLib::findOne('regions', array('hash' => $region)))
      return ErrorLib::set_error("Invalid region");
    
    // all clear!
    
    $permit['region'] = $region['hash'];
    MongoLib::insert('permits', $permit);
    
    return $permit['_id'];
  }
  
  /** 
  * Set a permit param 
  * @param string Type of param (name, desc, goals)
  * @param string New value
  * @param string Permit id
  * @return hash 
  * @key __world
  */ 
  static function set($my, $to, $for)
  {
    $id = MongoLib::fix_id($for);
    $update[$my] = $to;
    MongoLib::set('permits', $id, $update);
    return $id;
  }

}

// EOT