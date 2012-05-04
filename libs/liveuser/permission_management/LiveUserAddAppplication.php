<?php

class Live_User_add_Appplication extends HTML_QuickForm{
  


        function Live_User_add_Appplication(){

        parent::HTML_QuickForm('Live_User_add_Appplication');

        $this->addElement('header', null, 'LiveUserAddApplication');
		  $this->addElement('text', 'application', 'Name', array('size' => 20, 'maxlength' => 255));

		  $this->addElement('hidden', 'cmd', 'AddApplication');

        $group = array();
        $group[] =& $this->addElement('reset', 'abbruch', 'Clear');
        $group[] =& $this->addElement('submit', 'LiveUserAddApplication', 'Abschicken');

        $this->addGroup($group, 'buttons');

        // costum rules

        $this->registerRule('checkApplication', 'callback', 'ApplicationRegistered', &$this);

        $this->addRule('application', 'What kind of Application would you like to register?', 'required');
        $this->addRule('application', 'Diese Anwendung ist schon registriert <br/> Wählen sie einen anderen Namen', 'checkApplication');

       }
       
       

       function ApplicationRegistered($application){

              global  $lu_admin;

              //überprüfen ob die Applikation schon registriert ist.

              $LiveUserApplications=$lu_admin->perm->getApplications();

			foreach ($LiveUserApplications as $a) {

            		        if ($application == $a['application_define_name']){
           			   return false;
            		        }
                        }

                	return true;

      }

      function process_LiveUserAddApplication($application){

        global $lu_admin;


        $data=array(
                    'application_define_name' => $application['application']
        );


        // add an application
        if(!empty($data[application_define_name])){
        $lu_admin->perm->addApplication($data);
        }
        header('Location: '.$_SERVER["SCRIPT_NAME"]);

      }

}


?>