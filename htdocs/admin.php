<?php
session_start();
include 'set_env.php';
include 'locale/german.php';
//include 'horde/kronolith.php';


$smarty->assign('Page_Title', "Allevo Administration");
$smarty->assign('css_header', array());
$smarty->assign('javascript_header', array());											
$smarty->assign('metatag_header', array(
                     'author'   => "Roman Stachura",
                     'keywords' => "TML, Meta-Informationen, Suchprogramme, HTTP-Protokoll"
                     )
                  );

   if (!$LU->checkRightLevel('USERMANAGEMENT_RIGHT_EDIT', (int)$LU->getProperty('owner_user_id'), (int)$LU->getProperty('owner_group_id') )) {

   }else{
        
   }
	

   if (!$LU->checkRightLevel('TREEMANAGER_EDITCONTENT', (int)$LU->getProperty('owner_user_id'), (int)$LU->getProperty('owner_group_id'))){
         
         
   }else{


      $sql = 'SELECT t1.uuid AS object_name,  t1.title AS title 
              From content as t1
              LEFT OUTER JOIN nested_set AS t2 
              ON t1.uuid = t2.uuid
              WHERE t2.id IS null
              GROUP BY object_name
              ORDER BY t1.title DESC
              LIMIT 0, 30 
              ';
      $res = $mdb2->query($sql);

      while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
         $google_docs[] = $row;
      }

       $tager = new Content_Tagger($mdb2);  
         $args['offset'] = 0;
         $args['limit']  = "";
         $args['q']      = "";
         $args['type']   = "tag";
         $args['output'] = "array";
         
      $global_tags = $tager->search($args);
      
      $smarty->assign('global_tags', $global_tags);			
      $smarty->assign('google_docs', $google_docs);
		
}



if(!$LU->isLoggedIn()){


   if(IS_AJAX){

      header("Content-Type: application/json");
      echo json_encode($_response);
      exit;
   }

	$smarty->display('finishers/index.tpl');
	
}else{

   include 'liveuser/index.php';
   
   $tager = new Content_Tagger($mdb2);
   $args['offset'] = 0;
   $args['limit']  = 100;
   $args['typeId'] = 'bilder';
   $smarty->assign('bilder_tags', $tager->getTags($args), true);




	if(IS_AJAX){

		ob_start();
		//var_dump($_REQUEST);
		print_r($_REQUEST);
		$ajax_request = ob_get_contents();
		ob_end_clean();
		
		$logger->log('ajax requests '. $ajax_request, PEAR_LOG_DEBUG );
		$logger->log('ajax respond '. serialize($respond), PEAR_LOG_DEBUG );
		
		header("Content-Type: application/json");
		echo json_encode($_response);

		exit;
	}

	$smarty->display('admin/index.tpl');
}

?>
