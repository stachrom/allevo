<?php


require_once 'HTML/QuickForm/advmultiselect.php';

class Live_User_edit_Application_Area extends HTML_QuickForm{



        function Live_User_edit_Application_Area(){


        # Quickform :


        parent::HTML_QuickForm('Live_User_edit_Application_Area');


        $this->addElement('header', null, 'LiveUserAddApplication');
        $this->addElement('header', null, 'Advanced Multiple Select: custom layout ');

	$this->addElement('text', 'application_name', 'Application Name:', array('size' => 20, 'maxlength' => 255));
	$this->addElement('text', 'new_area_name', 'Modul Name:', array('size' => 20, 'maxlength' => 255));

        $this->addElement('hidden', 'application_id', $_GET['application_id'] );
        $this->addElement('hidden', 'application_name_hidden', $_GET['application_name'] );
        $this->addElement('hidden', 'cmd', 'EditApplication' );



        # QuckformMultiSelect




        $this->addElement('submit', 'newArea', 'Speichern');
        $this->addElement('submit', 'updateApplication', 'Speichern');
        $this->addElement('submit', 'abbrechenApplication', 'Abbrechen');
        $this->addElement('submit', 'deleteApplication', 'Löschen', 'onclick="return confirmLink( this, \' die Applikation:'.$_GET['application_name'].'\n löschen?\')" ');



        $this->setDefaults(
                 array(
		     application_name => $_GET['application_name'],
		     application_id   => $_GET['application_id']
                 )
        );







        // costum rules

        $this->registerRule('checkApplication', 'callback', 'ApplicationRegistered', &$this);
        $this->addRule('application_name', 'Diese Anwendung ist schon registriert <br/> Wählen sie einen anderen Namen', 'checkApplication');

       }



       function ApplicationRegistered($application){

              global  $lu_admin;

              //überprüfen ob die Applikation schon registriert ist.

              


              $LiveUserApplications=$lu_admin->perm->getApplications();

			foreach ($LiveUserApplications as $a) {

            		        if ( isset($POST['updateApplication']) and $application == $a['application_define_name']){
           			   return false;
            		        }
                        }
                	return true;
      }

      function process_LiveUserEditApplication($application){
        
        


        global $lu_admin;

        $update_apllication_param = array(
                  'application_define_name' => $application['application_name']
        );


        $update_apllication_filter = array(
                  'application_id' => $application['application_id']
        );


      $new_Area=array(
                  'area_define_name' => $application['new_area_name'],
                  'application_id'   => $application['application_id']
        );




        // add an application
        if($application['updateApplication']){

             $lu_admin->perm->updateApplication($update_apllication_param, $update_apllication_filter);
             header('Location: '.$_SERVER["SCRIPT_NAME"]);

        }elseif($application['deleteApplication']){

            if(!empty($update_apllication_filter[application_id])){
                $lu_admin->perm->removeApplication($update_apllication_filter);
            }
             header('Location: '.$_SERVER["SCRIPT_NAME"]);

        }elseif($application['abbrechenApplication']){

             header('Location: '.$_SERVER["SCRIPT_NAME"]);

        }elseif($application['newArea']){

          

               $area_id = $lu_admin->perm->addArea($new_Area);
               

            // fixe rollen für die module verteilen. 

              $Area_Right_edit=array(
                  'area_id' => $area_id,
                  'right_define_name'   => 'edit',
                  'has_implied' => '0'
                  );
              $Area_Right_view=array(
                  'area_id' => $area_id,
                  'right_define_name'   => 'view',
                   'has_implied' => '0'
                  );
              $Area_Right_create=array(
                  'area_id' => $area_id,
                  'right_define_name'   => 'create',
                   'has_implied' => '0'
                  );
              $Area_Right_delete=array(
                  'area_id' => $area_id,
                  'right_define_name'   => 'delete',
                   'has_implied' => '0'
                  );




               $lu_admin->perm->addRight($Area_Right_edit);
               $lu_admin->perm->addRight($Area_Right_view);
               $lu_admin->perm->addRight($Area_Right_create);
               $lu_admin->perm->addRight($Area_Right_delete);





            header('Location: '.$_SERVER["SCRIPT_NAME"].'?application_id='.$application['application_id'].'&cmd=EditApplication&application_name='.$application['application_name_hidden']);


        }

        //header('Location: '.$_SERVER["SCRIPT_NAME"]);

      }

}
?>