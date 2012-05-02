<?php
include '../set_env.php';

 PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'jsonHandler');
	
	$table 			= array_key_exists('table', $_GET)   ? (string) trim($_GET['table'])   : false;
	$table 			= array_key_exists('table', $_POST)  ? (string) trim($_POST['table'])  : $table;
	$table   		= preg_match($pattern['alphanumeric'], $table)  ? $table  : '';
	
	$action 			= array_key_exists('action', $_GET)   ? (string) trim($_GET['action'])   : false ;
	$action 			= array_key_exists('action', $_POST)  ? (string) trim($_POST['action'])  : $action;
	$action   		= preg_match($pattern['alphanumeric'], $action)  ? $action  : '';
	
	$offset 			= array_key_exists('offset', $_GET)   ? (int) trim($_GET['offset'])   : 0 ;
	$offset 			= array_key_exists('offset', $_POST)  ? (int) trim($_POST['offset'])  : $offset;
	$offset   		= preg_match($pattern['int'], $offset)  ? $offset  : '';
	
	$limit 			= array_key_exists('limit', $_GET)   ? (int) trim($_GET['limit'])   : 10 ;
	$limit 			= array_key_exists('limit', $_POST)  ? (int) trim($_POST['limit'])  : $limit;
	$limit   		= preg_match($pattern['int'], $limit)  ? $limit  : '';
	
	$id 			= array_key_exists('id', $_GET)   ? (int) trim($_GET['id'])   : false ;
	$id 			= array_key_exists('id', $_POST)  ? (int) trim($_POST['id'])  : $id;
	$id   		= preg_match($pattern['int'], $id)  ? $id  : '';

	$json_data 		= array_key_exists('json_data', $_POST)  ? (string) trim($_POST['json_data'])  : false ;
		
	


	$mdb2_table = MDB2::factory($_allevo_config['dsn_yuitable']);
	$mdb2_table->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$mdb2_table->loadModule('Manager');
	$mdb2_table->loadModule('Extended');
	$mdb2_table->loadModule('Reverse', null, true);
	(int)$action_count = 1;
	
	
	
	// alle Tabellen in der DB auslesen und die zugehörigen Tuppels 
	
	
	
	function yuiListTables(){
		global $mdb2_table;
		global $respond;
		global $action_count;
		
		$tables = $mdb2_table->listTables();
		
		$respond['action'][$action_count] = 'list_table';
		
		if(!empty($tables) ){
			

			foreach($tables as $key => $value_table_name){
				$Fields = $mdb2_table->listTableFields($value_table_name);
				

				
				
				$i=0;
				foreach($Fields as $key => $value){
				
				
				$DataType = $mdb2_table->getTableFieldDefinition($value_table_name, $value);
							
				
				//print_r($Fields);

					if($value == 'id'){
							 $respond['yui_table'][$value_table_name]['columnList'][$i] = array(
							 																				'key' => "$value",
																											'label' => "$value",
																											'formatter' => 'number',
																											'sortable' => true, 
																											'hidden'=> false,
																											'parser'=> 'number'
																											);
																											
							 $respond['yui_table'][$value_table_name]['responseSchema'][$i] = array(
							 																				'key' => "$value",
																											'parser'=> 'number'
																											);																					
																											
					}else{

					switch($DataType[0]['type']){
						 case "integer":
							 $respond['yui_table'][$value_table_name]['columnList'][$i] = array(
							 																				'key' => "$value",
																											'label' => "$value",
																											'formatter' => 'number',
																											'sortable' => true, 
																											'hidden'=> false,
																											'editor' => 'YAHOO.yuitable.ddEditor'
																											);
																											
								$respond['yui_table'][$value_table_name]['responseSchema'][$i] = array(
							 																				'key' => "$value",
																											'parser'=> 'number'
																											);																					
																	
							  break;
						 case "date":
							  $respond['yui_table'][$value_table_name]['columnList'][$i] = array(
																											'key' => "$value",
																											'label' => "$value", 
																											'sortable' => true, 
																											'hidden'=> false, 
																											
																											'formatter' => 'date'
																						 					);
																											
								$respond['yui_table'][$value_table_name]['responseSchema'][$i] = array(
							 																				'key' => "$value",
																											'parser' => "date"
																											);																					
																											
							  break;
						 case "text":
							  $respond['yui_table'][$value_table_name]['columnList'][$i] = array(
							  																				'key' => "$value",
																											'label' => "$value",
																											'sortable' => true, 
																											'hidden'=> false,
																											'editor' => 'textbox'
																											 );
																											 
							  $respond['yui_table'][$value_table_name]['responseSchema'][$i] = array(
							 																				'key' => "$value",
																											'parser' => "string"
																											);																				 
							  break;
					}
					
					
					
					}
					
					//resizeable: true, editor: new YAHOO.widget.DateCellEditor({ asyncSubmitter: submitter }), formatter: YAHOO.widget.DataTable.formatDate
					
					$i++;
				}
			}
		}else{
			$respond['replyText']['list_table'] = 'Keine Tabellen gefunden';

		}
		
	if(!PEAR::isError($tables)){
		   $respond['code']['list_table'] = 200;
			$respond['replyText']['list_table'] = "Tabellen erfolgreich ausgelesen.";
	}else{
		 	$respond['code']['list_table'] = 501;
		 	$respond['replyText']['list_table'] = "Tabelle konnte nicht erstellt werden";
	}
		
		 return $respond;
		 $action_count++; 
	} 




if( $action == "create_table"  and $table ){

	$definition = json_decode($json_data, true);
	
	$logger->log('json_data create table : '. $definition, PEAR_LOG_DEBUG );

	//zu jeder Tabelle ein id Feld hinzufügen! 
	$definition ['id'] = array ( 
			'type' => 'integer',
			'primary' => true,
			'unsigned' => 1,
			'notnull' => 1,
			'default' => 0,
			);

	$response_table = $mdb2_table->createTable($table, $definition);
		
			$respond['action'][$action_count] = $action;
		   $respond['data'][$action]['from_client']= $definition;
		   $respond['data'][$action]['from_server']= $response_table;
		
	if(!PEAR::isError($response_table)){
		   $respond['code'][$action] = 200;
			$respond['replyText'][$action] = "Tabelle ".$table." erfolgreich erstellt.";
	}else{
		 	$respond['code'][$action] = 501;
		 	$respond['replyText'][$action] = "Tabelle konnte nicht erstellt werden";
	}
		
		//$definition_index = array('fields' => array('birth_date' => array(),) );
		//$mdb2_table->createIndex($table, $field, $definition_index)
	 $action_count++; 	
}

	if( $action == "drop_table" and $table ){
	
		$response_table = $mdb2_table->dropTable($table);
		// für den drop der Sequenztablle das Error handling ändern, 
		// falls keine sequenz vorhandne ist, den fehler unterdrücken.	
		// Seqenz wird erst nach dem ersten Datenset erstellt.
		PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
		$response_table_sequenz = $mdb2_table->dropSequence($table);

		if (PEAR::isError($response_table_sequenz)) {
			$respond['data']['dropSequence']['from_server']= $response_table_sequenz;
		} else {
			
		}
			
			$respond['action'][$action_count] = $action;
		   $respond['data'][$action]['from_server']= $response_table;

		if(PEAR::isError($response_table)){
			$respond['code'][$action] = 501;
		 	$respond['replyText'][$action] = "Tabelle konnte nicht gelöscht werden";
		}else{
			$respond['code'][$action] = 200;
			$respond['replyText'][$action] =  "Tabelle ". $table." erfolgreich gelöscht.";
		}
		$action_count++; 
	}



	if( $action == "update_table" and $table ){
	
		//name: New name for the table
		//add: New fields to be added
		//remove: Fields to be dropped
		//rename: Fields to rename
		//change: Fields to modify
		
		
		$definition = json_decode($json_data);
		
		/*
		$definition = array(
					 'add' => array(
									'name2' => array ( 
											'type' => 'text',
											'length' => 255
									),
						)
		 );
		*/ 
		$response_table = $mdb2_table->alterTable($table, $definition, true);
	
			$respond['action'][$action_count] = $action;
			$respond['data'][$action]['from_server']= $response_table;
			$respond['data'][$action]['from_client']= $definition;
		
		if(PEAR::isError($response_table)){
			$respond['code'][$action] = 501;
		}else{
			$respond['code'][$action] = 200;
			$respond['replyText'][$action] =  $table." erfolgreich geändert.";
		}
		$action_count++; 	
	}



	if( $action == "select" and $table ){
	
					// read $limit rows with an offset 
				  $mdb2_table->setLimit($limit, $offset);
		$data = $mdb2_table->queryAll('SELECT * FROM '.$table);
		
			$respond['action'][$action_count] = $action;
			$respond['data'][$action]['from_server']= $data;
			$respond['data'][$action]['from_client'][offset]= $offset;
			$respond['data'][$action]['from_client'][limit] = $limit;
			
			
		
			
			
		if(PEAR::isError($data) ){
			$respond['code'][$action] = 501;
			$respond['replyText'][$action] = "Probleme beim auslesen der Tabelle";
		}elseif(empty($data)){
			$respond['code'][$action] = 204;
			$respond['replyText'][$action] = "In der Tablle " .$table." sind keine Daten vorhanden";
		}else{
			$respond['code'][$action] = 200;
			$respond['replyText'][$action] = "In der Tablle " .$table." erfolgreich Daten ausgelesen";
			$respond['yui_table'][$table]['results'] = $data;
		}
		$action_count++; 
	}


	if( $action == "add_row" and $table ){
	
		$my_new_id = $mdb2_table->nextId($table);
		$content_row = json_decode($json_data, true);
		$content_row['id'] = $my_new_id;
	
		$response_insert = $mdb2_table->extended->autoExecute($table, $content_row, MDB2_AUTOQUERY_INSERT);
		
			$respond['action'][$action_count] = $action;
			$respond['data'][$action]['from_server']= $response_insert;
			$respond['data'][$action]['from_client']= $content_row;
		
		if(PEAR::isError($response_insert) ){
			$respond['code'][$action] = 501;
			$respond['replyText'][$action] = "daten konnten nicht zur Tabelle ".$table." hinzugefügt werden.";
		}else{
			$respond['code'][$action] = 200;
			$respond['replyText'][$action] = "zur Tabelle ".$table." erfolgreich daten hinzugefügt";
		}
		$action_count++; 
	}


	if( $action == "update_cell" and $table ){
	
	
		$content_cell = $json_decode($json_data);
	
		
		$response_update = $mdb2_table->extended->autoExecute($table, $content_row, MDB2_AUTOQUERY_UPDATE, 'id = '.$mdb2->quote($id));
		
			$respond['action'][$action_count] = $action;
			$respond['data'][$action]['from_server']= $response_update;
			$respond['data'][$action]['from_client']= $content_cell;
		
		if(PEAR::isError($response_update) ){
			$respond['code'][$action] = 501;
			$respond['replyText'][$action] = "In der Tabelle ".$table." konnten keine Daten gespeichert werden";
		}else{
			$respond['code'][$action] = 200;
			$respond['replyText'][$action] = "In der Tabelle ".$table." erfolgreich Zelle gändert";
		}
		$action_count++; 
	}


	if( $action == "delete_row" and $table ){
	
		$response_delete_row = $mdb2_table->exec('DELETE FROM '.$table.' WHERE id = '.$mdb2->quote($id));
		
			$respond['action'][$action_count] = $action;
			$respond['data'][$action]['from_server']= $response_delete_row;
			$respond['data'][$action]['from_client']= $table;
			
		if(PEAR::isError($response_delete_row) ){
			$respond['code'][$action] = 501;
			$respond['replyText'][$action] = 'In der Tabelle: '.$table.' konnte den Datensatz: '.$id.' nicht löschen';
		}else{
			$respond['replyText'][$action] = 'In der Tabelle: '.$table.' erfolgreich datensatz '.$id.' gelöscht';
			$respond['code'][$action] = 200;
		}
		$action_count++; 
	}


 yuiListTables();



	if (!$LU->checkRightLevel(USERMANAGEMENT_RIGHT_EDIT, (int)$LU->getProperty('owner_user_id'), (int)$LU->getProperty('owner_group_id') )) {

   }else{
	  
	}
	
	






if(!$LU->isLoggedIn()){
//echo "you are not logged in!!!";
//exit;
}else{


}


	include 'libs/liveuser/index.php';
	


	if(IS_AJAX){

		ob_start();
		//var_dump($_REQUEST);
		print_r($_REQUEST);
		$ajax_request = ob_get_contents();
		ob_end_clean();
		
		$logger->log('ajax requests '. $ajax_request, PEAR_LOG_DEBUG );
		$logger->log('ajax respond '. serialize($respond), PEAR_LOG_DEBUG );
		
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Content-type: application/json; charset=utf-8');

		echo json_encode($respond);
		exit;
	}else{

	print_r($_REQUEST);
		
	echo"<pre>";
		print_r($respond);
	echo"</pre>";
	
	
	}
	
	




?>