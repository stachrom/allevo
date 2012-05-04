<?php



class Live_User_Edit_Area extends HTML_QuickForm{



        function Live_User_Edit_Area(){
          

          
                         $get_rights = array(
                               'fields' => array('right_define_name',  'right_id' ),
                               'filters' => array('area_id' => $_REQUEST['AreaId'] )
                         );

                          global  $lu_admin;

                         $currentRights = $lu_admin->perm->getRights($get_rights);

                         foreach($currentRights as $rechte ){

                                   //print_r($rechte);
                                  $right_id[$rechte['right_define_name']] = array('1', $rechte['right_id'], $rechte['right_define_name'] );
                                  $rechte_id[] = array('1', $rechte['right_id'], $rechte['right_define_name']);
                         }





                       //print_r($right_id);


        # Quickform :


        parent::HTML_QuickForm('Live_User_Edit_Area');


        $this->addElement('header', null, 'LiveUserAddArea');


	$this->addElement('text', 'area_name', 'Modul Name:', array('size' => 20, 'maxlength' => 255));


        $this->addElement('hidden', 'AreaId',    $_REQUEST['AreaId'] );
        $this->addElement('hidden', 'AreaName', $_REQUEST['AreaName'] );
        $this->addElement('hidden', 'cmd', 'EditArea' );


        $this-> addElement('checkbox', 'view', 'betrachten', null);
        $this-> addElement('checkbox', 'create', 'erstellen', null);
        $this-> addElement('checkbox', 'edit', 'bearbeiten', null);
        $this-> addElement('checkbox', 'delete', 'löschen', null);

        $this->addElement('hidden', 'view_', $right_id['view'][1] );
        $this->addElement('hidden', 'create_', $right_id['create'][1] );
        $this->addElement('hidden', 'edit_', $right_id['edit'][1] );
        $this->addElement('hidden', 'delete_', $right_id['delete'][1] );


        # QuckformCheckbox user_right


        $user=$lu_admin->getUsers();
        $right_defaults=array();
        $hidden_defaults=array();


    if(!empty($rechte_id)) {

         foreach($user as $result_user){

             $params = array(
                'filters' => array(
                          'perm_user_id' => $result_user['perm_user_id'],
                          'area_id' => $_REQUEST['AreaId']
                          )
                 );

             $default_rights=$lu_admin->perm->getRights($params);

             //print_r($default_rights);

             foreach($rechte_id as $key => $result_rechte){


                #gruppen pro user mit den jeweiligen rechten erstellen hidden

                 $group_hidden[$result_user['perm_user_id']][] =& $this->createElement('hidden', $result_rechte['1'], null);
                 $this->addGroup( $group_hidden[$result_user['perm_user_id']], $result_user['perm_user_id'].hidden, null);


                 #gruppen pro user mit den jeweiligen rechten erstellen checkbox
                 $group[$result_user['perm_user_id']][] =& $this->createElement('checkbox', $result_rechte['1'], null ,$result_rechte['2']);
                 $this->addGroup( $group[$result_user['perm_user_id']], $result_user['perm_user_id'], null);

                 foreach($default_rights as $value){

                     #default Werte für die rechte einfüllen
                     $right_defaults[$result_user['perm_user_id']][$value['right_id']]=true;
                     $hidden_defaults[$result_user['perm_user_id'].hidden][$value['right_id']]=true;
                 } // ende foreach für die default rechte.
             }// ende foreach Gruppenbildung der Rechte.
         }// ende foreach über die anzahl user.
         
         $setDefaults= array(
		            area_name => $_GET['AreaName'],
		            area_id   => $_GET['AreaId'],
                            create    => $right_id['create'][0],
	                    edit      => $right_id['edit'][0],
		            delete    => $right_id['delete'][0],
		            view      => $right_id['view'][0]
                          );



          

          $result = $setDefaults + $right_defaults + $hidden_defaults;


                 // print_r($right_defaults);

    }// ende if verfügbarkeit von  array $rechte_id


          //print_r($setDefaults);


        $this->addElement('submit', 'saveUserRight', 'Speichern');
        $this->addElement('submit', 'abbrechenArea', 'Abbrechen');
        $this->addElement('submit', 'updateArea', 'Speichern');
        $this->addElement('submit', 'deleteArea', 'Löschen', 'onclick="return confirmLink( this, \' das Modul:'.$_GET['area_name'].'\n löschen?\')" ');


         # defaults der


           $this->setDefaults(
                      $result
                          );



        // costum rules

        //$this->registerRule('checkApplication', 'callback', 'ApplicationRegistered', &$this);

        //$this->addRule('area_name', 'Please provide an application name', 'required');
        //$this->addRule('area_name', 'Diese Anwendung ist schon registriert <br/> Wählen sie einen anderen Namen', 'checkApplication');

       }



       function ApplicationRegistered($application){

              global  $lu_admin;

              //überprüfen ob die Applikation schon registriert ist.

              //$LiveUserApplications=$lu_admin->perm->getApplications();

			foreach ($LiveUserApplications as $a) {

            		        if ( isset($POST['updateApplication']) and $application == $a['application_define_name']){
           			   return false;
            		        }
                        }
                	return true;
      }

function process_LiveUserEditArea($area){


        global $lu_admin;


        if($area['updateArea']){

            if(empty($_POST['edit_'])and $_POST['edit']==1){

                $Area_Right_edit=array(
                  'area_id' => $_POST['AreaId'],
                  'right_define_name'   => 'edit',
                  'has_implied' => '0'
                  );

                $lu_admin->perm->addRight($Area_Right_edit);


            }elseif(!empty($_POST['edit_'])and empty($_POST['edit']) ){

                $Area_Right_edit=array(
                  'right_id'   => $_POST['edit_']
                  );

                $lu_admin->perm->removeRight($Area_Right_edit);
            }
            
            
            
            if(empty($_POST['view_'])and $_POST['view']==1){

                $Area_Right_edit=array(
                  'area_id' => $_POST[AreaId],
                  'right_define_name'   => 'view',
                  'has_implied' => '0'
                  );

                $lu_admin->perm->addRight($Area_Right_edit);


            }elseif(!empty($_POST['view_'])and empty($_POST['view']) ){

                $Area_Right_edit=array(
                  'right_id'   => $_POST['view_']
                  );

                $lu_admin->perm->removeRight($Area_Right_edit);
            }

            if(empty($_POST['delete_'])and $_POST['delete']==1){

                $addArea_Right_delete=array(
                  'area_id' => $_POST['AreaId'],
                  'right_define_name'   => 'delete',
                  'has_implied' => '0'
                  );

                $lu_admin->perm->addRight($addArea_Right_delete);


            }elseif(!empty($_POST['delete_'])and empty($_POST['delete']) ){

                $Area_Right_delete=array(
                  'right_id'   => $_POST['delete_']
                  );

                $lu_admin->perm->removeRight($Area_Right_delete);
            }

            if(empty($_POST['create_'])and $_POST['create']==1){

                $Area_Right_edit=array(
                  'area_id' => $_POST['AreaId'],
                  'right_define_name'   => 'create',
                  'has_implied' => '0'
                  );

                $lu_admin->perm->addRight($Area_Right_edit);


            }elseif(!empty($_POST['create_'])and empty($_POST['create']) ){

                $Area_Right_edit=array(
                  'right_id'   => $_POST['create_']
                  );

                $lu_admin->perm->removeRight($Area_Right_edit);
            }

           header('Location: '.$_SERVER['SCRIPT_NAME'].'?AreaId='.$_POST['AreaId'].'&cmd=EditArea&AreaName='.$_POST['AreaName']);


      }elseif($area['deleteArea']){

                $filters=array(
                  'area_id'   => $_POST['AreaId']
                );

                $lu_admin->perm->removeArea( $filters);
                              
                header('Location: '.$_SERVER["SCRIPT_NAME"]);

      }elseif($area['abbrechenArea']){

                header('Location: '.$_SERVER["SCRIPT_NAME"]);

      }elseif($area['saveUserRight']){


        //print_r($area);


        foreach($area as $key => $value){
              # user rechte gewähren
              if(is_array($value) and is_int($key)){

                   $user_id=$key;

                  foreach($value as $key_temp => $user_right_checked){

                    if( $area[$user_id."hidden"][$key_temp] != 1){

                        $data = array(
                         'perm_user_id' => $user_id,
                         'right_id' => $key_temp,
                         'right_level' => 2
                         );

                        $lu_admin->perm->grantUserRight($data);;
                    }// ende if
                  }// ende foreach --> loops user rechte
                
                }
                # user rechte entziehen
                if(is_array($value) and is_string($key)){

                    $user_id = preg_replace("%hidden%", '', $key);

                    foreach($value as $key_temp => $user_right_checked){

                      if( $area[$user_id."hidden"][$key_temp] == 1 AND $area[$user_id][$key_temp] != 1){

                        $remove_grant_data = array(
                                  'right_id' =>  $key_temp,
                                  'perm_user_id'  => $user_id
                         );
                        $lu_admin->perm->revokeUserRight($remove_grant_data);
                      }// ende if
                    }//ende foreach  --> loops user rechte entziehen
                }
        
}


header('Location: '.$_SERVER['SCRIPT_NAME'].'?AreaId='.$_POST['AreaId'].'&cmd=EditArea&AreaName='.$_POST['AreaName']);




      
      }

      



}

}






?>