<?php
session_start();
include '../set_env.php';


	$src_id 		= array_key_exists('src_id', $_GET)   ? (int) trim($_GET['src_id'])   : 0 ;
	$src_id 		= array_key_exists('src_id', $_POST)  ? (int) trim($_POST['src_id'])  : $src_id;
	$src_id  		= preg_match($pattern['int'], $src_id)  ? $src_id : '';
		
	$target_id 		= array_key_exists('target_id', $_GET)   ? (int) trim($_GET['target_id'])   : 0 ;
	$target_id 		= array_key_exists('target_id', $_POST)  ? (int) trim($_POST['target_id'])  : $target_id;
	$target_id   	= preg_match($pattern['int'], $target_id)  ? $target_id  : '';
	
	$move_type 		= array_key_exists('move_type', $_GET)   ? (string) trim($_GET['move_type'])   : false ;
	$move_type		= array_key_exists('move_type', $_POST)  ? (string) trim($_POST['move_type'])  : $move_type;
	$move_type   	= preg_match($pattern['alphanumeric'], $move_type)  ? $move_type  : '';

	$copy 	= array_key_exists('copy', $_GET)   ? (bool) trim($_GET['copy'])   : false ;
	$copy 	= array_key_exists('copy', $_POST)  ? (bool) trim($_POST['copy'])  : $copy;

	$name 	= array_key_exists('name', $_GET)   ? (string) trim($_GET['name'])   : false ;
	$name 	= array_key_exists('name', $_POST)  ? (string) trim($_POST['name'])  : $name;
	$name  	= preg_match($pattern['nodeName'], $name)  ? $name : 'default';
	
	$uuid			= array_key_exists('uuid', $_GET)   ? (string) trim($_GET['uuid'])   : false ;
	$uuid 			= array_key_exists('uuid', $_POST)  ? (string) trim($_POST['uuid'])  : $uuid;
	//$uuid   		= preg_match($pattern['alphanumeric'], $uuid)  ? $uuid  : '';	
	
	$type 			= array_key_exists('type', $_GET)   ? (string) trim($_GET['type'])   : false ;
	$type 			= array_key_exists('type', $_POST)  ? (string) trim($_POST['type'])  : $type;
	$type   		= preg_match($pattern['alphanumeric'], $type)  ? $type  : '';

	$offset 		= array_key_exists('offset', $_GET)   ? (int) trim($_GET['offset'])   : 0 ;
	$offset 		= array_key_exists('offset', $_POST)  ? (int) trim($_POST['offset'])  : $offset;
	$offset   		= preg_match($pattern['int'], $offset)  ? $offset  : '';
	
	$limit 			= array_key_exists('limit', $_GET)   ? (int) trim($_GET['limit'])   : 10 ;
	$limit 			= array_key_exists('limit', $_POST)  ? (int) trim($_POST['limit'])  : $limit;
	$limit   		= preg_match($pattern['int'], $limit)  ? $limit  : '';
	
	$id 			= array_key_exists('id', $_GET)   ? (int) trim($_GET['id'])   : false;
	$id 			= array_key_exists('id', $_POST)  ? (int) trim($_POST['id'])  : $id;
	$id   			= preg_match($pattern['int'], $id)  ? $id  : false;
	
	$linkidhidden 	= array_key_exists('linkidhidden', $_GET)   ? (int) trim($_GET['linkidhidden'])   : false;
	$linkidhidden	= array_key_exists('linkidhidden', $_POST)  ? (int) trim($_POST['linkidhidden'])  : $linkidhidden;
	$linkidhidden   = preg_match($pattern['int'], $linkidhidden)  ? $linkidhidden  : false;

	$action 		= array_key_exists('action', $_GET)   ? (string) trim($_GET['action'])   : false ;
	$action 		= array_key_exists('action', $_POST)  ? (string) trim($_POST['action'])  : $action;
	$action  		= preg_match($pattern['alphanumeric'], $action)  ? $action  : '';
	

	// tager vars
	$tag_id = array_key_exists('tag-id', $_GET)   ? (int) trim($_GET['tag-id'])  : "" ;
	$tag_id = array_key_exists('tag-id', $_POST)  ? (int) trim($_POST['tag-id']) : $tag_id;
	$tag_id = preg_match($pattern['int'], $tag_id )  ? $tag_id   : "";
	
	$userId = array_key_exists('userId', $_GET)   ? (int) trim($_GET['userId'])  : "" ;
	$userId = array_key_exists('userId', $_POST)  ? (int) trim($_POST['userId']) : $userId;
	$userId = preg_match($pattern['int'], $userId )  ? $userId   : "";

	$typeId = array_key_exists('typeId', $_GET)   ? (int) trim($_GET['typeId'])  : false ;
	$typeId = array_key_exists('typeId', $_POST)  ? (int) trim($_POST['typeId']) : $typeId;
	$typeId = preg_match($pattern['int'], $typeId )  ? $typeId   : "";
	
	$objectId = array_key_exists('objectId', $_GET)   ? (int) trim($_GET['objectId'])  : false;
	$objectId = array_key_exists('objectId', $_POST)  ? (int) trim($_POST['objectId']) : $objectId;
	$objectId = preg_match($pattern['int'], $objectId )  ? $objectId   : false;

	$object_name 	= array_key_exists('object_name', $_GET)   ? (string) trim($_GET['object_name'])   : '';
	$object_name 	= array_key_exists('object_name', $_POST)  ? (string) trim($_POST['object_name'])  : $object_name;
	
	$query = array_key_exists('q', $_GET)   ? (string) trim($_GET['q'])   : "" ;
	$query = array_key_exists('q', $_POST)  ? (string) trim($_POST['q'])  : $query;
   
   $is_root_node = false;
   
   if ($target_id){
      foreach($rootnodes as $key => $value){
         if($value['id'] == $target_id ){
            $is_root_node = true;
         }
      }
   }

   
   
	

	// HTML purifier
	// http://htmlpurifier.org/
	function purifier_init( $plugin_path ) {
	
		//Include and set up the HTML purifier
		include( $plugin_path . 'purifier/HTMLPurifier.standalone.php' );
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8');
		$config->set('AutoFormat.RemoveEmpty', false);
      $config->set('HTML.Trusted', true);
      $config->set('HTML.SafeObject', true);
      $config->set('HTML.SafeIframe', true);
      $config->set('Output.FlashCompat', true);
		$purifier = new HTMLPurifier($config);
		return $purifier;
	
	}
	
	


	$_response = array(
		'result' =>'', 
		'status' => 403,
		'statusmsg'=>'permission denied',
		'request' => $type,
		'target' => $target_id
	);
	

	function getJsonTree($id=1){
	
		global $NestedSets;
		global $LU;

		$data = $NestedSets->getChildren($id, true);

		foreach ($data as $id => $node) {
			
			if (!$LU->checkRightLevel(TREEMANAGER_VIEW, (int)$node['owner_user_id'], (int)$node['owner_group_id'])) {
				// there are no rights to see this resource
			}else{
				if( $node['r']- $node['l'] == 1 ){
					$json[]= array(
						'data' => $node['name'],
						'attr' => array( 'id' => 'node_'.$node['id'], rel => "default" )
					);
				}else{
					$json[]= array(
						'data'  => $node['name'],
						'attr'  => array( 'id' => 'node_'.$node['id']),
						'state' => "closed"
					);
				}
			}	
		}
		return json_encode($json);
	}
	

	function readImgDir($dir, $_allevo_config){
	
		$durchgang = 0;
		if ($handle = opendir($_allevo_config['app_root'].$dir)) { // get conntent from the filesystem
				
			while (false !== ($file = readdir($handle))) {
			
				if ($file != "." && $file != "..") {
				
					$pieces = explode(".", $file);
				

					switch ($pieces[1]) {
						case 'doc':
						case 'pdf':
							$img['doc'][$durchgang]['file']=$file;
							$img['doc'][$durchgang]['dir']=$dir;
							$img['doc'][$durchgang]['path']=$_allevo_config['relativ_upload_path'];
							$img['doc'][$durchgang]['title']=$pieces[0];
							$img['doc'][$durchgang]['basket']="";
							$img['doc'][$durchgang]['extension']=$pieces[1];
						 break;
						case 'jpg':
						case 'jpeg':
						case 'png':
						case 'gif':
							$img['photos'][$durchgang]['img']=$file;
							$img['photos'][$durchgang]['dir']=$dir;
							$img['photos'][$durchgang]['path']=$_allevo_config['relativ_upload_path'].'140px/';
							$img['photos'][$durchgang]['title']=$pieces[0];
							$img['photos'][$durchgang]['extension']=$pieces[1];
						break;
					}				
					
					
					
			
				
					$durchgang = $durchgang+1;
				}
			}
			closedir($handle);
		}
		return $img;
	}
	
	
	
	

	
	
	
	
	
	


if(!$LU->isLoggedIn()){
		$user = array("guest");
}else{
	$user = array( $liveuser['handle']);
	$tager = new Content_Tagger($mdb2);
	
	
		if ($action  == "search_tags" ){
	
		$args['offset'] = 0;
		$args['limit']  = 10;
		$args['q']      = $query;
		$args['type']      = "tag";
		$args['output'] ="json";

		$_response['data'] = $tager->search($args);
		
		$_response['status'] = 200;
		$_response['statusmsg'] =""; 
		$_response['action'] = "search_tags";

	}
	

	

	if (!$LU->checkRight(TREEMANAGER_CREATE)){

	}else{
   
   function add_tags($new_tags, $user, $object_name, $type ){
   
         global $tager;
   
         function trim_value(&$value){
				$value = trim($value);
				$value = filter_var($value , FILTER_SANITIZE_STRING);
			}
				
			$tags = explode(',', $new_tags);
			array_walk($tags, 'trim_value');
		
			$_response['action'] = "add_tags";
					
			// checking for tag ids
			$tag_ids = $tager->ensureTags($tags);
			$_response['data']['tag_ids'] = $tag_ids;
				
			// checking  user ids
			$user_ids = $tager->ensureUsers($user);
			$_response['data']['user_ids'] = $user_ids;
				
			// generating  object ids
			if($object_name && $type){
				$object_ids = $tager->ensureObjects($object_name, $type);
				$_response['data']['object_ids'] = $object_ids;
			}

			if($object_ids && $tag_ids && $user_ids ){
				// tag a objectname 
				function int_value(&$value){
					$value = (int)$value;
				}
				array_walk($tag_ids, 'int_value');
					
				$tager->tag((int)current($user_ids), (int)current($object_ids), $tag_ids );
				$_response['data']['tagged'] = $object_name ;

			}
         
      return $_response;
   }
   

		if ($action  == "add_tags" ){
		
         $_response = add_tags($_POST['tags'], $user, $object_name, $type );

				if( $_response ){
					$_response['status'] = 200;
					$_response['statusmsg'] =" object: $object_ids[0] is tagged "; 
				}else{
					$_response['status'] = 400;
					$_response['statusmsg'] =" Nothing is taged "; 
				}
		}
	}	
		
		
		
	if (!$LU->checkRight(TREEMANAGER_DELETE) and $id == 1 ){
				
		$_response['request'] = 'delete node';
			
	}else{	
		if ($action  == "remove_tag" ){
		
			$tags = array($tag_id);
		
			$_response['action'] = "remove_tag";
			$_response['data'] = $tager->removetag('remove_tag', $userId, $objectId, $tags);
			$_response['statusmsg'] ="Tag-id: $tag_id removed from: "; 
			$_response['status'] = 200;
		}

		if ($action  == "remove_tag_from_object" ){
	
			$tags = array($tag_id);

			// generating  object ids
			if($object_name && $type){
				$object_Id = $tager->ensureObjects($object_name, $type);
				$_response['data']['object_ids'] = $object_Id;
				$objectId = current($object_Id);
			}

			$_response['action'] = "remove_tag_from_object";
			$_response['data'] = $tager->removeTagFromObject($objectId, $tags);
			$_response['statusmsg'] = "Tag-id: $tags removed from: $object_name "; 
			$_response['status'] = 200;

		}

	}



	if (!$LU->checkRight(TREEMANAGER_VIEWCONTENT)){
				
		$_response['statusmsg'] = 'keine Rechte Den Inhalt Anzuschauen';
		$_response['status'] = 403;
							
	}else{
		
		if ($action  == "get_tags" ){

			$args['offset'] = 0;
			$args['limit']  = 10;
			$args['q']      = "";
		
		
			if($object_name && $type){
				$object_Id = $tager->ensureObjects($object_name, $type);
				$_response['data']['object_ids'] = $object_Id;
				$args['objectId'] = current($object_Id);
			}

			if($userId){
				$args['userId'] = $userId;
			}

			if($type){
				$args['typeId'] = $type;
			}
		
			if($tag_id){
				$args['tagId'] = $tag_id;
			}

			$_response['action'] = "get_tags";
			$_response['data']['tags'] = $tager->getTags($args);
			
			
			$_response['data']['args'] = $args;
			$_response['statusmsg'] ="Show me the Tags: "; 
			$_response['status'] = 200;

	}


		if ($action  == "get_objects" ){

			$args['offset'] = $offset;
			$args['limit']  = $limit;
			$args['q']      = "";

			
			if($userId){
				$args['userId'] = $userId;
			}

			if($typeId){
				$args['typeId'] = $typeId;
			}
			
			if($objectId){
				$args['objectId'] = $objectId;
			}
			
			if($tag_id){
				$args['tagId'] = $tag_id;
			}
			
			if($tag_id){
				$args['notTagId'] = $notTagId;
			}

			$_response['action'] = "get_objects";
			$_response['data'] = $tager->getObjects($args);
			$_response['data']['args'] = $args;
			$_response['statusmsg'] ="objects: "; 
			$_response['status'] = 200;
		}
	}	
}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// SERVER SIDE PART

if(isset($_REQUEST["server"])) {
		// Make sure nothing is cached
		header('Cache-Control: no-cache, must-revalidate');
		header("Pragma: no-cache");
		header('Content-type: application/json; charset=utf-8');
		header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")-2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

	switch($type) {
	
		case "list": //*******************************************************************************

			if (!$LU->checkRight(TREEMANAGER_VIEW)){
				$logger->log("no right to view treemanger", PEAR_LOG_INFO );
				$_response['target'] = $id;
			}else{
				echo getJsonTree($id);
				die();
			}
			
			//echo getJsonTree($id);
			//echo '[{"data":"A node","children":[{"data":"Only child","state":"closed"}],"state":"open"},{"data":"Ajax node"}]';
			break;
			
		case "delete": //*******************************************************************************
		
			//$id = (int)$_REQUEST["id"];
		
			if (!$LU->checkRight(TREEMANAGER_DELETE) and $id == 1 ){
			
				$_response['request'] = 'delete node';
		
			}else{
				// single node delete
				if (!$kinder = $NestedSets->getSubBranch($target_id, true, true)){
					if ($NestedSets->deleteNode($target_id)){
						// inhalt löschen für id
						$mdb2->exec('DELETE FROM nested_set_content WHERE nested_set_id = '.$mdb2->quote($target_id));
						$_response['status'] = 200;
						$_response['statusmsg'] = 'delete single node';

					}else{
						$_response['status'] = 400;
						$_response['statusmsg'] = 'could not delete node';

					}
				// multi node delete
				}elseif($kinder){
					if ($NestedSets->deleteNode($target_id)){

						$_response['status'] = 200;
						$_response['statusmsg'] = 'Node '.$target_id.' deleted and Children: ';

						// content delete for all children
						foreach($kinder as $key => $value){
							$mdb2->exec('DELETE FROM nested_set_content WHERE nested_set_id = '.$key);
							$_response['statusmsg'] .= $value['name'].', ';
					   }

					}else{
					
						$_response['status'] = 400;
						$_response['statusmsg'] = 'could not delete node ';

					}
				}
			} // ende permissions if else

			break;
		case "create": //*******************************************************************************


			
			empty($name) ? $name = "new folder" : $name;
			 
			if($uuid){

			}else{ 
				$uuid = new Horde_Support_Uuid();
			}

			$values = array(
    			'name'  => $name,
				'owner_user_id'  => $LU->getProperty('owner_user_id'),
				'owner_group_id' => $LU->getProperty('owner_group_id'),
				'uuid' 			 => $uuid
			);

			if (!$LU->checkRight(TREEMANAGER_CREATE)){

			}else{

				switch ($move_type) {
					case "after":
                     $response_id = $NestedSets->createRightNode($target_id, $values, true);
						break;
					case "before":
                     $response_id = $NestedSets->createLeftNode($target_id, $values, true);
						break;
					case "inside":
						$response_id = $NestedSets->createSubNode($target_id, $values, true);
						break;
					case "root":
						$response_id = $NestedSets->createRootNode($values, $target_root_node_id=false, $first=false );
						break;
				} // end switch
				
				if( $response_id == false){
					$_response['status'] = 400;
					$_response['statusmsg'] = 'could not create node ';
				}else{
					$_response['result'] = $response_id;
					$_response['status'] = 200;
					$_response['statusmsg'] = $name.' successfully created';
				}	
			} // end if else check right permissions
			break;
		case "move": //*******************************************************************************
		
			//$src_id	   = (int) $_REQUEST["src_id"];
			//$target_id	= (int) $_REQUEST["target_id"];
 			//$type	      = (string) ($_REQUEST["move_type"]);

			 
			if (!$LU->checkRight(TREEMANAGER_MOVE)){
				
				$logger->log("error_move_node permissions", PEAR_LOG_INFO );
				
			}else{
         

				switch ($move_type) {
					case 'after':
                  if (!$is_root_node){
						  $response_id = $NestedSets->moveTree( $src_id, $target_id, 'AF', $copy);
						  $server_answer = "after ".$target_id;
                  }
						  break;
					case 'before':
                  if (!$is_root_node){
						 $response_id = $NestedSets->moveTree( $src_id, $target_id, 'BE', $copy);
						 $server_answer = "before ".$target_id;
                  }
						 break;
					case 'inside':
						 $response_id = $NestedSets->moveTree( $src_id, $target_id, 'SUB', $copy);
						 $server_answer = "inside".$target_id;
						 break;
				   case "last":
						$response_id = $NestedSets->moveTree( $src_id, $target_id, 'SUB', $copy);
						 $server_answer = "inside ".$target_id;
					case "first":
						$response_id = $NestedSets->moveTree( $src_id, $target_id, 'SUB', $copy);
						$server_answer = "inside ".$target_id; 
					default:
					   $response_id = $NestedSets->moveTree( $src_id, $target_id, 'SUB', $copy);
						$server_answer = "inside ".$target_id; 
					 	$logger->log("switch else: default type: ".$type, PEAR_LOG_DEBUG );
					break;
						 
				}
				
				if( !is_int($response_id)){
						$_response['status'] = 400;
						$_response['statusmsg'] = 'error move node';
				}else{
				
					if($copy){ 
						$_response['statusmsg'] = 'node '.$src_id.' copyed '.$server_answer;
					}else{
					   $_response['statusmsg'] = 'node '.$src_id.' moved '.$server_answer;
					}
						$_response['result'] = $response_id;
						$_response['status'] = 200;
					
				}
					  $logger->log($server_answer, PEAR_LOG_DEBUG );	
				
			}// end if else check right permissions			
			break;
		case "rename": //*******************************************************************************
        
			//$id   = (int) $_REQUEST["id"];
			//$name = (string) $_REQUEST["data"];

			$values = array(
    						'name'  => $name
							);
							
			$node_before_update = $NestedSets->pickNode($target_id, true, true);
	
			if (!$LU->checkRight(TREEMANAGER_EDIT)){

				$_response['statusmsg'] = 'rename  "'.$node_before_update['name'].'": permission denied ';

			}else{
				if($NestedSets->updateNode( $target_id, $values)){
				
						$_response['status'] = 200;
						$_response['statusmsg'] = 'node <b>'.$node_before_update['name'].'</b> successfully renamed to <b>'.$name.'</b>';
						
				$update = $mdb2->queryOne('SELECT nested_set_id FROM nested_set_content WHERE nested_set_id = '.$mdb2->quote($target_id));
			
				
				if($update){
				
				$mdb2->loadModule('Extended');
				
				    $valide_content_mdb2 = array(
                  'nested_set_id' => $target_id,
						'revision_id'   => $mdb2->nextId(nested_set_content),
                  'title'         => $name,
                  'modified'      => $timestamp = date("YmdHis")
                );
			
					$res = $mdb2->extended->autoExecute('nested_set_content', $valide_content_mdb2, MDB2_AUTOQUERY_UPDATE, 'nested_set_id = '.$update);
						  
					
					
					if (PEAR::isError($res)) {

						$_response['statusmsg'] = $res->getMessage();
						$_response['status'] = 400;
					}

				}		
						
				}else{

					$_response['status'] = 400;
					$_response['statusmsg'] = 'error occurred to rename  "'.$node_before_update['name'].'"';
							
				}
			} // end if else check right permissions		
			break;
			
//********************************************************************************************************//
//********************************************************************************************************//	
		case "loadcontent": //*******************************************************************************
//********************************************************************************************************//
//********************************************************************************************************//
		
			if (!$LU->checkRight(TREEMANAGER_VIEWCONTENT)){
			
					$_response['statusmsg'] = 'keine Rechte Den Inhalt Anzuschauen';
					$_response['status'] = 403;
						
			}else{
			
				$content =& $mdb2->queryRow('SELECT * FROM nested_set_content WHERE nested_set_id = '.$mdb2->quote($id), '', MDB2_FETCHMODE_ASSOC);

	//################################################################//
	//################## gallery and side pictures ###################//*******************************************************************//
	//################################################################//

			$dir = $_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'];
			$durchgang=0;


			$gallery 		=  is_string($content['gallery'])  ? unserialize($content['gallery'])  : array();
			$sidepictures 	=  is_string($content['sidepictures'])  ? unserialize($content['sidepictures'])  : array();

			$img = readImgDir($dir, $_allevo_config);
         $uuid = $content['uuid'];
			
			if(!is_array($gallery)) $gallery = array();
			if(!is_array($sidepictures)) $sidepictures = array();			

					 	  
			foreach($gallery as $key => $value) 
			{
				foreach($img['photos'] as $key2 => $value2) 
				{
					if($value == $value2['img'] ) $img['photos'][$key2]['basket']['album']= true;
				}
			}
			
			
			foreach($sidepictures as $key => $value)
			{
				foreach($img['photos'] as $key2 => $value2) 
				{
					if($value == $value2['img'] ) $img['photos'][$key2]['basket']['leftside']= true;
				}
			}
	
			$content['media']= $img;

	//####################################################//
	//################## query string  ###################//******************************************************************************//
	//####################################################//


				if(is_string($content['query_string'])) $query_string = unserialize($content['query_string']);
				else $query_string = array('id' => $mdb2->quote($id));


				$count_querry_string =  isset($query_string) ? count($query_string) : 0;
				// alle get parameter die zur verfügung sttehen auflisten.
				$get_parameter = array(
					'turbaID'      => array("Rows" =>  7, "Parameter" => 'turbaID',      "Value" => "",                "meta_Value" => 'Text',  " ap" => 'passiv', "meta_ap" => 'YesNo'),
					'id'           => array("Rows" =>  8, "Parameter" => 'id',           "Value" => $mdb2->quote($id), "meta_Value" => 'Number', "ap" => 'passiv', "meta_ap" => 'YesNo'),
					'eventUID'     => array("Rows" =>  9, "Parameter" => 'eventUID',     "Value" => "",                "meta_Value" => 'Text',   "ap" => 'passiv', "meta_ap" => 'YesNo'),
					'start'        => array("Rows" => 10, "Parameter" => 'start',        "Value" => strftime('%Y/%m/%d', time()), "meta_Value" => 'Date', "ap" => 'passiv', "meta_ap" => 'YesNo'),
					'stop'         => array("Rows" => 11, "Parameter" => 'stop',         "Value" => strftime('%Y/%m/%d', time()), "meta_Value" => 'Date', "ap" => 'passiv', "meta_ap" => 'YesNo'),
					'trainings'    => array("Rows" => 12, "Parameter" => 'trainings',    "Value" => "",                "meta_Value" => 'Text',   "ap" => 'passiv', "meta_ap" => 'YesNo'),
					'gallery'      => array("Rows" => 13, "Parameter" => 'gallery',      "Value" => "passiv",          "meta_Value" => 'YesNo',  "ap" => 'passiv', "meta_ap" => 'YesNo')
				);

				// daten aus der Datenbank den jeweiligen get paramtern zuweisen. 
				$order =0;
				
				if(is_array($query_string)){	
					foreach($query_string as $key => $value){
						switch ($key) {
							 case "turbaID":
									$get_parameter['turbaID']=array("Rows"=>$order, "Parameter"=>$key,  "Value"=>$value, "meta_Value" => 'Text', "ap" => 'aktiv', "meta_ap" => 'YesNo');
									break;
							 case "id":
									$get_parameter['id']=array("Rows"=>$order, "Parameter"=>$key,  "Value"=>$mdb2->quote($id), "meta_Value" => 'Number', "ap" => 'aktiv', "meta_ap" => 'YesNo');
									break;
							 case "eventUID":
									$get_parameter['eventUID']=array("Rows"=>$order, "Parameter"=>$key,  "Value"=>$value,"meta_Value" => 'Text', "ap" => 'aktiv', "meta_ap" => 'YesNo');
									break;
							 case "start":
									$get_parameter['start']=array("Rows"=>$order, "Parameter"=>$key, "Value"=> strftime('%Y/%m/%d', $value), "meta_Value" =>'Date', "ap" => 'aktiv', "meta_ap" => 'YesNo');
									break;
							 case "stop":
									$get_parameter['stop']=array("Rows"=>$order, "Parameter"=>$key,  "Value"=> strftime('%Y/%m/%d', $value), "meta_Value"=>'Date', "ap" => 'aktiv', "meta_ap" => 'YesNo');
									break;
							 case "trainings":
									$get_parameter['trainings']=array("Rows"=>$order, "Parameter"=>$key,  "Value"=>$value, "meta_Value" => 'Text', "ap" => 'aktiv', "meta_ap" => 'YesNo');
									break;
							 case "gallery":
									$get_parameter['gallery']=array("Rows" =>$order, "Parameter"=>$key, "meta_Parameter"=>'Text', "Value"=>$value, "meta_Value" => 'Text', "ap" => 'aktiv', "meta_ap" => 'YesNo');
									break;
							 default:
						}
						$order = $order+1;
					}
				}
			
				// query_string sortieren nach der "Rows" Reihenfolge 
				$tmp = Array();
				foreach($get_parameter as &$sortarray) $tmp[] = &$sortarray["Rows"];
				array_multisort($tmp, $get_parameter);
				
				//  loading the content variable queriys_string
				$i=0;
				unset($content['query_string']);
				foreach($get_parameter as $key => $value){
					$content['query_string']['Result'][$i]= $value;
					$i = $i+1;
				}
				
	//#####################################################//
	//################## tags   ###################//******************************************************************************//
	//#####################################################//				
				
			$query = "SELECT object_id FROM rampage_objects
						 WHERE object_name ='".$uuid."'";
						 
			if($objectId = $mdb2->queryOne($query)){
	
				$args['offset'] = $offset;
				$args['limit']  = $limit;
				$args['q']      = "";
				$args['objectId'] = $objectId;

				$content['tags'] = $tager->getTags($args);
		
		}

	//#####################################################//
	//################## dropdown link  ###################//******************************************************************************//
	//#####################################################//
	
	
				$i=0;
				foreach($NestedSets->getBranch(1, true) as $key => $value){
					$link_dropdown[$i][text]= $value['name'];
					$link_dropdown[$i][value]= $value['id'];
					$i = $i+1;
				}
				
				$content['tree'] = $link_dropdown;
				
				
				//print_r($content);
				
				$nested_set_content = $NestedSets->pickNode($id, true);
				$_response['uuid'] = $nested_set_content['uuid'];
				
				$content_google_docs =& $mdb2->queryRow("SELECT * From content WHERE uuid = '".$nested_set_content['uuid']."'", '', MDB2_FETCHMODE_ASSOC);
				
				if($content or $content_google_docs ){
					//echo json_encode($content);
					
					$_response['status'] = 200;
					$_response['id'] = $id;
					$_response['statusmsg'] = 'Inhalt '.$id.' erfolgreich ausgelsen';

					
					$content['nested_set_id'] = $content['nested_set_id'];
					$content['link'] = $content['link'];
					$content['auth_user_id']=$content['auth_user_id'];
					$content['title'] = $content['title'];
					$content['keywords'] = $content['keywords'];
					$content['page_title'] = $content['page_title'];
					$content['active']  = $content['active'];
					$content['timestamp'] = $content['timestamp'];
					$content['modified'] = $content['modified'];
					$content['content'] = $content['content'];
					$content['gallery'] = $content['gallery'];
					$content['sidepictures'] = $content['sidepictures'];	
					$content['content2'] = $content['content2'];
					$content['comments'] = $content['comments'];
					$content['background_pic'] = $content['background_pic'];
					$content['head'] = $content['head'];
					$content['summary'] = $content['summary'];
					$content['revision_id'] =	$content['revision_id'];
					$content['uuid'] = $content['uuid'];

					
					if (strtotime($content['modified']) < strtotime($content_google_docs['modified'])){
					
						$content['content'] = $content_google_docs['content'];
						$content['title']   = $content_google_docs['title'];
						$content['active']  = $content_google_docs['active'];
						$content['timestamp'] = $content_google_docs['timestamp'];
						$content['modified'] = $content_google_docs['modified'];
						$content['uuid'] = $content_google_docs['uuid'];
					
					
					}

					$_response['result'] = $content;
					
				}else{
			
					$_response['statusmsg'] = 'kein Inhalt vorhanden';
					$_response['status'] = 400;

				}
				
				$_response['googledocs'] = $content_google_docs;
				$_response['allevo_content'] = $content;

			}
			break;
			
//*******************************************************************************************************//
//*******************************************************************************************************//
		case "savecontent": //*******************************************************************************
//*******************************************************************************************************//
//*******************************************************************************************************//
		
		if (!$LU->checkRight('TREEMANAGER_EDITCONTENT')){
      
			echo json_encode(array(error => "keine Rechte Den Inhalt zu Speichern"));	
         
		}else{
		
					$mdb2->loadModule('Extended');
					
					$purifier = purifier_init( $plugin_path );
					

					$revision_id             = $mdb2->nextId('nested_set_content');
				   $media                   = json_decode(stripslashes($_REQUEST['media']));
					$query_string            = json_decode(stripslashes($_REQUEST['query_string']));
					$editor_content_stripped = $purifier->purify($_REQUEST['editor']); //stripslashes($_REQUEST['editor']);
					$_nested_set_node_name   = $NestedSets->pickNode($id, true);
					$name                    = $_nested_set_node_name['name'];
					
					if($uuid){

					}elseif($_nested_set_node_name['uuid']){
						$uuid = $_nested_set_node_name['uuid'];
					}else{ 
					 	$uuid = new Horde_Support_Uuid();
					}
               
               
               add_tags($_REQUEST['tags'], $user, $uuid, "content" );
               

					//if ($values['coments'] == 1){}else ($values['coments'] = 0);
					// logging some parameter //

					if(is_array($query_string)){
					
							foreach($query_string as $k1 => $object )
							{
								if (is_object($object))
								 foreach($object as $k2 => $v ) {
										$query_string_array[$k2] = $v;
								 }
 
							}
					}
					
					if( count($media)!= 0 and is_object($media)){
							
							$dir = $_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'];
							$img = readImgDir($dir, $_allevo_config);
							$gallery_count = 0;
							$sidepictures_count = 0;
							
							foreach($media as $key => $value)
							{
					
									if($value->album)
									{
										foreach($img['photos'] as $key2 => $values2)
										{
											if($key == $values2['title'])
											{
												$gallery[$gallery_count] = $values2['img'];
												$gallery_count = $gallery_count +1;
											}
										}
									}
									
									if($value->siedpictures)
									{
										foreach($img['photos'] as $key2 => $values2)
										{
											if($key ==$values2['title'])
											{
												$sidepictures[$sidepictures_count] = $values2['img'];
												$sidepictures_count =$sidepictures_count + 1;
											}
										}
									}
									
									if($value->deleteimg)
									{
										foreach($img['photos'] as $key2 => $values2)
										{
												if($key ==$values2['title'])
												{
													@unlink($_allevo_config['app_root'].$_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'].$values2['img']);
													@unlink($_allevo_config['app_root'].$_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'].'140px/'.$values2['img']);
													@unlink($_allevo_config['app_root'].$_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'].'280px/'.$values2['img']);
													@unlink($_allevo_config['app_root'].$_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'].'446px/'.$values2['img']);
													@unlink($_allevo_config['app_root'].$_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'].'700px/'.$values2['img']);
												}
										}
									} 
					
							}// foreach ende media 
							
						$gallery_serialized 	    = is_array($gallery)   ? serialize($gallery)   : '' ;
						$sidepictures_serialized 	= is_array($sidepictures)   ? serialize($sidepictures) : '' ;

					}
					

					ob_start();
						var_dump($_REQUEST['query_string']);
						//var_dump($media);
						var_dump($query_string_array);
						var_dump($gallery_serialized);
						var_dump($sidepictures_serialized);
						$test = ob_get_contents();
					ob_end_clean();
	
					$logger->log("json :". $test,  PEAR_LOG_INFO );
				
				
				
			if ( $id == false ){
			
				// there is no id !!! so screw it; 
				$_response['result'] = $id;
				$_response['status'] = 400;
				$_response['statusmsg'] = 'there is no id';
				
			}else{

				$valide_content_mdb2=array(
                  'nested_set_id' => $id,
                  'auth_user_id'  => $LU->getProperty('auth_user_id'),
                  'comments'      => 0,
                  'title'         => $name,
                  'keywords'      => $_REQUEST['keywords'],
                  'head'          => $_REQUEST['head'],
                  'summary'       => $_REQUEST['summary'],
                  'background_pic'=> $_REQUEST['headpicture'],
                  'page_title'    => $_REQUEST['page_title'],
                  'modified'      => $timestamp = date("YmdHis"),
                  'revision_id'   => $revision_id,
                  'uuid'          => $uuid
					);

				$valide_content_nestedsets_mdb2=array(
                  'rewrite'  => $_REQUEST['rewrite'],
                  'uuid'     => $uuid
					);
				
					// serialize query string 
				if (is_array($query_string_array)){
					$valide_content_mdb2['query_string'] = serialize($query_string_array);
					$valide_content_nestedsets_mdb2['query_string'] = serialize($query_string_array);
				} 

				if( (int)$_REQUEST['ContentActive'] == 1){
					$valide_content_nestedsets_mdb2['active'] = 1; 
					$valide_content_mdb2['active'] = 1;
				}elseif(!$_REQUEST['ContentActive']){
					$valide_content_nestedsets_mdb2['active'] = 0;
					$valide_content_mdb2['active'] = 0;
				}	

				if( (int)$linkidhidden!=0){
					$valide_content_mdb2['link'] = $linkidhidden;
					$valide_content_nestedsets_mdb2['link'] = $linkidhidden;
				}
					
				if(is_string($gallery_serialized))       $valide_content_mdb2['gallery']      = $gallery_serialized;
				if(is_string($sidepictures_serialized))  $valide_content_mdb2['sidepictures'] = $sidepictures_serialized;	
					
				if(is_string($editor_content_stripped)){
					$valide_content_mdb2['content']= $editor_content_stripped;
				}
					
				if(is_string(stripslashes($_REQUEST['content2']))){
					//$valide_content_mdb2['content2']= stripslashes($_REQUEST['content2']);
					$valide_content_mdb2['content2']= $purifier->purify($_REQUEST['content2']);
				}

				$update = $mdb2->queryOne('SELECT nested_set_id FROM nested_set_content WHERE nested_set_id = '.$mdb2->quote($id));

				if( empty($update)){
					$return_nested_set_content_create = $mdb2->extended->autoExecute('nested_set_content', $valide_content_mdb2, MDB2_AUTOQUERY_INSERT);
					$return_nested_set__create = $mdb2->extended->autoExecute('nested_set', $valide_content_nestedsets_mdb2, MDB2_AUTOQUERY_UPDATE, 'id = '.$mdb2->quote($id));
				}else{
					$return_nested_set_update = $mdb2->extended->autoExecute('nested_set', $valide_content_nestedsets_mdb2, MDB2_AUTOQUERY_UPDATE, 'id = '.$update);
					$return_nested_set_content_update = $mdb2->extended->autoExecute('nested_set_content', $valide_content_mdb2, MDB2_AUTOQUERY_UPDATE, 'nested_set_id = '.$update);
				}
				
				$mdb2->extended->autoExecute('nested_set_content_revision', $valide_content_mdb2, MDB2_AUTOQUERY_INSERT);

				$logger->log("savecontent:".$valide_content_mdb2,  PEAR_LOG_INFO );

				if (PEAR::isError($mdb2)) {
					die($mdb2->getMessage());
				}

				$_response['result'] = $id;
				$_response['status'] = 200;
				$_response['content'] = $valide_content_mdb2;
				$_response['update'] = $update;
				$_response['statusmsg'] = 'erfoglreich gespeichert';

			}// the id check 		
		}// checkRight TREEMANAGER_EDITCONTEN		
		break;
	} // ende switch
}





if( is_array($_response) and function_exists('json_encode')){

	echo json_encode($_response);

}else{


	die();


}




?>