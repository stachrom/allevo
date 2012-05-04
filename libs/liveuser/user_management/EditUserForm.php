<?php
//foreach ($_REQUEST as $k => $v) {printf("k=%s v=%s<br>\n", $k, $v); }
class editUserForm extends HTML_QuickForm{
	function editUserForm()
        {
            parent::HTML_QuickForm('editUserForm');

            global $lu_admin;
				global $LU;
            global $auth_users;
				global $user_data;
				global $live_user_id;
 
           //print_r($user_data);
   			$perm_type = $LU->getProperty('perm_type');
				
		
				

			if($user_data and $_REQUEST['json_request'] == "edit_liveUser"){

			   $group_id = $lu_admin->perm->getGroups(
					array (
						'fields'  => array ('group_id'),
						'filters' => array ('perm_user_id' => $perm_user_id )	 
                    )
             );	
	
			}else{
            	//$live_user_id=$_REQUEST['cmd_edit_user'];
			}
			


    		$LiveUserGroups=$lu_admin->perm->getGroups();
			$Liveuser_gruppen["Null"]= "keine Gruppe";
			foreach($LiveUserGroups as $a){
				$Liveuser_gruppen[$a['group_id']]= $a['group_define_name'];
			}

           //print_r($user_data);
           //add forms
			  
			$this->addElement('header', '', 'login');

			$this->addElement('hidden', 'auth_user_id', $user_data['auth_user_id']);
			$this->addElement('hidden', 'perm_user_id', $user_data['perm_user_id']);
			$this->addElement('hidden', 'alte_gruppe', $group_id[0]['group_id']);
			$this->addElement('hidden', 'action', 'update_user_account');
			$this->addElement('hidden', 'username', $user_data['handle'] );
			
					
		   $this->addElement('select',   'gruppen', 'Gruppen', $Liveuser_gruppen );
			
 

			$this->addElement('advcheckbox', 'is_active',  '', 'aktive', '', 1);
			$this->addElement('text',   'neuer_username', 'neuer Username:', array('size' => 20, 'maxlength' => 255));
    		$this->addElement('password', 'pass1', 'neues Passwort:', array('size' => 10, 'maxlength' => 255));
			$this->addElement('password', 'pass2', 'Passwort bestätigen:', array('size' => 10, 'maxlength' => 255));
			

        if ( ($perm_type >= 4) ){
		  $this->addElement('header', '', 'Benutzerrolle');
         $rolle[] = &HTML_QuickForm::createElement('radio', 'rolle', 'Anonym ','', '0');
         $rolle[] = &HTML_QuickForm::createElement('radio', 'rolle', 'User','', '1');
			$rolle[] = &HTML_QuickForm::createElement('radio', 'rolle', 'Admin','', '2');
			$rolle[] = &HTML_QuickForm::createElement('radio', 'rolle', 'Modul Admin ','', '3');
			$rolle[] = &HTML_QuickForm::createElement('radio', 'rolle', 'Super Admin ','', '4');
			$rolle[] = &HTML_QuickForm::createElement('radio', 'rolle', 'Master Admin ','', '5');
			$this->addGroup($rolle, 'rolle', 'Rolle:');
			
        }

			
			$this->addElement('header', '', 'Postanschrift');

			$radio[] = &HTML_QuickForm::createElement('radio', 'anrede', 'Herr','', '0');
			$radio[] = &HTML_QuickForm::createElement('radio', 'anrede', 'Frau','', '1');
			$radio[] = &HTML_QuickForm::createElement('radio', 'anrede', 'Firma','', '2');
			$this->addGroup($radio, 'anrede', 'Anrede');
			
			
			$this->addElement('text', 'vorname', 'Vorname:', array('size' => 20, 'maxlength' => 255) );
			$this->addElement('text', 'nachname', 'Nachname:', array('size' => 20, 'maxlength' => 255) );
			$this->addElement('text', 'strasse', 'Strasse',array('size' => 20, 'maxlength' => 255) );
			$this->addElement('text', 'nummer', 'Nr.:',array('size' => 4, 'maxlength' => 5) );
			$this->addElement('text', 'plz', 'PLZ',array('size' => 4, 'maxlength' => 5) );
			$this->addElement('text', 'stadt', 'Stadt:',array('size' => 20, 'maxlength' => 255) );
			$this->addElement('text', 'land', 'Land',array('size' => 20, 'maxlength' => 255) );


			
			$this->addElement('header', '', 'Kommunikation');
			
			$this->addElement('text', 'email', 'Email:',array('size' => 20, 'maxlength' => 255) );
			$this->addElement('text', 'internet', 'Internet:',array('size' => 20, 'maxlength' => 255) );
		   $this->addElement('text', 'mobile', 'Mob.:',array('size' => 11, 'maxlength' => 20) );
			$this->addElement('text', 'tel', 'Tel:',array('size' => 11, 'maxlength' => 20) );
			$this->addElement('text', 'fax', 'Fax:',array('size' => 11, 'maxlength' => 20) );

			



			$this->addElement('textarea', 'bemerkungen', 'Bemerkungen', array('rows' => 10, 'cols' => 30) );
			

         $this->setDefaults(
						array(
									'auth_user_id' =>	      $user_data['auth_user_id'],
									'auth_container_name' =>$user_data['auth_container_name'],
                           'vorname' =>		      $user_data['vorname'],
									'nachname' =>		      $user_data['nachname'],
                           'anrede' =>		        	(string)$user_data['geschlecht'],
									'rolle'  =>            	(string)$user_data['perm_type'],
									'is_active'  => 			(bool)$user_data['is_active'],	
								  	'email' =>		         $user_data['email'],
								  	'internet' =>		      $user_data['internet'],
								  	'tel' =>			         $user_data['tel'],
								  	'fax' =>			         $user_data['fax'],
								  	'mobile' =>		         $user_data['mobile'],
								  	'strasse' =>		      $user_data['strasse'],
								  	'nummer' =>		         $user_data['strassen_nummer'],
								  	'plz' =>			         $user_data['plz'],
								  	'stadt' =>		        	$user_data['stadt'],
								  	'land' =>			      $user_data['land'],
								  	'bemerkungen' =>	      $user_data['bemerkungen'],
									'permission'  =>        $user_data['perm_type'],
									'gruppen'     =>        $group_id[0]['group_id']
									
								  )
			);


			// add rules
			$this->addRule('email', 'dies scheint keine Valide email zu sein', 'email');
			$this->addRule('nummer', 'Bitte nur Zahlen angeben', 'numeric');
			$this->addRule('plz', 'Bitte nur Zahlen angeben', 'numeric');
			$this->addRule('tel', 'Bitte nur Zahlen angeben', 'numeric');
			$this->addRule('fax', 'Bitte nur Zahlen angeben', 'numeric');
			$this->addRule('mobile', 'Bitte nur Zahlen angeben', 'numeric');
			$this->addRule(array('pass1', 'pass2'), 'Ihre Passwörter sind ungleich<br/>', 'compare');
            
			// assigne and add costume rule
         $this->registerRule('checkusername', 'callback', 'usernameOK', &$this);
         $this->addRule('neuer_username', 'Username ist schon benutzt <br/>', 'checkusername');

			// add filters
         $this->applyFilter('ALL', 'trim');
		}
		

		function usernameOK($username){
		
			global $auth_users;
			
			foreach ($auth_users as $a) {
            if ($username == $a['handle']){
           			return false;
            }
			}
         	return true;
      }

        

		function process_data($data){

      global $lu_admin;
      global $perm_user_id;
		global $action;
		global $logger;
		
			
		print_r("asdfasdfasdf");
		
		$respond = array();
		
		$logger->log('prozess data change liveuser data', PEAR_LOG_DEBUG );


      if( $action == update_user_account ){

			$user_to_add = array();

			if($data['neuer_username']){
				$respond['old_username'] =  $username=$data['username'];
				$username=$data['neuer_username'];
			}else{
				$username=$data['username'];
			}

         (int)$permissiontype=$data['rolle']['rolle'];

			$update_data=array(                   
				'perm_type' 		=> $permissiontype,
            'auth_user_id'   	=> $data['auth_user_id'],
            'handle'         	=> $username,
            'is_active'      	=> $data['is_active'],
            'vorname'        	=> $data['vorname'],
            'nachname'       	=> $data['nachname'],
            'email'          	=> $data['email'],
            'geschlecht'     	=> $data['anrede'],
            'strasse'    	 	=> $data['strasse'],
            'strassen_nummer'	=> $data['nummer'],
            'plz'    	     	=> $data['plz'],
            'stadt'    	     	=> $data['stadt'],
            'land'    	    	=> $data['land'],
            'internet'    	 	=> $data['internet'],
            'tel'    	     	=> $data['tel'],
            'fax'    	     	=> $data['fax'],
            'avatar' 	      => $data['avatar'],
            'mobile'    	 	=> $data['mobile'],
            'bemerkungen'    	=> $data['bemerkungen']
          );
                               
         if($data['pass1']){						 
         	$update_password = array('passwd' => $data['pass1']);
         	$update_data = array_merge($update_password, $update_data);
			}else{
				//do nothing altes passwort behalten
			}

                      //print_r($data);
                      //print_r($update_data);
                      //print_r($permissiontype);

			// ------------------------ add / remove user to gruppe -------------------	

				$filter_neu = array(
					'perm_user_id' => $data['perm_user_id'],
					'group_id' 		=> $data['gruppen']
				);
			
				$filter_alt = array(
					'perm_user_id' => $data['perm_user_id'],
					'group_id' 		=> $data['alte_gruppe']
				);
			
    			$removed = $lu_admin->perm->removeUserFromGroup($filter_alt);
				$added   = $lu_admin->perm->addUserToGroup($filter_neu);			
				$result  = $lu_admin->updateUser( $update_data, $data['perm_user_id']);
			
			
			
			//############################ ajax json response ############################//
			 
				$respond['action']					=$action;
				$respond['updated_handle']			=$username;
				$respond['updated_auth_user_id']	=$data['auth_user_id'];

			if($result === false){
				$respond['error'] =  "error occured";
			}else{
				$respond['addUserToGroup'] =  $added;
				$respond['effected_rows']  =  $result;
				$respond['success'] =  "success";
			}
			
				// Clear action.
				$action = '';
				
				header("Content-Type: application/json");
				echo json_encode($respond);
				exit;

			} // ende save user (add/remove user from groupe)
	
	}


}
?>