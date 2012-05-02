<?php
session_start();
include '../set_env.php';

	$action = array_key_exists('action', $_GET)   ? (string) trim($_GET['action'])   : "" ;
	$action = array_key_exists('action', $_POST)  ? (string) trim($_POST['action'])  : $action;
	$action = preg_match($pattern['text'], $action )  ? $action   : "";
	
	$limit  	= array_key_exists('limit',  $_REQUEST) ? (int) trim($_REQUEST['limit'])  :10;
	$limit   	= preg_match($pattern['int'], $limit)  ? $limit  : '';
	
	$offset 	= array_key_exists('offset', $_REQUEST) ? (int) trim($_REQUEST['offset']) :0;
	$offset   	= preg_match($pattern['int'], $offset)  ? $offset  : '';
	
	$obj   = json_decode($_POST['jsonData']);
	$array = json_decode($_POST['jsonData'], true);
	
	$perm_type 	= $LU->getProperty('perm_type');
   	$status 	= $LU->getStatus();
 	$handle 	= $LU->getProperty('handle');

	//$respond['obj'] = $obj;
	
	$metadaten = $obj->meta;
	$isInGroups = $obj->groups;
	$respond['meta'] = $metadaten;

if (!$LU->checkRight(BENUTZERVERWALTUNG_EDIT)){
	
	$respond['action'] = $action;		
	$respond['data'] = "";
	$respond['statusmsg'] ="you are not logged in"; 
	$respond['status'] = 403;

}else{
	
	if($metadaten){
		
		foreach($metadaten as  $value){
		
			$action = $value->type;
			$perm_user_id = false;
			

			if( trim($value->idType) == 'area_id' ){
				$area_id = (int)$value->id;
			}

			if($action == 'are-rights' and $area_id){
			
				$new_area = $obj->new;

				if( is_string($new_area[0]) ){

					$has_implied = 0;
					$right_name = $new_area[0];
					
					$data = array(
						'area_id' => $area_id,
						'right_define_name' => $right_name,
						'has_implied' => $has_implied
						);
						
					$rightId = $lu_admin->perm->addRight($data);

				}
				
				$remove_area_right = $obj->$area_id;
				
				foreach($remove_area_right as $right_id){
					$filter = array('right_id' => $right_id);
					$removeRight = $lu_admin->perm->removeRight($filter);
				}
			}
			
			
			if( trim($value->idType) == 'perm_user_id' ){
				$perm_user_id = (int)$value->id;
			}

			if($action == 'user-right' and $perm_user_id){

				
				//remove user rights
				$remove_right_ids = $obj->rr;

				$respond['remove_right_ids'] = $remove_right_ids;
				
				 foreach($remove_right_ids as $right_id){
					$filter = array(
						'perm_user_id' => $perm_user_id,
						'right_id' => $right_id
					);
					$revoked = $lu_admin->perm->revokeUserRight($filter);

				} 
				
				$right_ids = $obj->$perm_user_id;
				// grant user rights
				foreach($right_ids as $right_id){
			 
					$data = array(
						'perm_user_id' => $perm_user_id,
						'right_id' => $right_id,
						'right_level' => 3,
					);
					
					$granted = $lu_admin->perm->grantUserRight($data);
				}
				

				// user is in group --> remove him.
					$group_params = array( 
						'filters' => array('perm_user_id' => $perm_user_id)
					);
			
					$gruppen_im_system = $lu_admin->perm->getGroups($group_params);
					
					if($gruppen_im_system){
					
						foreach($gruppen_im_system as $gruppe){
							$filter = array(
									'perm_user_id' => $perm_user_id,
									'group_id' => $gruppe['group_id']
							);
							
							$removed = $lu_admin->perm->removeUserFromGroup($filter);
						}
					
					}
					

				
				if ($isInGroups){

					foreach($isInGroups as $group_id){

						$filter = array(
								'perm_user_id' => $perm_user_id,
								'group_id' => $group_id
						);
							
						$added = $lu_admin->perm->addUserToGroup($filter);
					
					}
				
				}
		
			}
			
			if( trim($value->idType) == 'group_id' ){
				$group_id = (int)$value->id;
			}
			
			if($action == 'group-right' and $group_id){

				
				//remove user rights
				$remove_right_ids = $obj->rr;
				$respond['remove_right_ids'] = $remove_right_ids;
				
				foreach($remove_right_ids as $right_id){
					$filter = array(
						'group_id' => $group_id,
						'right_id' => $right_id
					);
					
					$revoked = $lu_admin->perm->revokeGroupRight($filter);

				} 
				
				$right_ids = $obj->$group_id;
				// grant user rights
				
				$respond['right_ids_response'] = $right_ids;
				
				foreach($right_ids as $right_id){
			 
					$data = array(
						'group_id' => $group_id,
						'right_id' => $right_id
					);
					
					$granted = $lu_admin->perm->grantGroupRight($data);
				}
			}
		}
	}
}

if (!$LU->checkRight(BENUTZERVERWALTUNG_SHOW)){
	
	$respond['action'] = $action;		
	$respond['data'] = "";
	$respond['statusmsg'] ="Not enough karma"; 
	$respond['status'] = 403;

}else{
	
	if($_GET['id']){
		
		$user_params['filters'] = array('perm_user_id' => $_GET['id']);
		
	}
	
	
	
	if ( $action  == 'get-user' && $obj->id  ){

		$perm_user_id = $obj->id;
		$respond['perm_user_id'] = $perm_user_id;
		$user_params['filters'] = array('perm_user_id' => $perm_user_id);

	}
	
	if ($action  == 'get-group' && $obj->id){

		$group_id = $obj->id;
		$respond['group_id'] = $group_id;

		$group_right_params = array(
			'fields' => array(
				'right_id',
				'right_define_name',
				'group_id',
				'area_define_name',
				'area_id'
			),
			'filters' => array(
				'group_id' => $group_id
			),
			'rekey' => true,
			'by_group' => true
			
		);
		
		$granted_group_rights = $lu_admin->perm->getRights($group_right_params);
		
		$respond['data']['group_rights'] = $granted_group_rights;
		
		
		$group_params = array( 
			'filters' => array('group_id' => $group_id)
		);
		

	
	}
	
	
	if ($action  == 'get-area' && $obj->id){

		$area_id = $obj->id;
		$respond['area_id'] = $area_id;
		
		$area_params = array( 
			'filters' => array('area_id' => $area_id)
		);
	
	}
	
	
	
	
	$roundtrip = 0;
	
	
	if(!$LU->isLoggedIn()){

		$respond['action'] = $action;		
		$respond['data'] = "";
		$respond['statusmsg'] ="you are not logged in"; 
		$respond['status'] = 403;

	}else{
	

		$groups      = $lu_admin->perm->getGroups($group_params);
		
		$params = array(
			'fields' => array('perm_user_id', 'group_id'),
			'rekey' => true,
            'group' => true
			);
		$useringroup = $lu_admin->perm->getUsers($params);
		$areas       = $lu_admin->perm->getAreas($area_params);
		
		$params = array(
			'fields' => array('area_define_name', 'right_define_name', 'application_id', 'area_id', 'right_id' ),
			'rekey' => true,
            'group' => true
			);
		$rights      = $lu_admin->perm->getRights($params);
		
		$user_params['container'] = 'perm';
		$users       = $lu_admin->getUsers($user_params);
		

		
		$roundtrip = 0;
		//user, groups and their rights.
		foreach($users as $key => $_value_user){
		
			$isInGroup = false;

			$user_data[$roundtrip]['handle'] = $_value_user['handle'];	
			$user_data[$roundtrip]['perm_user_id'] = $_value_user['perm_user_id'];
			$user_data[$roundtrip]['aktiv'] = $_value_user['aktiv'];
			$user_data[$roundtrip]['perm_type'] = $_value_user['perm_type'];
			$user_data[$roundtrip]['auth_user_id'] = $_value_user['auth_user_id'];
			$user_data[$roundtrip]['owner_user_id'] = $_value_user['owner_user_id'];
			$user_data[$roundtrip]['owner_group_id'] = $_value_user['owner_group_id'];

			foreach($useringroup as $_key_perm_user_id => $_value_groups){
			
				if ($_value_user['perm_user_id'] ==  $_key_perm_user_id ){

					$user_data[$roundtrip]['group_ids'] = $_value_groups;

					if($_value_groups){
					
						foreach($_value_groups as  $_value_group_id){
						
							$params = array(
								'fields' => array(
										'right_id',
										'right_define_name',
										'group_id',
										'area_define_name',
										'area_id'
								),
								
								'filters' => array(
									'group_id' => $_value_group_id
								),
								'rekey' => true,
								'by_group' => true

							);

							$user_data[$roundtrip]['grantet_group_rights'][$_value_group_id] = $lu_admin->perm->getRights($params);
						}
					
					}

					$isInGroup = true;
				}

			}
			
			if ($isInGroup == false){
				$user_data[$roundtrip]['group_ids'] = false;
			}
			
			$params = array(
				'fields' => array(
					'right_id', 'right_define_name', 'area_define_name', 'area_id'
				),
				'filters' => array(
					'perm_user_id' =>  $_value_user['perm_user_id']
				)		
			);
			

			$user_data[$roundtrip]['grantet_user_rights'] = $lu_admin->perm->getRights($params);
			
			$roundtrip = $roundtrip + 1 ;

		}
		
		
		if ($user_params['filters']['perm_user_id'] || 	$granted_group_rights ) {
			foreach( $rights as $area_key => $area_rights){
				$i = 0;
				foreach($area_rights as $key => $single_rights){
				
					// remove the right_id according to user_rights
					if($user_params['filters']['perm_user_id']){
						foreach( $user_data[0]['grantet_user_rights'] as  $grantet_user_rights){
							if($single_rights['right_id'] == $grantet_user_rights['right_id']){
								unset($rights[$area_key][$key]);
							}
						}
					}

					// remove the right_id according to group_rights
					if($granted_group_rights)  {
						foreach( $granted_group_rights as $key_group_rights => $group_rights){
							if($single_rights['right_id'] == $key_group_rights){
								unset($rights[$area_key][$key]);
							}
						}
					}

					if(array_key_exists($key, $rights[$area_key])){
						$temp_Array = $rights[$area_key][$key];
						unset($rights[$area_key][$key]);
						$rights[$area_key][$i] = $temp_Array;
						
						$i = $i+1;
					
					}	
				}
			}
		}
		

		$respond['data']['groups'] = $groups;
		$respond['data']['users'] = $user_data;
		$respond['data']['areas']  =  $areas;
		$respond['data']['rights']  = $rights;
	
		
		//$respond['data']['users']  = $users;

	  	$respond['action'] = $action;		
		$respond['statusmsg'] ="get user and groups"; 
		$respond['status'] = 200;

	
	}	
}	
		
	if(IS_AJAX){

		ob_start();
		//var_dump($_REQUEST);
		print_r($_REQUEST);
		$ajax_request = ob_get_contents();
		ob_end_clean();
		
		$logger->log('ajax requests '. $ajax_request, PEAR_LOG_DEBUG );
		$logger->log('ajax respond '. serialize($respond), PEAR_LOG_DEBUG );
		
	
		header('Expires: Mon, 25 Dec 1976 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Content-type: application/json; charset=utf-8');
		
		if($_response){
			$respond = $_response;
		}
		
		echo json_encode($respond);
		

		exit;
	}else{
	
	print_r($respond);
	
	}
		
?>