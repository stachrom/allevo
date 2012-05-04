<?php

class signupForm extends HTML_QuickForm{

     function signupForm(){
	  
            parent::HTML_QuickForm('signupForm');
				global $lu_admin;

    			$LiveUserGroups=$lu_admin->perm->getGroups();
				$Liveuser_gruppen["Null"]= "keine Gruppe";
				
				foreach($LiveUserGroups as $a){
					$Liveuser_gruppen[$a['group_id']]= $a['group_define_name'];
				}

         	$this->addElement('header', null, 'Signup');
         	$this->addElement('text', 'username', 'Username', array('size' => 20, 'maxlength' => 255));
				$this->addElement('text', 'vorname', 'vorname', array('size' => 20, 'maxlength' => 255));
         	$this->addElement('password', 'pass1', 'Passwort', array('size' => 10, 'maxlength' => 255));
				$this->addElement('select', 'gruppen', 'Gruppen', $Liveuser_gruppen );
			
			
            $this->addElement('password', 'pass2', 'Passwort bestätigen', array('size' => 10, 'maxlength' => 255));
            $this->addElement('hidden', 'cmd', 'new_user');
            // add a  group of buttons. Groups appear on one line
            $group = array();
            $group[] =& $this->createElement('reset', 'reset', 'Abbrechen' );
            $group[] =& $this->createElement('submit', 'submit', 'Speichern');
            $this->addGroup($group, 'buttons');
            $this->addElement('submit', 'edit_group', 'Gruppen bearbeiten');
            // die regeln
            $this->addRule('username', 'Benutzername zwingend <br/>', 'required');
            $this->addRule('pass1', 'Passwort ist zwingend <br/>', 'required');
            $this->addRule('pass1', 'Ihr Passwort muss zwischen 6 und 20 Zeichen enthalten <br/>', 'rangelength', array(6,20));
            // passwrter vergleichen
            $this->addRule(array('pass1', 'pass2'), 'Ihre Passwörter sind ungleich<br/>', 'compare');

            // register and add a rule to check if a username is free
            $this->registerRule('checkusername', 'callback', 'usernameOK', &$this);

            $this->addRule('username', 'Benutzername ist schon vergeben<br/>', 'checkusername');
        }

        function usernameOK($username)
        {
			 global $lu_admin;
			 
			 $aktive_user  = $lu_admin->getUsers();
			 
			  // berprfung ob username schon benutzt ist.
			  // daten werden aus dem array $aktive_user ausgelesen.

            foreach ($aktive_user as $a) {

            		if ($username == $a[handle]){
                			return false;
            		}
			 	}

                	return true;
        }

        function process(){
			global $lu_admin;
			$username=$_REQUEST['username'];
			$pass1=$_REQUEST['pass1'];

            //$lu_admin->addUser($username, $pass1);
               //$lu_admin->setAdminContainers();


         $user_to_add = array();
         $user_to_add['handle'] = $username;
         $user_to_add['passwd'] = $pass1;
		 	$user_to_add['is_active'] = 0;
		 	$user_to_add['vorname'] = $_REQUEST['vorname'];
		 


              $userId = $lu_admin->addUser($user_to_add);
			  
			  
			      $data_group = array(
        			'perm_user_id' =>  $userId,
        			'group_id' => $_REQUEST['gruppen']
       				);
			  
		if($data_group['group_id']){
           $added = $lu_admin->perm->addUserToGroup($data_group);
        }      

              //$lu_admin->perm->addUser($user_to_add);
              //print_r( $user_to_add);

         header('Location: '.$_SERVER["SCRIPT_NAME"]);
        }
    }

