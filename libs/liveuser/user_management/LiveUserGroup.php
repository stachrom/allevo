<?php

	//$group_name = 'Test Gruppe';
	//$data = array('group_define_name' => $group_name);
	// $groupId = $lu_admin->perm->addGroup($data);
	
	

			
if($_REQUEST['edit_group']){



$group_id = $_REQUEST['gruppen'];


		



$all_users = $lu_admin->auth->getUsers(array ( 
									'fields' => array ( 'auth_user_id', 'handle'),

                                        )
                                  );


$group_user = $lu_admin->perm->getUsers(array (
							  	'fields' => array ( 'auth_user_id' ),
        						'filters' => array ('group_id' => $group_id)
                                  )
                             );
								  
								  
$group_edit = $lu_admin->perm->getGroups(array (
        						'filters' => array ('group_id' => $group_id)
                                  )
							);								  



}
	
	// eine Gruppe erzeugen

 //$groupId = $lu_admin->perm->addGroup($data);
 

    //$groupId = $lu_admin->perm->updateGroup($data, $filters);
    //$groupId = $lu_admin->perm->addGroup($data);
    //$removed = $lu_admin->perm->removeGroup($filter);

    //$added = $lu_admin->perm->grantGroupRight($data);
	
	

	//$added = $lu_admin->perm->addUserToGroup($data);
	 //$removed = $lu_admin->perm->removeUserFromGroup($filter);
	 
	 
	 
	// $gruppenrechte = $lu_admin->perm->getGroupRight( $data,  $filters);

//print_r($lu_admin);
/*$data = array(	
		'group_define_name' => test,
		'is_active' => 1,
		'owner_user_id' => null,
		'group_type' =>2
		);
		*/
//$groupId = $lu_admin->perm->addGroup($data);

/*    if ($groupId == false)
      {
      echo 'Add_group: error on line: '.__LINE__;
      print_r($lu_admin->getErrors());
      }
    else
      {
      echo 'Group with id ' . $groupId . ' created';
      }

*/

//einen user einer Gruppe zuordnen
//$lu_admin->perm->addUserToGroup($data);

//$groups = $lu_admin->perm->getGroups();




class Live_User_Add_Group extends HTML_QuickForm{
  


        function Live_User_Add_Group(){
		
			global $lu_admin;


    		$LiveUserGroups=$lu_admin->perm->getGroups();
			
			foreach($LiveUserGroups as $a){
				$Liveuser_gruppen[$a['group_id']]= $a['group_define_name'];
			}
		

      parent::HTML_QuickForm('Live_User_Add_Group');

      $this->addElement('header', null, 'LiveUserAddGroup');
		$this->addElement('text', 'group_name', 'Name', array('size' => 20, 'maxlength' => 255));
		

		$this->addElement('hidden', 'active', '1');
		$this->addElement('hidden', 'owner_user_id', null);
		$this->addElement('hidden', 'group_type', 2);
		$this->addElement('select', 'gruppen', 'Gruppen', $Liveuser_gruppen );

		$this->addElement('hidden', 'cmd', 'LiveUseraddGroup');

        $group = array();
        $group[] =& $this->addElement('reset', 'abbruch', 'Abbruch' );
        $group[] =& $this->addElement('submit', 'LiveUseraddGroup', 'Hinzufügen');

        $this->addGroup($group, 'buttons');

		$this->addElement('submit', 'LiveUserdeleteGroup', 'Löschen', 'onclick="return confirmLink( this, \' diese Gruppe löschen?\')"' );
		


        // costum rules

        $this->registerRule('checkGroup', 'callback', 'AddGroup', &$this);

        $this->addRule('group_name', 'Was für eine Gruppe möchten sie hinzufügen?', 'required');
        $this->addRule('group_name', 'Diese Gruppe besteht bereits <br/> Wählen sie eine neue', 'checkGroup');

       }
       
       

       function AddGroup($group){

              global  $lu_admin;

              //überprüfen ob die Gruppe schon aktiviert ist.

              $LiveUserGroups=$lu_admin->perm->getGroups();

			foreach ($LiveUserGroups as $a) {

            		        if ($group == $a['group_define_name']){
           			   return false;
            		        }
                        }

                	return true;

      }

function process_LiveUserAddGroup($group){

    global $lu_admin;

	$data = array(	
		'group_define_name' => $group['group_name'],
		'is_active' => $group['active'],
		'owner_user_id' => $group['owner_user_id'],
		'group_type' => $group['group_type']
		);
	

        // add a group
        if(!empty($group['group_name'])){
        	$groupId = $lu_admin->perm->addGroup($data);		
        }
		// standartrechte hinzufügen
		
		
		 		$data_TREEMANAGER_RIGHT_VIEW = array(
		 						'right_id' => TREEMANAGER_RIGHT_VIEW,
                  		'group_id' => $groupId
                 );
		
				 $data_TREEMANAGER_RIGHT_DELETE = array(
		 						'right_id' => TREEMANAGER_RIGHT_DELETE,
                  		'group_id' => $groupId
                 );
		
				 $data_TREEMANAGER_RIGHT_EDIT = array(
		 						'right_id' => TREEMANAGER_RIGHT_EDIT,
                  		'group_id' => $groupId
                 );
		
				 $data_TREEMANAGER_RIGHT_CREATE= array(
		 						'right_id' => TREEMANAGER_RIGHT_CREATE,
                  		'group_id' => $groupId
                 );
					  
				 $data_TREEMANAGER_RIGHT_MOVE= array(
		 						'right_id' => TREEMANAGER_RIGHT_MOVE,
                  		'group_id' => $groupId
                 );	  
		
		
		
		$added = $lu_admin->perm->grantGroupRight($data_TREEMANAGER_RIGHT_VIEW);
		//$added = $lu_admin->perm->grantGroupRight($data_TREEMANAGER_RIGHT_DELETE);
		//$added = $lu_admin->perm->grantGroupRight($data_TREEMANAGER_RIGHT_MOVE);
		$added = $lu_admin->perm->grantGroupRight($data_TREEMANAGER_RIGHT_EDIT);
		$added = $lu_admin->perm->grantGroupRight($data_TREEMANAGER_RIGHT_CREATE);
     
        
		
		
		

        header('Location: '.$_SERVER["SCRIPT_NAME"]);

      }
	  
function process_LiveUserdeleteGroup($group){

    	global $lu_admin;

	    $filter = array('group_id' => $group['gruppen']);
        $removed = $lu_admin->perm->removeGroup($filter);
     
        header('Location: '.$_SERVER["SCRIPT_NAME"]);

      }	  
	  
	  
	  
	  

}

?>