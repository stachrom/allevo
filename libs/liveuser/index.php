<?php
//foreach ($_REQUEST as $k => $v) {printf("k=%s v=%s<br>\n", $k, $v); }
	

//include 'user_management/LiveUserGroup.php';

	$perm_type 	= $LU->getProperty('perm_type');
   $status 	= $LU->getStatus();
 	$handle 	= $LU->getProperty('handle');


	$limit  	= array_key_exists('limit',  $_REQUEST) ? (int) trim($_REQUEST['limit'])  :10;
	$limit   = preg_match($pattern['int'], $limit)  ? $limit  : '';
	
	$offset 	= array_key_exists('offset', $_REQUEST) ? (int) trim($_REQUEST['offset']) :0;
	$offset  = preg_match($pattern['int'], $offset)  ? $offset  : '';
	 
	$action 	= array_key_exists('action', $_GET)   ? (string) trim($_GET['action'])   : '';
	$action 	= array_key_exists('action', $_POST)  ? (string) trim($_POST['action'])  : $action;
	$action  = preg_match($pattern['alphanumeric'], $action)  ? $action  : '';
 
	$source 	= array_key_exists('source', $_GET)   ? (string) trim($_GET['source'])   : '';
	$source 	= array_key_exists('source', $_POST)  ? (string) trim($_POST['source'])  : $source;
	$source  = preg_match($pattern['alphanumeric'], $source )  ? $source   : '';
 
	$target 	= array_key_exists('target', $_GET)   ? (string) trim($_GET['target'])   : '';
	$target 	= array_key_exists('target', $_POST)  ? (string) trim($_POST['target'])  : $target;
	$target  = preg_match($pattern['alphanumeric'], $target )  ?$target   : '';
 
	$name		= array_key_exists('name', $_GET)   ? (string) trim($_GET['name'])        : '';
	$name		= array_key_exists('name', $_POST)  ? (string) trim($_POST['name'])       : $name;
	$name   	= preg_match($pattern['alphanumeric'], $name)  ? $name  : '';
	
	$oldValue	= array_key_exists('oldValue', $_GET)   ? (string) trim($_GET['oldValue'])   : '';
	$oldValue	= array_key_exists('oldValue', $_POST)  ? (string) trim($_POST['oldValue'])  : $oldValue;
	$oldValue   = preg_match($pattern['alphanumeric'], $oldValue)  ? $oldValue  : '';
	
	$newValue	= array_key_exists('newValue', $_GET)   ? (string) trim($_GET['newValue'])   : '';
	$newValue	= array_key_exists('newValue', $_POST)  ? (string) trim($_POST['newValue'])  : $newValue;
	$newValue   = preg_match($pattern['alphanumeric'], $newValue)  ? $newValue  : '';
	
	$right_level   = array_key_exists('right_level ', $_GET)   ? (int) trim($_GET['right_level'])   : '';
 	$right_level   = array_key_exists('right_level ', $_POST)  ? (int) trim($_POST['right_level'])  : $right_level;
	$right_level   = preg_match($pattern['rightlevel'], $right_level)  ? $right_level  : '';

	$perm_user_id  = isset($_REQUEST['perm_user_id'])? trim($_REQUEST['perm_user_id']) : $LU->getProperty('perm_user_id');
	$perm_user_id  = preg_match($pattern['alphanumeric'], $perm_user_id)  ? $perm_user_id  : '';
	
	$auth_user_id  = isset($_REQUEST['auth_user_id'])? trim($_REQUEST['auth_user_id']) : $LU->getProperty('auth_user_id');
	$auth_user_id  = preg_match($pattern['alphanumeric'], $auth_user_id)  ? $auth_user_id  : '';
	
	$has_implied   = array_key_exists('has_implied ', $_GET)   ? (bool) trim($_GET['has_implied'])   : '0';
 	$has_implied   = array_key_exists('has_implied ', $_POST)  ? (bool) trim($_POST['has_implied'])  : $has_implied;
	
	//$auth_user_id  = preg_match($pattern['alphanumeric'], $LU->getProperty('auth_user_id'))  ? $LU->getProperty('auth_user_id')  : '';
	//print_r($auth_user_id);
	


	$_response = array(
			'result' =>'', 
			'status' => 403,
			'statusmsg'=>'permission denied',
			'action' => $action
		);  


	if ( !($perm_type >= 4) ){
	
		// nur owner dürfen die eigenen Angaben bearbeiten. 
		$params_users = array(
			'container' => 'auth',
			
         'filters'   =>  array(
         	'owner_user_id' => $LU->getProperty('owner_user_id')
			)
		);
						
			
		$params_userrechte = array(
			'fields'  => array(
				'area_define_name', 
				'right_define_name',
				'area_id', 
				'perm_user_id' 
			),
			'filters' => array(
         	'perm_user_id' => $perm_user_id,
         	'application_id' => APPLICATION_ID
          ),
			'rekey' => true,
			'group' => true
		);
			 
	}else{
	
 		// nur super und master admins dürfen alle user sehen. perm_type >= 4			
		$params_users = array(
			'container' => 'auth',
			'limit'     => $limit,
			'offset'    => $offset
						);
						
		$params_userrechte = array(
			'fields' => array( 
				'area_define_name', 
				'right_define_name', 
				'area_id',
				'perm_user_id' 
			),
			'filters' => array(
				'application_id' => APPLICATION_ID
         ),
			'rekey' => true,
			'group' => true
    );							
	}

   $UserRechteAllevo = $lu_admin->perm->getRights($params_userrechte);
	 
   $area_param = array(
      'fields' => array('area_define_name', 'area_id'),
      'filters' => array('application_id' => APPLICATION_ID) 
   );

   $liveuser_areas = $lu_admin->perm->getAreas($area_param);
  	$auth_users     = $lu_admin->getUsers($params_users);
	 
   foreach ($auth_users as $key => $value){
	 
      if ($auth_user_id == $value['auth_user_id']){
         $user_data = $value;
      }

      $all_perm_user_ids[] = $value['perm_user_id'];
   }

  
  	$smarty->assign('auth_users', $auth_users);
	$smarty->assign('UserRechteAllevo',   $UserRechteAllevo);
   $smarty->assign('allevo_area',   $allevo_area);
   $smarty->assign('LiveUserApplications', $LiveUserApplications);
   $smarty->assign('LiveUserAreas', $liveuser_areas);
	 
	//print_r($auth_users);
	//print_r($allevo_area);
   //print_r($LiveUserApplications);
   //print_r($liveuser_areas);


          

	//########################################################################//
	//#########################      Show User      ##########################//
	//########################################################################//
	

	
	if($LU->checkRightLevel( USERMANAGEMENT_VIEW, $LU->getProperty('owner_user_id'), $LU->getProperty('owner_group_id')) or $LU->getProperty('perm_user_id') == $perm_user_id ){

      if($action == "showUser"){

									
									
									$params_perm = array( 	
											'select'  => 'row',
											'filters' => array('perm_user_id' => $perm_user_id),
											'fields'  => array('perm_user_id', 'auth_user_id')
										);

									if ( ($perm_type >= 4) ){
										array_unshift( $params_perm['fields'], 'perm_type');
									}

										
									$result_perm = $lu_admin->perm->getUsers($params_perm);
										

									$params_auth = array( 	
										'select'  => 'row',
										'filters' => array('auth_user_id' => $result_perm['auth_user_id']),
										'fields'  => array('passwd','geschlecht', 'vorname','nachname', 'email', 'plz','stadt', 'strasse', 'strassen_nummer', 'land','tel','mobile','fax', 'internet', 'bemerkungen', 'avatar')
										);
										
									if ( ($perm_type >= 4) ){
									
										array_unshift( $params_auth['fields'], 'handle', 'is_active', 'owner_group_id', 'owner_user_id');

									}
										
									$result_auth = $lu_admin->auth->getUsers($params_auth);
										
										
										 //$_response['result_auth'] = $result_auth;
										 //$_response['result_perm'] = $result_perm;
										
									if(is_array($result_auth) and is_array($result_perm)){
										$result = array_merge( $result_perm, $result_auth);
									}
									

         if( is_array($result) ){
				
				foreach( $result as $key => $value ){

						switch($key){
							case 'perm_type':
								$form[] = array( 
									'name' => "$key", 
									'label' => 'Benutzer Rolle: ', 
								   'type'  => 'SelectField',
									'choices' => array(
														array('label'=>'anonymer user', 'value'=> '0'),
														array('label'=>'user',          'value'=> '1'),
														array('label'=>'admin',         'value'=> '2'),
														array('label'=>'area_admin',    'value'=> '3'),
														array('label'=>'super_admin',   'value'=> '4'),
														array('label'=>'master_admin',  'value'=> '5')
												) 
								);
								break;
							case 'is_active':
								$form[] = array( 
									'name' => $key, 
									'label' => 'Aktiv', 
									'type'  => 'CheckboxField',
									'value' => '1',
									'checked'  => ($value == 1) ? true  : false 
								);
								break;
							case 'geschlecht':
									$form[] = array( 
									'name' => $key, 
									'label' => $key, 
									'type'  => 'ChoiceField',
									'value' => "$value",
									'multi'  => false, 
									'choices' => array(
														array('label'=>'Frau', 'value'=> '0'),
														array('label'=>'Mann', 'value'=> '1') 
												) 
								);
								break;	
							case 'bemerkungen':
								$form[] = array( 'name' => $key, 'label' => $key, 'value' => $value, 'type'  => 'TextareaField');
								break;
							case 'avatar':
								$form[] = array( 'name' => $key, 'label' => $key, 'type'  => 'FileField');
								break;	
							case 'email':
								$form[] = array( 'name' => $key, 'label' => $key, 'value' => $value, 'type' => 'TextField');
								break;	
							case 'passwd':
								$form[] = array( 'name' => 'new_password', 'label' => 'neues Passwort: ',  'type' => 'PasswordField');
								break;
							case 'handle':
							   $form[] = array( 'name' => 'Old-username', 'label' => 'bisheriger Benutzername ', 'type' => 'TextField', 'value' => $value, 'disabled' => true);
								$form[] = array( 'name' => 'username', 'label' => 'neuer Benutzername ',  'type' => 'TextField' );
								break;	
							case 'perm_user_id':
								$form[] = array('name' => $key, 'value' => "$value", 'type'  => 'HiddenField');
								$form[] = array('name' => 'action', 'value' => "update_user_account", 'type'  => 'HiddenField');
								break;	
							default:	
								$form[] = array( 'name' => $key, 'label' => $key, 'value' => "$value", 'type'  => 'TextField');
								break;
						}// end switch
				}// end foreach
		   }else{
						$_response['status'] = 400;
					   $_response['statusmsg'] = 'could not finde user';
				}
				

				
				if($result){
				
					$form[] = array( 'name' => 'submit',   'type'  => 'SubmitButton', 'value' => 'Save');
				   $form[] = array( 'name' => 'reset',    'type'  => 'ResetButton',  'value' => 'Reset');
               
               
               if( $LU->checkRightLevel( USERMANAGEMENT_DELETE, $LU->getProperty('owner_user_id'), $LU->getProperty('owner_group_id')) ){
               
                  if($perm_user_id == $LU->getProperty('perm_user_id')){
       
                  }else{
                     $form[] = array( 'name' => 'DeleteUser',   'type'  => 'SubmitButton', 'value' => 'Delete');
                  }
                  
               }
               
               
               
				// sich selbst löschen ist nicht gut.

					$_response['result'] = $form;
					$_response['status'] = 200;
					$_response['statusmsg'] ='Show User Account';

				}else{
					$_response['status'] = 400;
					$_response['statusmsg'] = 'could not finde user';
	
				}
				
			}
	
		}
			
	//########################################################################//
	//#########################    Edit User      ##########################//
	//########################################################################//			

	if( $LU->checkRightLevel( USERMANAGEMENT_EDIT, $LU->getProperty('owner_user_id'), $LU->getProperty('owner_group_id')) or $LU->getProperty('perm_user_id') == $perm_user_id ){	
   
      $_response['post']= $_POST;

      if( $_POST['DeleteUser'] == "Delete"){

      }
	
				if($action == "update_user_account"){

					$user_data = array(
									'avatar' => '',
									'bemerkungen' => filter_var($_POST['bemerkungen'], FILTER_SANITIZE_SPECIAL_CHARS),
									'email'       => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL),
									'fax'         => filter_var($_POST['fax'], FILTER_SANITIZE_SPECIAL_CHARS),
									'internet'    => filter_var($_POST['internet'], FILTER_SANITIZE_URL),
									'land'        => filter_var($_POST['land'], FILTER_SANITIZE_SPECIAL_CHARS),
									'mobile'      => filter_var($_POST['mobile'], FILTER_SANITIZE_SPECIAL_CHARS),
									'nachname'    => filter_var($_POST['nachname'], FILTER_SANITIZE_SPECIAL_CHARS),
									'plz'         => filter_var($_POST['plz'], FILTER_SANITIZE_SPECIAL_CHARS),
									'stadt'       => filter_var($_POST['stadt'], FILTER_SANITIZE_SPECIAL_CHARS),
									'strasse'     => filter_var($_POST['strasse'], FILTER_SANITIZE_SPECIAL_CHARS),
									'strassen_nummer' => filter_var($_POST['strassen_nummer'], FILTER_SANITIZE_SPECIAL_CHARS),
									'tel'        => filter_var($_POST['tel'], FILTER_SANITIZE_SPECIAL_CHARS),
									'vorname'    => filter_var($_POST['vorname'], FILTER_SANITIZE_SPECIAL_CHARS)		
			        );
				
					$geschlecht = preg_match($pattern['bin'], $_REQUEST['geschlecht']) ? (int)$_REQUEST['geschlecht'] : false;
					$geschlecht != false ? ($user_data['geschlecht'] = $geschlecht) : false;
					
					$new_password = preg_match($pattern['password'], $_REQUEST['new_password']) ? (string)$_REQUEST['new_password'] : false ; 
			      $new_password  != false ? ($user_data['passwd'] = $new_password ) : false;
					

			         	// are you super or master admin? 
				if ( !($perm_type >= 4) ){
							//you are normal user like 1,2 or 3
				}else{
						   // master(5) or super admin(4)
							 // do not change manualy !!!
							//$data['perm_user_id'] =' ';
								
					$owner_group_id = preg_match($pattern['int'], $_REQUEST['owner_group_id']) ? (int)$_REQUEST['owner_group_id'] : false;
					$owner_group_id != false ? ($user_data['owner_group_id'] = $owner_group_id) : false;
				
					$owner_user_id = preg_match($pattern['int'], $_REQUEST['owner_user_id']) ? (int)$_REQUEST['owner_user_id'] : false;
					$owner_user_id != false ? ($user_data['owner_user_id'] = $owner_user_id) : false;
				
					$user_data['is_active']= preg_match($pattern['bin'], $_REQUEST['is_active']) ? (int)$_REQUEST['is_active'] : 0;
					
					$perm_type_data = preg_match($pattern['0-5'], $_REQUEST['perm_type']) ? (int)$_REQUEST['perm_type'] : false;
					$perm_type_data != false ? ($user_data['perm_type'] = $perm_type_data) : false;
					
					//check user name 			

					$username = preg_match($pattern['text'], $_REQUEST['username']) ? (string) trim($_REQUEST['username']) : false;
					$username != false ? ($user_data['handle'] = $username) : false;

							// $data['auth_user_id']=''; // do not change manualy !!!
				}
						
						$result = $lu_admin->updateUser($user_data, $perm_user_id); 
						
						if ($result){
						
							$_response['result'] = $result;
							$_response['status'] = 200;
							$_response['statusmsg']= 'User Account edited';
							$_response['perm_user_id']= $perm_user_id;
							$_response['handle']= $user_data['handle'];
							$_response['is_active']= $user_data['is_active'];
							

						}else{
							$_response['status'] = 400;
							$_response['statusmsg']= 'User Account is not eddited';
							$_response['extra']= $user_data;
                     
							

						}
                  
                  
			}	
	}
	
	
	//########################################################################//
	//#########################    Delete User      ##########################//
	//########################################################################//	
	
	if (!$LU->checkRightLevel(	USERMANAGEMENT_DELETE, $LU->getProperty('owner_user_id'), $LU->getProperty('owner_group_id') )){ 
	
		if( $action == "deleteUser" ){ 
			header('Location: index.php?logout=1');
		}
		

		
	}else{

		if( $action == "deleteUser" ){

         $LiveUserToDelete = $lu_admin->getUsers(array('filters' => array('perm_user_id' => $perm_user_id)));
     
      	$filter_delete = array(
				'perm_user_id'=> $LiveUserToDelete['0']['perm_user_id']
			);


			//prevent from selve deleting!!!!
			if($perm_user_id != $LU->getProperty('perm_user_id')){

				$_response['result']	= $lu_admin->removeUser($filter_delete);
				$_response['status']	= 200;
				$_response['statusmsg']	= "User Account Deleted"; 

			}else{
			   $_response['status']	= 400;
				$_response['statusmsg']	= "sich selbst löschen ist untersagt!"; 

			}

	   } // ende remove user																			
	}
	

	
	//########################################################################//
	//#########################    Create User      ##########################//
	//########################################################################//	
	
	
	if (  $action == "create_new_user"   &&  $LU->checkRightLevel(USERMANAGEMENT_VIEW, $LU->getProperty('owner_user_id'), $LU->getProperty('owner_group_id'))   ){ 

				$passwort1     = preg_match($pattern['password'], $_POST['pass1'])  ? (string)$_POST['pass1']  : false;
				$passwort2     = preg_match($pattern['password'], $_POST['pass2'])  ? (string)$_POST['pass2']  : false;
				$username      = preg_match($pattern['username'], $_POST['username'])  ? (string)$_POST['username']  : false;
	
				$passwort     = $passwort1 === $passwort2 ? (string)$passwort1 : false;
				$user_to_add  = array();


						
	
			if($passwort != false and $username != false ){
					  
				$user_to_add['handle'] = $username;
				$user_to_add['passwd'] = $passwort;
				$user_to_add['is_active'] = 0;
				$user_to_add['owner_user_id'] = $mdb2->nextID('liveuser_users');
				$user_to_add['owner_group_id'] = $mdb2->nextID('liveuser_users');
		
				
				
				$permUserId = $lu_admin->addUser($user_to_add);
				
				if($permUserId){
				
					$_response['status'] = 200;
					$_response['result'] = $permUserId;
					$_response['username'] = $user_to_add['handle'];
					$_response['statusmsg'] = 'User wurde erfolgreich hinzugefügt';
					
				}else{
				
					$_response['status'] = 400;
					$_response['statusmsg'] = 'username ist schon vergeben';
					
				}	
					$_response['username'] = $username;
					
			}else{
					$_response['status'] = 400;
					$_response['statusmsg'] = 'Passwörter sind ungleich';
			}
			 

				$data_group = array(
						'perm_user_id' =>  $permUserId,
						'group_id' => $_REQUEST['gruppen']
				);
				  
				if($data_group['group_id']){
					  $added = $lu_admin->perm->addUserToGroup($data_group);
				} 
			
				// Clear action.
				$action = $data = "";			
	}else{
	
		if( $action == "create_new_user" ){
		
					$_response['statusmsg'] = 'Sie besitzen kein Recht das Usermanagement zu betrachten';
		}
	
																			
	}
	
	
	
	
	

	


	//########################################################################//
	//#########################   Area Management   ##########################//	
	//########################################################################//
		
		if( $action == "add_area"){

 			$data = array(
							'application_id' => APPLICATION_ID,
                  	'area_define_name' => $name
							);
							
    		$areaId = $lu_admin->perm->addArea($data);


			   $respond = array();
				$respond['action'] = $action;
				
			if ($areaId === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich eine neues Modul hinzugefügt";
				$respond['areaId']  = $areaId;
				$respond['name']    = $name;
      	}
			
			// clear action 
			$action = $data = "";

		}
	
	
		if( $action == "update_area"){

				 $data = array('area_define_name' => $name);
				 $filter = array('area_id' => $source);
				 
				 $updateArea = $lu_admin->perm->updateArea($data, $filter);

			   $respond = array();
				$respond['action'] = $action;
				
			if ($updateArea === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Modul upgedated";
			
      	}
			
				// clear action 
				$action = $data = $filter = "";

		}
		
		
		if( $action == "remove_area"){

				$filter = array('area_id' => $source);

				$removeArea = $lu_admin->perm->removeArea($filter);
				
			   $respond = array();
				$respond['action'] = $action;
				
			if ($removeArea === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Modul entfernt";
				$respond['area_id'] = $source;

      	}
			
				// clear action 
				$action = $filter = "";

		}
		
		
		//******************************************************************************//
		//*****************  Promote a User as an Area Administrator  ******************//
		//******************************************************************************//
	
		if( $action == "add_area_admin"){

			$data = array(
					'area_id' => $source,
               'perm_user_id' => $perm_user_id
					);
							
    
    		$areaAdminId = $lu_admin->perm->addAreaAdmin($data);

			   $respond = array();
				$respond['action'] = $action;
				
			if ($areaAdminId === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "User wird als Areaadmin ernannt";
				$respond['areaId']  = $areaId;
				$respond['name']    = $name;
      	}
			
			// clear action 
			$action = $data = "";

		}	
	
	
		if( $action == "remove_area_admin"){

			$filter = array(
					'area_id' => $source
					);
							
    		$removeAreaAdmin = $lu_admin->perm->removeAreaAdmin($filter);

			   $respond = array();
				$respond['action'] = $action;
				
			if ($removeAreaAdmin === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "User wird als Areaadmin entfernt";
				$respond['areaId']  = $areaId;
				$respond['name']    = $name;
      	}
			
			// clear action 
			$action = $filter = "";

		}			
		
		
		
	//########################################################################//
	//#########################   Group Management   #########################//
	//########################################################################//
	
		if( $action == "add_group"){

 				$data = array('group_define_name' => $name);
    			$groupId = $lu_admin->perm->addGroup($data);

			   $respond = array();
				$respond['action'] = $action;
				
			if ($groupId === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich eine neue Gruppe gegründet";
				$respond['groupId'] = $groupId;
      	}
			
				// clear action 
				$action = $data = "";

		}
		
		if( $action == "update_group"){
		
				$data   = array('group_define_name' => $name);
				$filter = array('group_id' => $source);
			
				$updateGroup = $lu_admin->perm->updateGroup($data, $filter);

				$respond     = array();
				$respond['action'] = $action;
	
			if ($updateGroup === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich die Gruppe Modifiziert";
				$respond['group_name'] = $name;
      	}
	
				// clear action 
				$action = $data = $filter ="";

		}
		
		if( $action == "get_groups"){

	
				// es werden nur gruppen angezeigt, in welchen die user mitglied sind. -->  perm_type >= 4 
				if ( !($perm_type >= 4) ){
					
   	 				$params = array( 
											'filters' => array('perm_user_id' => $perm_user_id) 
								);		
					}else{
									
						$params =array( 
										'filters' => array() 
								);		
					}
					
					
    			$groups = $lu_admin->perm->getGroups($params);
				
				$respond     = array();
				$respond['action'] = $action;
	
			if ($groups === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich die Gruppe Aufgelistet";
				$respond['data'] = $groups ;
      	}
	
				// clear action 
				$action = $data = $params = "";

		}
		
		
		if( $action == "remove_group"){
		
				$filter = array('group_id' => $source);
				$removed_group = $lu_admin->perm->removeGroup($filter);
				
				$respond     = array();
				$respond['action'] = $action;
	
			if ($removed_group === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich die Gruppe gelöscht";
				$respond['data'] = $removed_group ;
      	}
	
				// clear action 
				$action = $data = $filter = "";

		}
		
		
		
		if( $action == "add_user_to_group"){

				$data = array(
        			'perm_user_id' => $perm_user_id,
					'group_id' => $source
       		);
			
				$added = $lu_admin->perm->addUserToGroup($data);
				
				$respond     = array();
				$respond['action'] = $action;
	
			if ($added === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich den User zur Gruppe hinzugefügt";
				$respond['data']    = $data ;
      	}
	
				// clear action 
				$action = $data = "";

		}
		
		if( $action == "remove_user_from_group"){

				$filter = array(
        			'perm_user_id' => $perm_user_id,
					'group_id' => $source
       		);
			
				$removed = $lu_admin->perm->removeUserFromGroup($filter);
				
				$respond     = array();
				$respond['action'] = $action;
	
			if ($removed === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich den User von der Gruppe entfernt";
				$respond['data']    = $data ;
      	}
	
				// clear action 
				$action = $filter = "";

		}		
		
		
	//########################################################################//		
	//######################   Rights Management   ###########################//
	//########################################################################//
	
	
			if( $action == "add_right_to_area"){

			$data = array(	'area_id' => $source,
                  		'right_define_name' => $name,
                  		'has_implied' => $has_implied
								);

		 	$rightId = $lu_admin->perm->addRight($data);
			
			   $respond = array();
				$respond['action'] = $action;
				
			if ($rightId === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Rechte erstellt";
				$respond['rightId'] = $rightId;
      	}
			
			// clear action 
			$action = $data = "";

		}
	


		if( $action == "update_right_from_area"){

			$data = array('right_define_name' => $newValue );
			$filter = array('right_id' => $source);
			
			$updateRight = $lu_admin->perm->updateRight($data, $filter);
			
				$respond     = array();
				$respond['action'] = $action;
				
	
			if ($updateRight === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
				$respond['newValue'] = $newValue;
				$respond['oldValue'] = $oldValue;
				$respond['replyText'] = "erfoglreich das Recht geändert";
				$respond['replyCode'] = "201";
				$respond['replyType'] = "text";
				$respond['data'] = $updateRight;
      	}
			
			
			// clear action 
			$action = $data = $filter = "";

		}
		
		
		if( $action == "get_rights_from_area"){

			$filter = array('filters' => array('area_id' => $source ));
			$rights = $lu_admin->perm->getRights($filter);
			
		 	$respond     = array();
			$respond['action'] = $action;
			
			 if ($rights === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich die Rechte für das Area aufgezählt";
				$respond['data'] = $rights;
      	}
			
			// clear action 
			$action = $filter = "";

		}
		
	   if( $action == "remove_rights"){

			$filter = array('right_id' => $source);
			$removeRight = $lu_admin->perm->removeRight($filter);
			
			$respond     = array();
			$respond['action'] = $action;
		 
		 	if ($removeRight === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich Berechtigung für Area entfernt";
      	}

			// clear action 
			$action = $filter = "";

		}
		
		//******************************************************************************//
		//************************  implied Rights Management  *************************//
		//******************************************************************************//		
		
		if( $action == "imply_right"){


			 $data = array(
						'right_id' => $source,
						'implied_right_id' => $target
						);
						
			 $impliedright = $lu_admin->perm->implyRight($data);
			
			$respond     = array();
			$respond['action'] = $action;
		 
		 	if ($impliedright === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich Rechte miteinbezogen ";
      	}

			// clear action 
			$action = $data = "";

		}
		
		if( $action == "unimply_right"){
		
			$update_imply_status = false;
			
    		$filter = array(
							'right_id' => $source,
							'implied_right_id' => $target
                   );
						 
			$deleteImpliedRight =$lu_admin->perm->unimplyRight($filter, $update_imply_status);
			
			$respond     = array();
			$respond['action'] = $action;
		 
		 	if ($deleteImpliedRight === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich einbezogene Rechte entzogen ";
      	}

			// clear action 
			$action = $filter = "";

		}
		

	//########################################################################//
	//######################   Permissions Management   ######################//
	//########################################################################//
		
	//right_level 1 Then it means the user owner needs to match the given user
	//right_level 2 Then it means either the group owner or the user owner needs to match the given user
	//right_level 3 That means he is not restricted by ownership
	

		//******************************************************************************//
		//***********************   Permissions Management User  ***********************//
		//******************************************************************************//

	   if( $action == "grant_user_right"){
		
			$data = array(
        		'perm_user_id' => $perm_user_id,
        		'right_id' => $target,
				'right_level' => $right_level,
			 );
			 
    		$granted = $lu_admin->perm->grantUserRight($data);

			$respond = array();
			$respond['action'] = $action;
			
			if ($granted === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich Rechte gewährt";
      	}
			
			// clear action 
			$action = $data = $filter = "";

		}


 		if( $action == "update_user_right"){

 			$data = array('right_level' => $right_level);
		
			$filter = array(
						'perm_user_id' => $perm_user_id,
						'right_id'     => $target
						);
					
    		$updatedUserRight = $lu_admin->perm->updateUserRight($data, $filter);

			$respond     = array();
			$respond['action'] = $action;
			
			if ($updatedUserRight=== false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Recht geändert";
      	}
			
			// clear action 
			$action = $data = $filter = "";
		}


 		if( $action == "revoke_user_right"){

			$filter = array(
				'perm_user_id' => $perm_user_id,
        		'right_id'     => $target
       	);
			
    		$revoked = $lu_admin->perm->revokeUserRight($filter);

			$respond     = array();
			$respond['action'] = $action;
			
			if ($revoked=== false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Recht geändert";
      	}
			
			// clear action 
			$action = $data = $filter = "";
			

		}

		//******************************************************************************//
		//***********************  Permissions Management Group  ***********************//
		//******************************************************************************//
		
	   if( $action == "grant_group_right"){
		
			$data = array(
        		'right_id' => $target,
        		'group_id' => $source,
				'right_level' => $right_level
			 );
			 
    		$granted = $lu_admin->perm->grantGroupRight($data);

			$respond = array();
			$respond['action'] = $action;
			
			if ($granted === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Recht für die Gruppe gewährt";
      	}
			
			// clear action 
			$action = $data = $filter = "";

		}	
	
	   if( $action == "update_group_right"){

			 $data    = array(
			 				'right_level' => $right_level
							);
    		 $filters = array(
			 				'right_id' => $target,
                     'group_id' => $source
							);

    		$updated = $lu_admin->perm->updateGroupRight($data, $filters);

			$respond = array();
			$respond['action'] = $action;
			
			if ($updated === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Recht für die Gruppe upgedated";
      	}
			
			// clear action 
			$action = $data = $filters = "";

		}	


	   if( $action == "revoke_group_right"){

    		 $filter = array(
                     'group_id' => $source
							);

    		$revoke = $lu_admin->perm->revokeGroupRight($filter);
			

			$respond = array();
			$respond['action'] = $action;
			
			if ($revoke === false){
   			$respond['error'] = $lu_admin->getErrors();
      	}else{
     			$respond['success'] = "erfoglreich das Recht für die Gruppe entfernt";
      	}
			
			// clear action 
			$action = $data = $filter = "";

		}
		
		
// ---------------- Form aufruf von ADD Appplication LiveUser -------------------


	//########################################################################//
	//######################   authentication   ##############################//
	//########################################################################//
		if ($action == 'authentication'){
		
		if(!$LU->isLoggedIn()){
		
		
		
		
		}else{
		
				$_response['status'] = 200;
				$_response['statusmsg'] = $handle .' ist eingeloggt';
		
		
		
		}
		
		}else{
		
		
		}

?>