<?php
session_start();
include '../set_env.php';


 PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'jsonHandler');

	$action  = array_key_exists('action', $_GET)   ? (string) trim($_GET['action'])   : false ;
	$action  = array_key_exists('action', $_POST)  ? (string) trim($_POST['action'])  : $action;
	$action  = preg_match($pattern['alphanumeric'], $action)  ? $action  : '';
	
	$resource_type = array_key_exists('resource_type', $_GET)   ? (string) trim($_GET['resource_type'])   : 'calendar' ;
	$resource_type = array_key_exists('resource_type', $_POST)  ? (string) trim($_POST['resource_type'])  : $resource_type;
	$resource_type  = preg_match($pattern['alphanumeric'], $resource_type)  ? $resource_type  : '';
	
	$q 	= array_key_exists('q', $_GET)   ? (string) trim($_GET['q'])   : false ;
	$q 	= array_key_exists('q', $_POST)  ? (string) trim($_POST['q'])  : $q;
	$q  = filter_var($q , FILTER_SANITIZE_STRING); 
	
	$limit  = array_key_exists('limit',  $_REQUEST) ? (int) trim($_REQUEST['limit'])  : 10;
	$limit  = preg_match($pattern['int'], $limit)  ? $limit  : '';

	$offset  = array_key_exists('offset',  $_REQUEST) ? (int) trim($_REQUEST['offset'])  : 0;
	$offset  = preg_match($pattern['int'], $offset)  ? $offset  : '';
	
	$calendar_id  = array_key_exists('calendar_id', $_GET) ? (string) trim($_GET['calendar_id']) : false;
	

	
	function purifier_init( $plugin_path ) {
	
		//Include and set up the HTML purifier
		include( $plugin_path . 'purifier/HTMLPurifier.standalone.php' );
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8');
		$config->set('AutoFormat.RemoveEmpty', true);
		$config->set('HTML', 'Allowed', ' h1, h2, h3, h4, h5, h6');
			
		$purifier = new HTMLPurifier($config);
		return $purifier;
	
	}


	$tager = new Content_Tagger($mdb2);

	if(!$LU->isLoggedIn()){
		$user = array("guest");
	}else{
		$user = array( $liveuser['handle']);
	}


	$_response = array(
		'status' => 403,
		'statusmsg'=>'permission denied',
		'action' => $action
	);  

	$perm_type = $LU->getProperty('perm_type');	
	

	
	if( $action == "getTags"){
	
		$args['q']      	= $q;
		$args['type']   	= "tag"; 	//object, tag, type, user	
		$args['offset'] 	= $offset;
		$args['limit']  	= $limit;
		$args['output'] 	= 'array';
		
		$tags = $tager->search($args);

		$_response['data']['tags'] = $tags;
		$_response['status'] = 200;
		
		if (is_array ($tags) ){
			$_response['statusmsg'] = count($tags).' tags found';
		}else{
			$_response['statusmsg'] = 'keine tags gefunden für: '.$q;
		}
	}
	
	
	
	if( $action == "get_content"){

		$purifier = purifier_init( $plugin_path );
	
		$addSQL = array( 'where' => 'active = 1');
		
		if($Siblings = $NestedSets->getSiblings($q, true, true, $addSQL)){
			$count = 0;
			foreach($Siblings as $key => $value ){
				$end = $offset + $limit;
					
				if( $count >= $offset AND  $count < $end ){				
					$content[$key] =& $mdb2->queryRow("SELECT DATE_FORMAT(timestamp,'%Y, %c, %e') AS date , content, title, nested_set_id FROM nested_set_content WHERE nested_set_id =".$value['id']);
					$content[$key] = unserialize_content($content[$key]);
					
					foreach($content[$key] as $key2 => $value2){
					
						if ($key2 === content){
							$value2 = substr( $value2 , 0 , 300 );
							$content[$key][$key2]= $purifier->purify($value2);						
						}
					}	
				}					
				$count = $count+1;								 
			}			
		}
	
		$_response['status'] = 200;
		$_response['q'] = $q;
		$_response['count'] = $count-1;	
		$_response['offset'] = $offset;
		$_response['end'] = $end;
		//$_response['Siblings'] = $Siblings;
	
		$_response['statusmsg'] ='get content';
		$_response['content'] = $content;
	
	}
	

	
	if( $action == "searchTags"){
	
		$tags = explode(',',$q);
		
		//$filter=array('future' => false );

		$rpc_parameters_tags = array(
			'names' => $tags, 
			'max' => 100,
			'from' => 0,
			'resource_type' => $resource_type,
			'user' => '',
			'raw' => false,
			'remote' => true,
			'filter' => $filter
			);
			
		try {

			$http_client = new Horde_Http_Client($rpc_options);
		
			$tags = Horde_Rpc::request(
				'jsonrpc',
				$GLOBALS['rpc_endpoint'],
				'calendar.searchTags',
				$GLOBALS['http_client'],
				$rpc_parameters_tags
			);
	
		}catch (Exception $e) {	

			echo 'Caught exception: ',  $e->getMessage(), "\n";

		}				
	

		foreach( $tags->result as $k => $event){

			$event->durMin = secondsToTime(strtotime($event->et) - strtotime($event->st));
			$event->tg = $event->tg;
			$event->sd = $event->sd;
			$event->l = $event->l;
			$event->t = $event->t;
			
			$e[] = $event;
		}

		$_response['status'] = 200;	
		$_response['statusmsg'] ='tags for '.$resource_type;
		$_response['calendar']['events'] = $e;

	}
	

	
	if( $action == "calendar"){
	
	$http_client = new Horde_Http_Client($rpc_options);
	
		if ($calendar_id == false){

		try {

			$results_list_calendars_object = Horde_Rpc::request(
				'jsonrpc',
				$GLOBALS['rpc_endpoint'],
				'calendar.listCalendars',
				$GLOBALS['http_client'],
				$GLOBALS['rpc_parameter']
			);
				
				
			if (!is_a($results_list_calendars_object, 'PEAR_Error')) {
				$calendar_ids = $results_list_calendars_object->result;
			}	
	
		}catch (Exception $e) {	

			//echo 'Caught exception: ',  $e->getMessage(), "\n";

		}
		
		}else{
		
			$calendar_ids = array($calendar_id);
		
		}
		

		// Datum auslesen pro zusammenhängende Gruppe ein Request !!! 
		$dates = explode(',',$q);

		// without a calendar date
		$startStopDate  = explode('/', $q);
		$startDate = explode('.', $startStopDate[0]);
		$stopDate  = explode('.', $startStopDate[1]);
					
		$i = 0;
		$gruppe = true;
					
					
				
		foreach($dates as $key => $string_date){
					
			$date      = explode('-', $dates[$key]);
			$next_date = explode('-', $dates[$key+1]);
			
			//solange gruppe = flase gesetzt ist, wird das Startdatum nicht geändert.				
			if ($gruppe == true){
				$StartYear   = $date[0];
				$StartMonth  = $date[1];
				$StartDay    = $date[2];

				$gruppe = false;
			}

			// existiert ein nächstes datum? 
			if($next_date[2]){
				$EndYear  = $next_date[0];
				$EndMonth = $next_date[1];
				$EndDay   = $next_date[2];
							
				$_check_stop  = mktime(0, 0, 0, $EndMonth, $EndDay, $EndYear);		
			}else{	
				$EndYear  = $date[0];
				$EndMonth = $date[1];
				$EndDay   = $date[2];
							
				$_time_stop  = mktime(0, 0, 0, $EndMonth, $EndDay, $EndYear) + 86399;
			}

			$_time_start  = mktime(0, 0, 0, $StartMonth, $StartDay, $StartYear);
			$_check_start = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
							
							
							
			// should we start a request? 
			if(($_check_stop - $_check_start)== 86400 ){
						
			// das sind zusammenhängende Tage. 
			// dump the stop day one up.
						
			}else{
						

						
				if(!$_time_stop){
		
					$EndYear   = $date[0];
					$EndMonth  = $date[1];
					$EndDay    = $date[2];
									
					$_time_stop  = mktime(0, 0, 0, $EndMonth, $EndDay, $EndYear) + 86400;
		
				}

				if ( $_time_stop - $_time_start >= 86399){
								
				}else{
					// one day Szenario 
					$EndYear   = date("Y");
					$EndMonth  = date("m");
					$EndDay    = date("d");
									
					$_time_stop  = mktime(0, 0, 0, $EndMonth, $EndDay, $EndYear) + 86400;
					$_time_start = mktime(0, 0, 0, $EndMonth, $EndDay, $EndYear);
				}
						
				
				if($stopDate[2]){
								
					$EndYear   = $stopDate[0];
					$EndMonth  = $stopDate[1];
					$EndDay    = $stopDate[2];
									
					$_time_stop  = mktime(0, 0, 0, $EndMonth, $EndDay, $EndYear);
					$_time_start = $_time_stop - (60*60*24*7); // 1 week back.
		
				}
				
				if($startDate[2]){
								
					$StartYear   = $startDate[0];
					$StartMonth  = $startDate[1];
					$StartDay    = $startDate[2];
									
					$_time_start  = mktime(0, 0, 0, $StartMonth, $StartDay, $StartYear);
		
				}
							
				$_response['data']['check'][$key]['start'] = date("Y-d-m", $_time_start);
				$_response['data']['check'][$key]['stop']  = date("Y-d-m", $_time_stop);
							
			
				// make the request
				$rpc_parameters_events = array(
					'startstamp' => $_time_start, 
					'endstamp' => $_time_stop,
					'calendars' => $calendar_ids,
					'showRecurrence' => true,
					'alarmsOnly' => false,
					'showRemote' => true,
					'hideExceptions' => false,
					'coverDates' => true,
					'fetchTags' => true,
					'remote' => true,
				);

				try {

				$events[$i] = Horde_Rpc::request(
					'jsonrpc',
					$GLOBALS['rpc_endpoint'],
					'calendar.listEvents',
					$GLOBALS['http_client'],
					$GLOBALS['rpc_parameters_events']
				);
								
				$gruppe = true;
	
				}catch (Exception $e) {	

				//echo 'Caught exception: ',  $e->getMessage(), "\n";

				}
			}
			
			

			/*
				$_response['data']['check'][$key][start] = $_check_start;
				$_response['data']['check'][$key][stop] = $_check_stop;
				$_response['data']['check'][$key][differenz] = $_check_stop - $_check_start;
				$_response['data']['time'][$key][start] = $_time_start;
				$_response['data']['time'][$key][stop] = $_time_stop;
							
				$_response['data']['time'][$key][differenz] = $_time_stop-$_time_start;
							
				$_response['data']['gruppe'][$key] = $gruppe;
				$_response['data']['q'][$key][year]  = $date[0];
				$_response['data']['q'][$key][month] = $date[1];
				$_response['data']['q'][$key][day]   = $date[2];
			*/
				

			$i = $i +1;		
			
		}

		
		// Requests zusammenführen. 	
		foreach( $events as $k => $v){
			//$_response['rawdata1'] = $v;	
			foreach( $v->result as $key => $value){
				foreach( $value as $k => $event){
					$event->durMin = secondsToTime(strtotime($event->json->et) - strtotime($event->json->st));
					$event->tg = $event->json->tg;
					$event->sd = $event->json->sd;
					$event->l = $event->json->l;
					$event->t = trim($event->json->t);
								
					$e[] = $event;
				}
			}	
		}
					
		// Dump the results
					
		$_response['status'] = 200;
		$_response['statusmsg'] ='calendar events';
		$_response['calendar']['events'] = $e;

	}
		
	
	
	if( $action == "publicsearch"){
	
			$_response['data']['query'] = $q;
			
			$query = "SELECT * FROM nested_set_content
						 WHERE MATCH (title, content, content2, page_title)
						 AGAINST ('".$q."' IN NATURAL LANGUAGE MODE)";
						 
						 
			$query2 = "SELECT * FROM nested_set_content
						  WHERE  content LIKE '%".$q."%'
						  OR title LIKE '%".$q."%'
						  OR content2 LIKE '%".$q."%'
						  OR page_title LIKE '%".$q."%'
						  ";

			$res_nested_sets_content =& $mdb2->query($query2);
										 
			$i=0;
										 
			while (($row = $res_nested_sets_content->fetchRow(MDB2_FETCHMODE_ASSOC))) {

	 		$url = unserialize($row['query_string']);
			

			$_response['data']['results'][$i]['name'] = $row['title'];
			$_response['data']['results'][$i]['displayName'] =$row['title'];
			$_response['data']['results'][$i]['description'] =$row['content'];
			$_response['data']['results'][$i]['author'] =$row['title'];
			
			// get tags 
		   $query = "SELECT object_id FROM rampage_objects
						 WHERE object_name ='".$row['uuid']."'";

			if($objectId = $mdb2->queryOne($query)){
			
				$args['offset'] = $offset;
				$args['limit']  = $limit;
				$args['q']      = "";
				$args['objectId'] = $objectId;

				$_response['data']['results'][$i]['tags'] = $tager->getTags($args);

			};

			$_response['data']['results'][$i]['resultType'] ='webpage';
			$_response['data']['results'][$i]['url'] = '/index.php?id='.$url['id'];
	 
	  		$i= $i+1;
	 
  			}
				
			$res_nested_sets_content->free();		
			
			// google docs import search --> table  content //
			
			/*
			$query = "SELECT * FROM content
						 WHERE MATCH (title, content, summary, page_title)
						 AGAINST ('".$q."' IN NATURAL LANGUAGE MODE)";
			
			$res_content =& $mdb2->query($query);
										 
			
										 
			while (($row = $res_content ->fetchRow(MDB2_FETCHMODE_ASSOC))) {

	 		$url = unserialize($row['query_string']);
	 		

			$_response['data']['results'][$i]['name'] = $row['title'];
			$_response['data']['results'][$i]['displayName'] =$row['title'];
			$_response['data']['results'][$i]['description'] =$row['content'];
			$_response['data']['results'][$i]['author'] =$row['title'];
			
						
			$query = "SELECT object_id FROM rampage_objects
						 WHERE object_name ='".$row['uuid']."'";
						 
			if($objectId = $mdb2->queryOne($query)){
	
				$args['offset'] = $offset;
				$args['limit']  = $limit;
				$args['q']      = "";
				$args['objectId'] = $objectId;
				
				$_response['data']['results'][$i]['tags'] = $tager->getTags($args);
		
		}

			$_response['data']['results'][$i]['resultType'] ='google docs';
			$_response['data']['results'][$i]['url'] = '/index.php?id='.$url['id'];
	 
	  		$i= $i+1;
	 
  			}
			
							 
			$mdb2->disconnect();
			
			*/

		// Turba search 
		
		$rpc_parameters_turba_sources = array();						
		$turba_fileds = array('name');
		
		
		$_allevo_config['horde']['module']['turba']['source']	;

		$rpc_parameters_turba_search = array(
					'query' => array($q),
					'sources' => $_allevo_config['horde']['module']['turba']['sources'], 
					'fields' => $turba_fileds,
					'matchBegin' =>  false,
                    'forceSource' => false, 
					'returnFields' => array()
				);
				
		try {

			$http_client = new Horde_Http_Client($rpc_options);
		
			$turba_search = Horde_Rpc::request(
				'jsonrpc',
				$GLOBALS['rpc_endpoint'],
				'contacts.search',
				$GLOBALS['http_client'],
				$rpc_parameters_turba_search
				);
	
		}catch (Exception $e) {	

			echo 'Caught exception: ',  $e->getMessage(), "\n";

		}	
	


			$_response['mitglieder'] = $turba_search->result;
			
			
			if( $results = $turba_search->result){

				foreach($results->$q as $key => $value ){
					$_response['data']['results'][$i]['url'] = '/index.php?turbaID='.$value->id.'&amp;source='.$value->source;
					$_response['data']['results'][$i]['name'] = $value->name;
					$_response['data']['results'][$i]['displayName'] = $value->name;
					$_response['data']['results'][$i]['description'] = $value->notes;
					$_response['data']['results'][$i]['resultType']  ='mitglied';
					$i= $i+1;
				}

			}
			
			//$_response['turba sources'] = array_keys(get_object_vars($turba_surces->result));
			
			$_response['statusmsg'] ='success';
			$_response['status'] = 200;
		}

	
	
	if (!$LU->isLoggedIn()){

	
	}else{
		
		if( $action == "search_liveuser_user"){

			if ( !($perm_type >= 4) ){
				
				// nur owner dürfen die eigenen Angaben bearbeiten. 
				$params_users = array(
					'container' => 'auth',
					'filters'   =>  array(
						'owner_user_id' => $LU->getProperty('owner_user_id')
						)
					);
			}else{
				
				// nur super und master admins dürfen alle user sehen. perm_type >= 4			
				$params_users = array(
					'container' => 'auth',
					'limit'     => $limit,
					'filters'   => array( 
						'handle' => array(
							'value' => $q.'%', 
							'op' => ' like '
						)
					)
				);
			
			}

		 	$users  = $lu_admin->getUsers($params_users);
		 
		 	if (is_array($users)){
				$_response['statusmsg'] = count($users).' Users found';
					
				foreach($users as $key => $value){
					
						$users[$key] = array(
							'handle'       =>  $value['handle'],
							'nachname'     =>  $value['nachname'],
							'vorname'      =>  $value['vorname'],
							'perm_user_id' =>  $value['perm_user_id'],
							'is_active'    =>  $value['is_active'],
							'perm_type'    =>  $value['perm_type']

						);
					
					if($value['is_active'] == true ){
							$users[$key]['is_active_icon'] = "icon.active.gif";
					}else{
							$users[$key]['is_active_icon'] = "icon.inactive.gif";
					}
					
					
					switch($value['perm_type']){
						case '0':				
							$users[$key]['perm_type_text'] = "Anon";
							break;
						case '1':				
							$users[$key]['perm_type_text'] = "User";
							break;
						case '2':				
							$users[$key]['perm_type_text'] = "Admin";
							break;
						case '3':				
							$users[$key]['perm_type_text'] = "Area Admin";
							break;
						case '4':				
							$users[$key]['perm_type_text'] = "Super Admin";
							break;
						case '5':
							$users[$key]['perm_type_text'] = "Master Admin";
							break;
						default:	
							break;
					}		
					
				}

			}else{
				$_response['statusmsg'] ='0 Users found';
			}
			
		 	$_response['result'] = $users;
			$_response['status'] = 200;
			$_response['search'] = $params_users;
		}
}



	if(IS_AJAX){
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($_response);
		exit;
		
	}else{
	//print_r($_REQUEST);
	//header('Content-type: application/json; charset=utf-8');
	//echo json_encode($_response);
    //print_r($_response);
}
?>