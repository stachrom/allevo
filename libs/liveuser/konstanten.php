<?php
 	$LiveUserApplications = $lu_admin->perm->getApplications();

        	# Daten für allevo
         foreach($LiveUserApplications as $key => $value){
         	if ( $value['application_define_name'] == 'allevo'){
                 define('APPLICATION_ID',  $value['application_id']);
            }
         }

			$logger->log("allevo id ".APPLICATION_ID, PEAR_LOG_DEBUG );
			
         $allevo_area_param = array(
                  'fields' => array('area_define_name', 'area_id'),
                  'filters' => array('application_id' => APPLICATION_ID)
                   );

			$allevo_area = $lu_admin->perm->getAreas($allevo_area_param);
			
	
		

        // konstanten für module/areas erzeugen: Exporting Rights 
		  // http://www.gvngroup.be/doc/LiveUser_Admin/admin_outputrights.php
		 
		 	$type ='php'; // output Art:  [php] [array] 

			foreach($allevo_area as $key => $value){
			
		  		$options = array(	
					'area'   => $value['area_id'],
					'naming' => LIVEUSER_SECTION_AREA
            );
				
		  		$output = $lu_admin->perm->outputRightsConstants($type, $options);
				

				//######### warning #########//
				
				if(!$output){

						ob_start();
						var_dump($LUA->getErrors);
						$error = ob_get_contents();
						ob_end_clean();

				//$logger->log('Lieveuser Konstanten: '. $error, PEAR_LOG_WARNING );

				}else{
				
				//######### beduging #########//
				
					if($_allevo_config ['log']['enabled']){
				 				 
						$for_type_log ='array';
						
						ob_start();
						var_dump($lu_admin->perm->outputRightsConstants($for_type_log, $options));
						$info = ob_get_contents();
						ob_end_clean();
						
						//$logger->log("Lieveuser Konstanten: ". $info, PEAR_LOG_DEBUG );
					}
				
				
				}
			}


?>