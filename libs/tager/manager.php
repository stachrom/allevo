<?php
/**
 * Copyright 2008-2012 Horde LLC (http://www.horde.org/)
 *
 * @author   Chuck Hagenbuch <chuck@horde.org>
 * @author   Michael J. Rubinsky <mrubinsk@horde.org>
 * @license  http://www.horde.org/licenses/bsd BSD
 * @category Horde
 * @package  Horde_Content
 */

/**
 * @author   Chuck Hagenbuch <chuck@horde.org>
 * @author   Michael J. Rubinsky <mrubinsk@horde.org>
 * @author   Roman Stachura <roman@stachura.ch>
 * @license  http://www.horde.org/licenses/bsd BSD
 * @category Horde
 * @package  Horde_Content
 *
 * References:
 *   http://forge.mysql.com/wiki/TagSchema
 *   http://www.slideshare.net/edbond/tagging-and-folksonomy-schema-design-for-scalability-and-performance
 *   http://blog.thinkphp.de/archives/124-An-alternative-Approach-to-Tagging.html
 *   http://code.google.com/p/freetag/
 *
 * @TODO:
 *   need to add type_id to the rampage_tagged table for performance?
 *   need stat tables by type_id?
 *
 * Potential features:
 *   Infer data from combined tags (capital + washington d.c. - http://www.slideshare.net/kakul/tagging-web-2-expo-2008/)
 *   Normalize tag text (http://tagsonomy.com/index.php/interview-with-gordon-luk-freetag/)
 */
class Content_Tagger
{

    /**
     * Database connection
     * @var mdb2
     */
    protected $mdb2;
	 
	 
	   /**
     * Tables
     * @var array
     */
    protected $_tables = array(
        'tags' => 'rampage_tags',
        'tagged' => 'rampage_tagged',
        'objects' => 'rampage_objects',
        'tag_stats' => 'rampage_tag_stats',
        'user_tag_stats' => 'rampage_user_tag_stats',
        'users' => 'rampage_users',
        'types' => 'rampage_types',
    );

   
    /**
     * User manager object
     * @var Content_Users_Manager
     */
    protected $_userManager;

    /**
     * Type management object
     * @var Content_Types_Manager
     */
    protected $_typeManager;

    /**
     * Object manager
     * @var Content_Objects_Manager
     */
    protected $_objectManager;

    /**
     * Default radius for relationship queries.
     * @var integer
     */
    protected $_defaultRadius = 10;

    /**
     * Constructor
     */
    public function __construct($mdb2)
    {
        $this->_db = $mdb2;
    }

    /**
     * Adds a tag or several tags to an object_id. This method does not
     * remove other tags.
     *
     * @param mixed       $userId    The user tagging the object.
     * @param mixed       $objectId  The object id to tag or an array containing
     *                               the object_name and type.
     * @param array       $tags      An array of tag name or ids.
     * @param Horde_Date  $created   The datetime of the tagging operation.
     *
     * @return void
     */
    public function tag($userId, $objectId, $tags, Horde_Date $created = null)
    {
        if (is_null($created)) {
            $created = date('Y-m-d H:i:s');
        } else {
            $created = $created->format('Y-m-d H:i:s');
        }

        // Make sure the object exists
        $objectId = $this->_ensureObject($objectId);

        // Validate/ensure the parameters
        $userId = current($this->ensureUsers($userId));

		  // pick the tagged table mbd2
		  $t_ = array(
				  'tagged' => $this->_tables['tagged'],
				  'tag_stats' => $this->_tables['tag_stats'],
				  'user_tag_stats' => $this->_tables['user_tag_stats']
		  ); 

        foreach ($this->ensureTags($tags) as $tagId) {
		  
		  		$query='SELECT 1 from '.$t_[tagged].' WHERE user_id ='. (int)$userId .' AND object_id ='. (int)$objectId .' AND tag_id ='. (int)$tagId;

            if (!$this->_db->queryOne($query)) {
				  
				   $reslut =  $this->_db->exec('INSERT INTO '.$t_[tagged].' (user_id, object_id, tag_id, created) VALUES ('
									. (int)$userId. ', ' 
									. (int)$objectId. ', ' 
									. (int)$tagId. ', "' 
									. $created.'")');
            }

            // increment tag stats
            if (!$this->_db->exec('UPDATE '.$t_[tag_stats].' SET count = count + 1 WHERE tag_id = ' . (int)$tagId)) {
					  $this->_db->exec('INSERT INTO '.$t_[tag_stats].' (tag_id, count) VALUES (' . (int)$tagId . ', 1)');
            }

            // increment user-tag stats
            if (!$this->_db->exec('UPDATE '.$t_[user_tag_stats]. ' SET count = count + 1 WHERE user_id = ' . (int)$userId . ' AND tag_id = ' . (int)$tagId )) {
					 $this->_db->exec('INSERT INTO '.$t_[user_tag_stats].' (user_id, tag_id, count) VALUES (' . (int)$userId . ', ' . (int)$tagId . ', 1)');
            }
        }
    }

	
	 /**
     * Remove all occurrences of a specific tag from an object regardless of
     * the username who tagged the object originally.
     *
     * @param mixed  $obejctId  The object identifier @see Content_Tagger::tag()
     * @param mixed  $tags      The tags to remove. @see Content_Tagger::tag()
     *
     * @return void
     */
    public function removeTagFromObject($objectId, $tags){
	
        $objectId = $this->_ensureObject($objectId);
        if (!is_array($tags)) {
            $tags = array($tags);
        }
		
        foreach ($this->ensureTags($tags) as $tagId) {

            // Get the users who have tagged this so we can update the stats
			
			$query = 'SELECT user_id, tag_id FROM '. $this->_tables['tagged'] .' WHERE object_id = '.$objectId.' AND tag_id = '.$tagId;
			$users = $this->_db->queryAll($query);
			
			$sql_delete = 'DELETE FROM ' . $this->_tables['tagged'] . ' WHERE object_id = '.$objectId.' AND tag_id ='.$tagId;
			
			$affected_rows =& $this->_db->exec($sql_delete);
			

            // Delete the tags
            if ( $affected_rows == 1 ) {
                // Update the stats: fuuuuck its not working
				
			
				$query_update[0]='UPDATE ' . $this->_tables['tag_stats'] . ' SET count = count - ' . count($users) . ' WHERE tag_id ='. $tagId;

				$i = 1;
				foreach( $users as $key => $user){
					$query_update[$i] = 'UPDATE ' . $this->_tables['user_tag_stats'] . ' SET count = count - 1 WHERE user_id = '.$user[0].' AND tag_id ='. $tagId;
					$i = $i+1;
				}
				
				foreach($query_update as $key => $sql){
					$info[] = $this->_db->exec($sql);
				}

                // Housekeeping
				$cleanup[0] = 'DELETE FROM ' . $this->_tables['tag_stats'] . ' WHERE count = 0';
				$cleanup[1] = 'DELETE FROM ' . $this->_tables['user_tag_stats'] . ' WHERE count = 0';
			  
			  
				foreach($cleanup as $key => $sql){
					$this->_db->exec($sql);
				}

				        ob_start();
						//var_dump($_REQUEST);
						print_r($info);
						$manager = ob_get_contents();
						ob_end_clean();
						
						$GLOBALS[logger]->log('$affected_rows '. $manager, PEAR_LOG_DEBUG );
				
				

            }
        }
    }
	
	
	
	

    /**
     * Undo a user's tagging of an object or remove the tag completely from tagger.
     *
     * @param mixed       $userId    The user who tagged the object.
     * @param mixed       $objectId  The object to remove the tag from.
     * @param array       $tags      An array of tag name or ids to remove.
	 * @param mixed       $action    untag a tag from user/object or delete a tag.
     */
	 
    public function removetag($action, $userId, $objectId, $tags)
    {
        // Ensure parameters
        $userId = current($this->ensureUsers($userId));
        $objectId = $this->_ensureObject($objectId);
		  
		  
		if($action == "remove_tag"){
		  
			$table_name =array(
		  		'tagged' => $this->_tables['tagged'],
		  		'tag_stats' => $this->_tables['tag_stats'],
				'user_tag_stats' => $this->_tables['user_tag_stats'],
				'tags' => $this->_tables['tags']
		  		);

			$tag_id = (int)$tags[0];

			$fields_values = array(
				'tag_id' => $tag_id
			);
			
			$types = array('integer');
		  
			$this->_db->loadModule('Extended');
		  
			foreach($table_name as $key => $value){
				$affectedRows[$key] = $this->_db->extended->autoExecute($value, null,
                    MDB2_AUTOQUERY_DELETE, 'tag_id = '.$this->_db->quote($tag_id, 'integer'));
			}

			return $affectedRows;

		 }elseif( $action == "untag" && $userId && objectId ){

			  foreach ($this->ensureTags($tags) as $tagId) {
			  
	
				  $where_statement = array(
						 'user_id '  => $tagId,
						 'object_id' => $userId,
						 'tag_id'    => $objectId
					);
		 
				  $mdb2->loadModule('Extended');
				
				  $affectedRows = $mdb2->extended->autoExecute($table_name['tagged'], null, MDB2_AUTOQUERY_DELETE, $where_statement); 
	
				  if ($affectedRows == 1) {
						  $update[0]='UPDATE ' . $table_name['tag_stats'] . ' SET count = count - 1 WHERE tag_id ='. $tagId;
						  $update[1]='UPDATE ' . $table_name['user_tag_stats'] . ' SET count = count - 1 WHERE user_id = ' . $userId .' AND tag_id =' . $tagId;
						  
						  foreach($update as $key => $sql){
		  					$this->_db->exec($sql);
		  				 }  
					}
			  }
			  
			  // Cleanup
		  	  $cleanup[0] = 'DELETE FROM ' . $table_name['tag_stats'] . ' WHERE count = 0';
              $cleanup[1] = 'DELETE FROM ' . $table_name['user_tag_stats'] . ' WHERE count = 0';
		  
		     foreach($cleanup as $key => $sql){
		  		  $this->_db->exec($sql);
		     }
		  }
		  

        
       
    }




    /**
     * Check if tags exists, optionally create then if they don't and return
     * ids for all that exist (including those that are optionally created).
     *
     * @param string|array $tags    The tag names to check.
     * @param boolean      $create  If true, create the tag in the tags table.
     *
     * @return array
     */
    protected function _checkTags($tags, $create = true)
    {
        if (empty($tags)) {
            return array();
        }

        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $tagIds = array();

	$table_name = $this->_tables['tags'];

        // Anything already typed as an integer is assumed to be a tag id.
        foreach ($tags as $tagIndex => $tag) {

		if (is_int($tag)) {
                	$tagIds[] = $tag;
                	continue;
            	}

            // Don't attempt to tag with an empty value
		if (!strlen(trim($tag))) {
                	continue;
            	}

            // Get the ids for any tags that already exist.
            	$query = 'SELECT tag_id FROM '. $table_name .' WHERE LOWER(tag_name) = LOWER("'.$tag.'")';

           	if ($id = $this->_db->queryOne($query)) {

                	$tagIds[] = $id;

            	} elseif ($create) {

			$fields_values = array(
				'tag_name' => trim($tag)
			);
				
			$types = array('text');

			$this->_db->loadModule('Extended');

             	// Create any tags that didn't already exist
			if ( $this->_db->extended->autoExecute($table_name, $fields_values, MDB2_AUTOQUERY_INSERT, null, $types) ) {
                		$tagIds[] = $this->_db->queryOne($query);
			}
            	}
        }

        return $tagIds;
    }
	 
	 
     /**
     * Retrieve tags based on criteria.
     *
     * @param array  $args  Search criteria:
     *   q          Starts-with search "string".
	 *	 type       object, tag, type, user
     *   limit      Maximum number of tags to return.
     *   offset     Offset the results. Only useful for paginating, and not recommended.
	 *   output     array or json	  
     *
     * @return array  An array of tags, id => name.
     */

	  
    public function search($args)
    {
       
		 if( $args['type'] == "type" ){
			$sql = 'SELECT * FROM '. $this->_tables['types'] .' WHERE LOWER(type_name) LIKE LOWER("%'. $args['q'] .'%")';
			
		 }elseif($args['type'] == "object"){
			$sql = 'SELECT * FROM '. $this->_tables['objects'] .' WHERE LOWER(object_name) LIKE LOWER("%'. $args['q'] .'%")';
			
		 }elseif($args['type'] == "user"){
			$sql = 'SELECT * FROM '. $this->_tables['users'] .' WHERE LOWER(user_name) LIKE LOWER("%'. $args['q'] .'%")';
			
		}elseif($args['type'] == "tag"){
			$sql = 'SELECT * FROM '. $this->_tables['tags'] .' WHERE LOWER(tag_name) LIKE LOWER("%'. $args['q'] .'%")';	

		}else{
		 //default search: tag-name
			$sql = 'SELECT * FROM '. $this->_tables['tags'] .' WHERE LOWER(tag_name) LIKE LOWER("%'. $args['q'] .'%")';
		 
		 }
		 
		  

		 
		 $this->_db->setLimit(  $args['limit'],$args['offset']);

		 
		 
		 $res =& $this->_db->query($sql);
		 
		 while (($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC))) {
		 
		 if( $args['type'] == "type" ){
			$results[] = array('id' => $row['type_id'], 'name' => $row['type_name']);
		 }elseif($args['type'] == "object"){
			$results[] = array('id' => $row['object_id'], 'name' => $row['object_name']);
		 }elseif($args['type'] == "user"){
			$results[] = array('id' => $row['user_id'], 'name' => $row['user_name']);
		}elseif($args['type'] == "tag"){
			$results[] = array('id' => $row['tag_id'], 'name' => $row['tag_name']);
		 }else{
		 //default search: tag-name
		 	$results[] = array('id' => $row['tag_id'], 'name' => $row['tag_name']);
		 }

		 } 

		 if($args['output'] == "array"){
				return $results; 
		 }elseif($args['output'] == "json"){
		 
			 if( $args['type'] == "type" ){
					return $results = array("type" => $results);
			 }elseif($args['type'] == "object"){
					return $results = array("object" => $results);
			 }elseif($args['type'] == "user"){
					return $results = array("user" => $results);
			}elseif($args['type'] == "tag"){
					return $results = array("tags" => $results);
			 }else{
			 //default search: tag-name
					return $results = array("tags" => $results);
			 }

		 }else{
				return $results;
			
		 }

		  
    }
	 
	 
	 

    /**
     * Ensure that an array of tags exist, create any that don't, and
     * return ids for all of them.
     *
     * @param array $tags  Array of tag names or ids.
     *
     * @return array  Array of tag ids.
     */
    public function ensureTags($tags)
    {
        return $this->_checkTags($tags);
    }

    /**
     *
     */
    public function getTagIds($tags)
    {
        return $this->_checkTags($tags, false);
    }

    /**
     * Split a string into an array of tag names, respecting tags with spaces
     * and ones that are quoted in some way. For example:
     *   this, "somecompany, llc", "and ""this"" w,o.rks", foo bar
     *
     * Would parse to:
     *   array('this', 'somecompany, llc', 'and "this" w,o.rks', 'foo bar')
     *
     * @param string $text  String to split into 1 or more tags.
     *
     * @return array        Split tag array.
     */
    public function splitTags($text)
    {
        // From http://drupal.org/project/community_tags
        $regexp = '%(?:^|,\ *)("(?>[^"]*)(?>""[^"]* )*"|(?: [^",]*))%x';
        preg_match_all($regexp, $text, $matches);

        $tags = array();
        foreach (array_unique($matches[1]) as $tag) {
            // Remove escape codes
            $tag = trim(str_replace('""', '"', preg_replace('/^"(.*)"$/', '\1', $tag)));
            if (strlen($tag)) {
                $tags[] = $tag;
            }
        }

        return $tags;
    }




 	/**
     * Ensure that an array of users exist in storage. Create any that don't,
     * return user_ids for all.
     *
     * @param array $users  An array of users. Values typed as an integer
     *                        are assumed to already be an user_id.
     *
     * @return array  An array of user_ids.
     */
    public function ensureUsers($users)
    {
        if (!is_array($users)) {
            $users = array($users);
        }

        $userIds = array();
        $userName = array();
		  
		  $table_name = $this->_tables['users'];

        // Anything already typed as an integer is assumed to be a user id.
        foreach ($users as $userIndex => $user) {
            if (is_int($user)) {
                $userIds[$userIndex] = $user;
            } else {
                $userName[$user] = $userIndex;
            }
        }

        // Get the ids for any users that already exist.
       
            if (count($userName)) {
                $userName;
                $sql = 'SELECT user_id, user_name FROM ' .$table_name
                    . ' WHERE user_name IN ("' . implode('","', array_keys($userName)) . '")';


					//$res =& $this->_db->exec($sql);
						  
					$res =& $this->_db->query($sql);
					

					while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
						 // Assuming MDB2's default fetchmode is MDB2_FETCHMODE_ORDERED
						 $userIndex = $userName[$row['user_name']];
                   unset($userName[$row['user_name']]);
                   $userIds[$userIndex] = $row['user_id']; 
					}	  

            }
				
				

			$types = array('text');
			$this->_db->loadModule('Extended');				
				

            // Create any users that didn't already exist
		  foreach ($userName as $user => $userIndex) {
		  
				$fields_values = array(
					'user_name' => "$user"
				);
		
				$affectedRows = $this->_db->extended->autoExecute($table_name, $fields_values,
										MDB2_AUTOQUERY_INSERT, null, $types);
				if ($affectedRows){
				
					$query = 'SELECT user_id FROM '. $table_name .' WHERE user_name = "'.$user.'"';
					
					if ($id = $this->_db->queryOne($query)) {
						$userIds[$userIndex] = $id;
					} 
				}						
		
		  }						

        return $userIds;
    }




    /**
     * Ensure that an array of types exist in storage. Create any that don't,
     * return type_ids for all.
     *
     * @param mixed $types  An array of types or single type value. Values typed
     *                      as an integer are assumed to already be an type_id.
     *
     * @return array  An array of type_ids.
     * @throws Content_Exception
     */
    public function ensureTypes($types)
    {
        if (!is_array($types)) {
            $types = array($types);
        }

        $typeIds = array();
        $typeName = array();
		  
		  $table_name = $this->_tables['types'];

        // Anything already typed as an integer is assumed to be a type id.
        foreach ($types as $typeIndex => $type) {
            if (is_int($type)) {
                $typeIds[$typeIndex] = $type;
            } else {
                $typeName[$type] = $typeIndex;
            }
        }

            // Get the ids for any types that already exist.
            if (count($typeName)) {
				
				$sql = 'SELECT type_id, type_name FROM ' .$table_name
                    . ' WHERE type_name IN ("' . implode('","', array_keys($typeName)) . '")';
	
				$res =& $this->_db->query($sql);		  
		

         	while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                    $typeIndex = $typeName[$row['type_name']];
                    unset($typeName[$row['type_name']]);
                    $typeIds[$typeIndex] = (int)$row['type_id']; 
                }
            }


			$types = array('text');
			$this->_db->loadModule('Extended');	
			


            // Create any types that didn't already exist
            foreach ($typeName as $type => $typeIndex) {
				
				$fields_values = array(
					'type_name' => "$type"
				);
		
				$affectedRows = $this->_db->extended->autoExecute($table_name, $fields_values,
										MDB2_AUTOQUERY_INSERT, null, $types);
				if ($affectedRows){
				
					$query = 'SELECT type_id FROM '. $table_name .' WHERE type_name = "'.$type.'"';
					
					if ($id = $this->_db->queryOne($query)) {
						$typeIds[$typeIndex] = $id;
					} 
				}	
				}

        return $typeIds;
    }

 /**
     * Check for object existence without causing the objects to be created.
     * Helps save queries for things like tags when we already know the object
     * doesn't yet exist in rampage tables.
     *
     * @param mixed string|array $objects  Either an object identifier or an
     *                                     array of them.
     * @param mixed $type                  A type identifier. Either a string
     *                                     type name or the integer type_id.
     *
     * @return mixed  Either a hash of object_id => object_names or false if
     *                the object(s) do not exist.
     * @throws InvalidArgumentException, Content_Exception
     */
    public function objectsExists($objects, $type){
        
		if (!is_array($objects)) {
            $objects = array($objects);
        }

		$table_name = $this->_tables['objects'];

        $type = current($this->ensureTypes($type));

        if (!count($objects)) {
            throw new InvalidArgumentException('No object requested');
        }

		$sql = 'SELECT object_id, object_name FROM '.$table_name. ' WHERE object_name IN ("' . implode('","', array_values($objects)) . '") AND type_id =' .$type;
  

        $results =& $this->_db->query($sql);
		

				
		while ($row = $results->fetchRow(MDB2_FETCHMODE_ASSOC)) {
    				$ids[$row['object_id']] = $row['object_name'];
		}
				
            if ($ids) {
                return $ids;
            }
       

        return false;
    }


 /**
     * Ensure that an array of objects exist in storage. Create any that don't,
     * return object_ids for all. All objects in the $objects array must be
     * of the same content type.
     *
     * @param mixed $objects  An array of objects (or single obejct value).
     *                        Values typed as an integer are assumed to already
     *                        be an object_id.
     * @param mixed $type     Either a string type_name or integer type_id
     *
     * @return array  An array of object_ids.
     */
    public function ensureObjects($objects, $type)
    {
        if (!is_array($objects)) {
            $objects = array($objects);
        }

        $objectIds = array();
        $objectName = array();
		$table_name = $this->_tables['objects'];

        $type = current($this->ensureTypes($type));
		  


        // Anything already typed as an integer is assumed to be a object id.
        foreach ($objects as $objectIndex => $object) {
            if (is_int($object)) {
                $objectIds[$objectIndex] = $object;
            } else {
                $objectName[$object] = $objectIndex;
            }
        }

        // Get the ids for any objects that already exist.

            if (count($objectName)) {
				
				
				
				$sql = 'SELECT object_id, object_name FROM ' .$table_name
                    . ' WHERE object_name IN ("' . implode('","', array_keys($objectName)) . '") AND type_id = ' . $type;
						  
						  
						  	
	
				$res =& $this->_db->query($sql);	
				
				
			  
		
         	while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                    $objectIndex = $objectName[$row['object_name']];
                    unset($objectName[$row['object_name']]);
                    $objectIds[$objectIndex] = $row['object_id'];
                }
            }

				$types = array('text', 'integer');
				$this->_db->loadModule('Extended');	

				foreach ($objectName as $object => $objectIndex) {
				
				$fields_values = array(
					'object_name' => "$object",
					'type_id'     => $type
				);
				
				
				
		
				$affectedRows = $this->_db->extended->autoExecute($table_name, $fields_values,
										MDB2_AUTOQUERY_INSERT, null, $types);
										
								
										
				if ($affectedRows){
				
					$query = 'SELECT object_id FROM '. $table_name .' WHERE object_name = "'.$object.'" AND type_id = '.$type;
					
					if ($id = $this->_db->queryOne($query)) {
						$objectIds[$objectIndex] = $id;
					} 
				}	
				}


        return $objectIds;
    }



 protected function _ensureObject($object)
    {
        if (is_array($object)) {
            $object = current($this->ensureObjects(
                $object['object'], (int)current($this->ensureTypes($object['type']))));
        }

        return (int)$object;
    }


   /**
     * Retrieve tags based on criteria.
     *
     * @param array  $args  Search criteria:
     *   q          Starts-with search on tag_name.
     *   limit      Maximum number of tags to return.
     *   offset     Offset the results. Only useful for paginating, and not recommended.
     *   userId     Only return tags that have been applied by a specific user.
     *   typeId     Only return tags that have been applied by a specific object type.
     *   objectId   Only return tags that have been applied to a specific object.
     *
     * @return array  An array of tags, id => name.
     */
    public function getTags($args)
    {
	 

        if (isset($args['objectId'])) {
            // Don't create the object just because we're trying to load an
            // objects's tags - just check if the object is there. Assume if we
            // have an integer, it's a valid object_id.
            if (is_array($args['objectId'])) {
                $args['objectId'] = $this->objectsExists($args['objectId']['object'], $args['objectId']['type']);
                if ($args['objectId']) {
                    $args['objectId'] = current(array_keys($args['objectId']));
                }
            }
            if (!$args['objectId']) {
                return array();
            }

            $sql = 'SELECT DISTINCT t.tag_id AS tag_id, tag_name 
						  FROM '. $this->_tables['tags'] . ' t 
						  INNER JOIN '. $this->_tables['tagged'] . ' tagged 
						  ON t.tag_id = tagged.tag_id 
						  AND tagged.object_id = '. (int)$args['objectId'];
								
        } elseif (isset($args['userId']) && isset($args['typeId'])) {
            $args['userId'] = current($this->ensureUsers($args['userId']));
            $args['typeId'] = current($this->ensureTypes($args['typeId']));
				
            $sql = 'SELECT DISTINCT t.tag_id AS tag_id, tag_name 
						  FROM ' . $this->_tables['tags'] . ' t 
						  INNER JOIN '. $this->_tables['tagged'] . ' tagged 
						  ON t.tag_id = tagged.tag_id 
						  AND tagged.user_id = '. (int)$args['userId'] . ' 
						  INNER JOIN '. $this->_tables['objects'] . ' objects 
						  ON tagged.object_id = objects.object_id 
						  AND objects.type_id = ' . (int)$args['typeId'];
								
		  } elseif (isset($args['userId'])) {
            $args['userId'] = current($this->ensureUsers($args['userId']));

            $sql = 'SELECT DISTINCT t.tag_id AS tag_id, tag_name 
						  FROM ' . $this->_tables['tagged'] . ' tagged 
						  INNER JOIN ' . $this->_tables['tags'] . ' t 
						  ON tagged.tag_id = t.tag_id 
						  WHERE tagged.user_id = ' . (int)$args['userId'];
								
            $haveWhere = true;

        } elseif (isset($args['typeId'])) {
            $args['typeId'] = current($this->ensureTypes($args['typeId']));
				
            $sql = 'SELECT DISTINCT t.tag_id AS tag_id, tag_name 
						  FROM ' . $this->_tables['tagged'] . ' tagged 
						  INNER JOIN ' . $this->_tables['objects'] . ' objects 
						  ON tagged.object_id = objects.object_id 
						  AND objects.type_id = ' . (int)$args['typeId'] . ' 
						  INNER JOIN ' . $this->_tables['tags'] . ' t 
						  ON tagged.tag_id = t.tag_id';
								
        } elseif (isset($args['tagId'])) {

				$radius = isset($args['limit']) ? (int)$args['limit'] : $this->_defaultRadius;
				$offset = isset($args['offset']) ? (int)$args['offset'] : 0;
				
				$this->_db->setLimit($radius);
            unset($args['limit']);
				
				
				$sql = 'SELECT DISTINCT tagged2.tag_id AS tag_id, tag_name 
				        FROM (
						  		SELECT object_id 
								FROM ' . $this->_tables['tagged'] . ' 
						  		WHERE tag_id = ' .$args['tagId'].' 
								LIMIT '.$offset.', '.$radius.'
						  ) AS tagged1 
						  INNER JOIN ' . $this->_tables['tagged'] . ' tagged2 
						  ON tagged1.object_id = tagged2.object_id 
						  INNER JOIN '. $this->_tables['tags'] . ' t 
						  ON tagged2.tag_id = t.tag_id
						  WHERE tagged2.tag_id != ' .$args['tagId'];
			 
			 
        } else {
            $sql = 'SELECT DISTINCT t.tag_id, tag_name 
						  FROM ' . $this->_tables['tags'] . ' t 
						  JOIN ' . $this->_tables['tagged'] . ' tagged 
						  ON t.tag_id = tagged.tag_id';
        }

        if (isset($args['q']) && strlen($args['q'])) {
            // @TODO tossing a where clause in won't work with all query modes
            $sql .= (!empty($haveWhere) ? ' AND' : ' WHERE') .  ' tag_name LIKE ("%'. $args['q'] .'%")';
        }
		  
		  $radius = isset($args['limit']) ? (int)$args['limit'] : $this->_defaultRadius;

        if (isset($args['limit'])) {
		  $this->_db->setLimit($radius, isset($args['offset']) ? $args['offset'] : 0 );
        }
		  
		  

		  $res =& $this->_db->query($sql);
		 
		 while (($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC))) {
		 
		 	$result[$row[tag_id]] = $row[tag_name]; 

		 }
		 
		 return ($result);
		 
    }
	 
	 
	     /**
     * Get objects matching search criteria.
     *
     * @param array  $args  Search criteria:
     *   limit      Maximum number of objects to return.
     *   offset     Offset the results. Only useful for paginating, and not recommended.
     *   tagId      Return objects related through one or more tags.
     *   notTagId   Don't return objects tagged with one or more tags.
     *   typeId     Only return objects with a specific type.
     *   objectId   Return objects with the same tags as $objectId.
     *   userId     Limit results to objects tagged by a specific user.
     *
     * @return array  An array of object ids.
     */
    public function getObjects($args)
    {
        if (isset($args['objectId'])) {
		  
		  /*if (is_array($args['objectId'])) {
                $args['objectId'] = current($this->_objectManager->ensureObjects(
                    $args['objectId']['object'],
                    $args['objectId']['type']));
            }
			*/

       (int)$objectId = $this->_ensureObject($args['objectId']);
    			$radius = isset($args['limit']) ? (int)$args['limit'] : $this->_defaultRadius;
				$offset = isset($args['offset']) ? (int)$args['offset'] : 0;
		
            $sql = 'SELECT t2.object_id AS object_id, object_name 
						  FROM (
						  		SELECT tag_id
								FROM '. $this->_tables['tagged'] . '  
								WHERE object_id = ' . (int)$objectId . '
								LIMIT '.(int)$offset.', '.(int)$radius.'
						  ) AS t1 
						  INNER JOIN ' . $this->_tables['tagged'] . ' AS t2 
						  ON t1.tag_id = t2.tag_id 
						  INNER JOIN ' . $this->_tables['objects']  . ' AS objects 
						  ON objects.object_id = t2.object_id 
						  WHERE t2.object_id != ' . $objectId . ' 
						  GROUP BY t2.object_id, object_name';

        } elseif (isset($args['tagId'])) {
            $tags = is_array($args['tagId']) ? array_values($args['tagId']) : array($args['tagId']);
            $count = count($tags);
            if (!$count) {
                return array();
            }

            $notTags = isset($args['notTagId']) ? (is_array($args['notTagId']) ? array_values($args['notTagId']) : array($args['notTagId'])) : array();
            $notCount = count($notTags);

            $sql = 'SELECT DISTINCT tagged.object_id AS object_id, object_name 
						  FROM '. $this->_tables['tagged'] . ' AS tagged 
						  INNER JOIN ' . $this->_tables['objects'] . ' objects 
						  ON objects.object_id = tagged.object_id';

            if (!empty($args['typeId'])) {
                $args['typeId'] = $this->ensureTypes($args['typeId']);
            }

            if ($count > 1) {
                for ($i = 1; $i < $count; $i++) {
                    $sql .= ' INNER JOIN ' . $this->_tables['tagged'] . ' tagged' . $i . ' ON tagged.object_id = tagged' . $i . '.object_id';
                }
            }

            if ($notCount) {
                // Left joins for tags we want to exclude.
                for ($j = 0; $j < $notCount; $j++) {
                    $sql .= ' LEFT JOIN ' . $this->_tables['tagged'] . ' not_tagged' . $j . ' 
						  				ON tagged.object_id = not_tagged' . $j . '.object_id 
										AND not_tagged' . $j . '.tag_id = ' . (int)$notTags[$j];
                }
            }

            $sql .= ' WHERE tagged.tag_id = ' . (int)$tags[0];

            if ($count > 1) {
                for ($i = 1; $i < $count; $i++) {
                    $sql .= ' AND tagged' . $i . '.tag_id = ' . (int)$tags[$i];
                }
            }
            if ($notCount) {
                for ($j = 0; $j < $notCount; $j++) {
                    $sql .= ' AND not_tagged' . $j . '.object_id IS NULL';
                }
            }

            if (!empty($args['typeId']) && count($args['typeId'])) {
                $sql .= ' AND objects.type_id IN (' . implode(', ', $args['typeId']) . ')';
            }

            if (array_key_exists('userId', $args)) {
                $args['userId'] = $this->ensureUsers($args['userId']);
                $sql .= ' AND tagged.user_id IN (' . implode(', ', $args['userId']) . ')';
            }
        }
		  
		  if (isset($args['limit'])) {
		  		$this->_db->setLimit($radius, isset($args['offset']) ? $args['offset'] : 0 );
        }
		  

		 
		 $res =& $this->_db->query($sql);
		  
		  
		  if (PEAR::isError($res)) {
			 die(print_r($res));
		 }

		  
		   while (($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC))) {
				$result[$row[object_id]] = $row[object_name]; 

		  }
		  
		  

		 
		 
		  return ($result);

       
    }
	 





 
}
?>
