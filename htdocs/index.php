<?php

session_start();




    include 'set_env.php';
    include 'locale/german.php';
	

    
    
	$dir_img = $_allevo_config['app_root'].'htdocs/'.$_allevo_config['relativ_upload_path'];
	$server_bilder = check_dir($dir_img);
	

    $_offset = array_key_exists('offset', $_GET)   ? (int) trim($_GET['offset'])  : 0 ;
    $_offset = array_key_exists('offset', $_POST)  ? (int) trim($_POST['offset']) : $_offset;
    $_offset = preg_match($pattern['int'], $_offset )  ? $_offset   : 0;
    
    $_limit = array_key_exists('limit', $_GET)   ? (int) trim($_GET['limit'])  : 10 ;
    $_limit = array_key_exists('limit', $_POST)  ? (int) trim($_POST['limit']) : $_limit;
    $_limit = preg_match($pattern['int'], $_limit )  ? $_limit   : 10;
    
    $eventUID  = array_key_exists('eventUID', $_GET)   ? (string) trim($_GET['eventUID']) : false ;
    $eventUID  = preg_match($pattern['eventUID'], $eventUID)  ? $eventUID  : false;
    
    $turbaID  = array_key_exists('turbaID', $_GET)   ? (string) trim($_GET['turbaID']) : false ;
    $turbaID  = preg_match($pattern['turbaID'], $turbaID)  ? $turbaID  : false;

    $date     = array_key_exists('date', $_GET)   ? (string) trim($_GET['date'])   : false ;
    $date     = array_key_exists('date', $_POST)  ? (string) trim($_POST['date'])  : $date;
    $date     = filter_var($date , FILTER_SANITIZE_STRING); 
    
    $start = array_key_exists('start', $_GET)   ? (int) trim($_GET['start']): mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $start = preg_match($pattern['int_kronolith'], $start)  ? $start : mktime(0, 0, 0, date("m"), date("d"), date("Y"));

    $stop = array_key_exists('stop', $_GET)   ? (int) trim($_GET['stop']): mktime(0, 0, 0, date("m"), date("d")+6, date("Y"));
    $stop = preg_match($pattern['int_kronolith'], $stop )  ? $stop : mktime(0, 0, 0, date("m"), date("d")+6, date("Y"));
    

    include 'Horde/kronolith.php';
	  

if($_REQUEST['cmd']== home or $_REQUEST['id']==1 ){

     unset($_SESSION['level_1']);
     unset($_SESSION['level_2']);
     unset($_SESSION['level_3']);
     unset($_SESSION['level_4']);
}

if($_REQUEST['id'] ){

}


        function add_querystring_var($url, $key, $value) {
             $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
             $url = substr($url, 0, -1);
             if (strpos($url, '?') === false) return ($url . '?' . $key . '=' . $value);
              else return ($url . '&' . $key . '=' . $value);    
        }
        
        // remove key value for get parameter
        // credit goes to http://www.addedbytes.com/code/querystring-functions/

        function querystring_remove($remove_vars){
            if (!is_array($remove_vars))$remove_vars = array($remove_vars);

            $sep = ini_get('arg_separator.output');
            $qs = "";
            foreach($_GET as $k => $v) {
                if(!in_array($k, $remove_vars))$qs.= "$k=".urlencode($v).$sep;
            }
            
            if(substr($qs, 0, -1))return "http://".$_SERVER['HTTP_HOST'].$_SERVER["URL"].'?'.substr($qs, 0, -1);
            else return "http://".$_SERVER['HTTP_HOST'].$_SERVER["URL"];
        }




if($eventUID){
get_kronolith_event($eventUID);

}



function get_turba_contact( $turbaID = false){

	global $smarty;
	global $_allevo_config;
	global $rpc_options; 
	global $rpc_endpoint;   

	// Horde Turba API Call --> contacts.getContact
    $turba_sources = $_allevo_config['horde']['module']['turba']['sources'];
    $rpc_parameters_turba = array(
        'source' => $turba_sources['0'],
        'objectId' => $turbaID
    );    

	//print_r($rpc_parameters_turba);
	
	try {

		$http_client = new Horde_Http_Client($rpc_options);
		
		$result  = Horde_Rpc::request(
			'jsonrpc',
			$rpc_endpoint,
			'contacts.getContact',
			$http_client,
			$rpc_parameters_turba
		);
	
	}catch (Exception $e) {	

		echo 'Caught exception: ',  $e->getMessage(), "\n";

	}
 

    // wenn der event gelöscht oder nicht auffindbar ist, dann permanent redirect auf index.php
    if (is_a($result, 'PEAR_Error'))header("Location: http://".$_SERVER['HTTP_HOST']."/index.php",TRUE,301);
    
    
	if (!is_a($result->result, 'PEAR_Error') and is_object($result->result) ){
      
         
         $contact = $result->result;
         $contactUID = $result->result->__uid;
         
         //print_r($contact);

		// fill php buffer. --> $kronolith_event_buffer
		ob_start();     
		echo'
		
		<img src="http://images.finishers.ch/?turbaID='.$turbaID.'" alt="'.$contact->name.'">
		<br>
			<dl>
			<dt>Name: </dt>
			<dd>'.$contact->name.'</dd>';
        if ($contact->vorstand || $contact->trainer || $contact->beisitzer){
		echo'<dt>Funktion:</dt>
			 <dd>'.$contact->vorstand.' '.$contact->trainer.' '.$contact->beisitzer.'</dd>';
        }
		echo'
			<dt>Tel:</dt>
			<dd>'.$contact->cellPhone.'</dd>
			<dt>E-mail:</dt>
			<dd>'.$contact->email.'</dd>
			<dt>Info:</dt>
			<dd>'.$contact->notes.'</dd>
			</dl>
		';
		$turba_contact_buffer = ob_get_contents();
		
		ob_end_clean();
    }
     $smarty->assign('turba_contact', $turba_contact_buffer, true);
}

    


if ($turbaID){

get_turba_contact( $turbaID);

}








    $addSQL = array( 'where' => 'active = 1');

    $id = preg_match($pattern['int'], $_GET['id'])  ? (int)$_GET['id']  : $standard_id;
	
    $current_node = $NestedSets->pickNode($id, true);
    
   if($navigation_1 = $NestedSets->getChildren(1, true, true, false, $addSQL)){
        foreach($navigation_1 as $key => $value){
            // Exclude the news childrens from the navigation
            if( $value['name'] !== 'News' AND $children = $NestedSets->getChildren($value['id'], true, true, false, $addSQL)){
                $navigation_1[$key]['subnavigation'] = $children;
            }
        }
    }
    
    

    


    if(is_array($current_node)){
            
        $breadcrumb = $NestedSets->getParents($id, true, true, false, $addSQL);
        $smarty->assign('breadcrumb',  $breadcrumb, true);
            
        // Vorstand zusammen fassen 
        if($current_node['name']=='Vorstand' ){
            if($vorstand_navigation = $NestedSets->getChildren($current_node['id'], true, true, false, $addSQL)){
                foreach($vorstand_navigation as $key => $value ){
                    $vorstand[$key] =& $mdb2->queryRow('SELECT * FROM nested_set_content WHERE nested_set_id ='.$value['id'].'');
                    $vorstand[$key] = unserialize_content($vorstand[$key]);
                    //$h2tags = preg_match_all("/(<h2.*>)(.*)(<\/h2>)/U",$vorstand[$key]['content'],$patterns);
                    //$vorstand[$key]['slogan'] = $patterns[2][0];
                }
                $smarty->assign('vorstand',  $vorstand, true);
            }
        }
                
        // news fischen //
		if($current_node['name']=='News' ){
					
			/* 
				$addSQL = array(
                    'cols' => 'tb2.col2, tb2.col3',         // Additional tables/columns
                    'join' => 'LEFT JOIN tb1 USING(STRID)', // Join statement
                    'where' => 'A=B' AND 'C=D',             // Where statement without 'WHERE' OR 'AND' in front
                    'append' => 'GROUP by tb1.STRID'        // Group condition
					);      
					// @param string $type The type of SQL.  Can be 'cols', 'join', or 'append'.
			*/
			
			
		
            if($news_navigation = $NestedSets->getChildren($current_node['id'], true, true, false, $addSQL)){
				$count = 0;
                foreach($news_navigation as $key => $value ){
					if($count < 6){
						$news[$key]=& $mdb2->queryRow('SELECT * FROM nested_set_content WHERE nested_set_id ='.$value['id'].'');                 
						$news[$key] = unserialize_content($news[$key]);
					}
					$count = $count+1;					
                }
				
                $smarty->assign('news', $news, true);
            }
        }        
     

        if($navigation = $NestedSets->getChildren($id, true, true, false, $addSQL)){
            // get the children
            foreach($navigation as $key => $value){
                    
				// check for news in the subnavigation //
                if($value['name']=='News' ){ 
					if($news_navigation = $NestedSets->getChildren($value['id'], true, true, false, $addSQL)){
						$count = 0;
                        foreach($news_navigation as $key2 => $value2 ){
							if($count < 6){
								$news[$key2]=& $mdb2->queryRow('SELECT * FROM nested_set_content WHERE nested_set_id ='.$value2['id'].'');                 
								$news[$key2] = unserialize_content($news[$key2]); 
							}
						$count = $count+1;							
                        }
	
                        $smarty->assign('news', $news, true);
                    }
                }    
                            
                // Animation --> slideshow
                if($value['name']=='Animation'){
                    if($children = $NestedSets->getChildren($value['id'], true, true, false, $addSQL)){
                        foreach($children as $key => $value ){
                            $animation[$key] =& $mdb2->queryRow('SELECT content, title  FROM nested_set_content WHERE nested_set_id ='.$value['id'].'');
                            $animation[$key] = unserialize_content($animation[$key]);                                                                                              
                        }
						shuffle($animation);
                        $smarty->assign('animation', $animation, true);
                    }
                }     
            }    
        }
	}

	
	
    if(is_array($breadcrumb) ){
            
        foreach($breadcrumb as $key => $value ){
		
		
		
			if( $value['name'] == 'Training'){
			
			
				$kronolith_properties = array(
					 //'name'
					'displayname'									
				);    

				$rpc_parameters = array(
					'path' => 'kronolith/admin',
					'options' => $kronolith_properties                     
				);  

								
				try {

					$http_client = new Horde_Http_Client($rpc_options);
					
					$kronolith_browse = Horde_Rpc::request(
						'jsonrpc',
						$GLOBALS['rpc_endpoint'],
						'calendar.browse',
						$http_client,
						$rpc_parameters
					);
					
					foreach($kronolith_browse->result as $key_kronolith => $value_kronolith){

						$id_kronolith = explode("/", $key_kronolith);

						if(!strpbrk($value_kronolith->displayname, '.') AND $current_node['name'] == urldecode($value_kronolith->displayname) ){
							
							$smarty->assign('calendar_id', $id_kronolith['2'], true);
							
						}
					}
					
				}catch (Exception $e) {	
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			}
			
			
		
            switch ($value['level']) {
               case '1':
                    $_SESSION['level_1'] = $key;
                    break;
               case '2':
                    $_SESSION['level_2'] = $key; 
                    $navigation_2 = $NestedSets->getChildren($key, true, true, false, $addSQL);
                    break;
               case '3':
                    $_SESSION['level_3'] = $key; 
                    $sub_navigation_siblings = $NestedSets->getSiblings($current_node['id'], true, true, false, $addSQL);
                    $navigation_3 = $NestedSets->getChildren($key, true, true, false, $addSQL);    
                    break;
               case '4':
                    $_SESSION['level_4'] = $key;
                    $navigation_4 = $NestedSets->getChildren($key, true, true, false, $addSQL);    
                    break;
               case '5':
                    $_SESSION['level_5'] = $key;
                    $navigation_4 = $NestedSets->getChildren($key, true, true, false, $addSQL);     
                    break;
               case '6':
                    $_SESSION['level_6'] = $key;
                    $navigation_4 = $NestedSets->getChildren($key, true, true, false, $addSQL);    
                    break;      
            }
        }
	
    }
            
    if($current_node['level']){
            
		switch ($current_node['level']) {
            case '1':
                $_SESSION['level_1']=$current_node['id'];
                unset($_SESSION['level_2']);
                unset($_SESSION['level_3']);
                unset($_SESSION['level_4']);
                unset($_SESSION['level_5']);
                unset($_SESSION['level_6']);
                break;
            case '2':
                $_SESSION['level_2']=$current_node['id'];
                $navigation_2 = $NestedSets->getChildren($current_node['id'], true, true, false, $addSQL);
                unset($_SESSION['level_3']);
                unset($_SESSION['level_4']);    
                unset($_SESSION['level_5']);
                unset($_SESSION['level_6']);
				break;
            case '3':
                $_SESSION['level_3']=$current_node['id'];
                $navigation_3 = $NestedSets->getChildren($current_node['id'], true, true, false, $addSQL);
                unset($_SESSION['level_4']);
                unset($_SESSION['level_5']);
                unset($_SESSION['level_6']);    
                break;
            case '4':
                $_SESSION['level_4']=$current_node['id'];
                $navigation_4 = $NestedSets->getChildren($current_node['id'], true, true, false, $addSQL);  
                if($navigation_4){
                    foreach($navigation_4 as $key => $value ){
                        $carousel_content[$key]=& $mdb2->queryRow('SELECT * FROM nested_set_content WHERE nested_set_id = '.$value['id'], '', MDB2_FETCHMODE_ASSOC);
                    }
                }
                unset($_SESSION['level_5']);
                unset($_SESSION['level_6']);
                break;    
            case '5':
                $_SESSION['level_5']=$current_node['id'];
                $navigation_5 = $NestedSets->getChildren($current_node['id'], true, true, false, $addSQL);
                unset($_SESSION['level_6']);
                break;              
        }
            
    }
            

function prepareNavigation( $navigation=array()){


    if (!empty($navigation)){
    
        foreach($navigation as $key => $value){
        
            //** charset iso or utf-8 **//
            //$navigation[$key]['name'] = utf8_decode($value['name']);
        
            if ( !empty($value['query_string']) ){
                $query_string = unserialize($value['query_string']);
            
            }else{
              $query_string = array('id'=>$value['id'] );
            } 

            if (!is_array($query_string)){
                $query_string = array('id'=>$value['id'] );
            }else{
                $navigation[$key]['query_string'] = http_build_query($query_string);
            } 
        }
    }
    

    return $navigation;
    

}



$smarty->assign('navigation_1', prepareNavigation($navigation_1), true);
$smarty->assign('navigation_2', prepareNavigation($navigation_2), true);
$smarty->assign('navigation_3', prepareNavigation($navigation_3), true);
$smarty->assign('navigation_4', prepareNavigation($navigation_4), true);
$smarty->assign('navigation_5', prepareNavigation($navigation_5), true);
$smarty->assign('navigation_6', prepareNavigation($navigation_6), true);

$smarty->assign('navigation_siblings', prepareNavigation($sub_navigation_siblings), true);

$smarty->assign('carousel_content', $carousel_content, true);


$link_id =&$mdb2->queryOne('SELECT link FROM nested_set WHERE id = '.$mdb2->quote($id));



$content =& $mdb2->queryRow('SELECT * FROM nested_set_content WHERE nested_set_id = '.$id, '', MDB2_FETCHMODE_ASSOC);



         if ($content['title'] == "Mitglieder"){
            

            
                $path = 'turba/admin/LofVYGVu5LFPRRgUSwaR4uA';
                $turba_properties = array(
                                    'name', 
                                    'funktion'    
                            );    

                $rpc_parameters = array(
                                    'path' => $path, 
                                    'properties' => $turba_properties
                            );  
				try {

					$http_client = new Horde_Http_Client($rpc_options);
				
					$turba_browse = Horde_Rpc::request(
						'jsonrpc',
						$GLOBALS['rpc_endpoint'],
						'contacts.browse',
						$http_client,
						$rpc_parameters
						);
			
				}catch (Exception $e) {	

					echo 'Caught exception: ',  $e->getMessage(), "\n";

				}	

 
            
                ob_start();				
                echo'<ul id="addressBook">';

                foreach($turba_browse->result as $key => $contact){
                      
                        $dataTags = '';
                      
                     if( is_string($contact->vorstand)){
                        $dataTags[0]  = "vorstand";
                      }
                      if(is_string($contact->beisitzer)){
                        $dataTags[1]  = "beisitzer";
                      }
                     if(is_string($contact->trainer)){
                        $dataTags[2]  = "trainer";
                      }
                     if($contact->anrede){
                        $dataTags[3]  = $contact->anrede;
                     }
                     if($contact->homeCity){
                        $dataTags[4]  = $contact->homeCity;
                     }
                     if($contact->memberstatus){
                     
                     switch ($contact->memberstatus) {
                            case 'P':
                                        $dataTags[5]  = 'passiv';
                                     break;
                            case 'Eintritt':
                                        $dataTags[5]  = 'neu eintritt';
                                     break;
                            case 'Austritt':
                                        $dataTags[5]  = 'austritt';
                                     break;
                            case 'G':
                                        $dataTags[5]  = 'gönner';
                                     break;
                            case 'E':
                                        $dataTags[5]  = 'ehrenmitglied';  
                                     break;
                            case 'A':
                                        $dataTags[5]  = 'aktiv';
                                     break;    
                            case 'W':
                                        $dataTags[5]  = 'walker';
                                     break;               
                            case 'F':
                                        $dataTags[5]  = 'familie';
                                      break;
                            case 'J':
                                        $dataTags[5]  = 'junior';
                                     break;                   
                            }
                     }
                     
                    if($contact->name){
                        $dataTags[6]  = $contact->name;
                    }
					
                    $path = explode("/", $key);    
                      
              echo' <li class="contact" data-tags="'.implode(",", $dataTags).' ">
						<a href="?turbaID='.end($path).'">'.$contact->name.'</a>
                    </li>';
                            
                    } // end foreach contactrs
        
echo' </ul>';      
        
    
        
    
     $turba_contacts_buffer = ob_get_contents();
     ob_end_clean();




     $smarty->assign('turba_contacts', $turba_contacts_buffer, true);        





            
            }


if ($content['title'] == "Resultate"){

include('fusiontable/contact.php');


$cols = array('count()', 'Email', 'Name', 'Vorname', 'Sportart');
$condition = "group by Email,Name, Vorname, Sportart";
$table_data = $ftclient->query(SQLBuilder::select($tableid, $cols, $condition));


//$table_info = $ftclient->query(SQLBuilder::describeTable(1065078));

//$cols1 = array( 'count()','Email', 'Timestamp', 'MAXIMUM(\'8-18 Junioren\')', 'MAXIMUM(\'19-30 Hauptklasse\')', 'sum(\'31-39 Altersklasse\')', 'sum(\'40-x Altersklasse\')', Rang, Wettkampftitel);
//$condition1 = "WHERE 'Rang' <=10 AND 'Wettkämpfe'= 'Wettkampf'  group by Email, Timestamp, Rang, 'Wettkampftitel'";
//$test = $ftclient->query(SQLBuilder::select('1065078', $cols1, $condition1));

//print_r($test);

if($table_data){

    $table_data = explode("\n", $table_data);

        foreach($table_data as $key => $value){
          $data_row = explode(",", $value);

            if($data_row['0'] AND $key !=0 ){
    
                 $array_sportart[$key] = $data_row['4'];

                 $array_athlets[$key] = array(
                    'email'=>$data_row['1'], 
                    'name' => $data_row['2'],
                    'vorname' => $data_row['3']
                );

            }

        }





    $array_sportart_result = array_unique($array_sportart);
    $array_athlets_result  = array(); 

    foreach($array_athlets as $d) { 
       $array_athlets_result[md5(serialize($d))] = $d; 
    } 

        
        for ($i = 1989; $i <= date("Y"); $i++) {
                 $saison[]= $i;
        }


     $smarty->assign('sliderSaisonValueStart', date("Y", $start)-1, true);
     $smarty->assign('sliderSaisonValueEnd', date("Y", $stop), true);
      
      
     $smarty->assign('sliderSaisonStart', current($saison), true);
     $smarty->assign('sliderSaisonEnd', end($saison), true);
      
      
     $smarty->assign('dashboard_athleten', $array_athlets_result, true);
     $smarty->assign('dashboard_sportarten', $array_sportart_result, true);


}





}





//** charset **//
//header('content-type: text/html; charset: ISO-8859-1');
//$content['content'] = utf8_decode($content['content']);
//$content['content2'] = utf8_decode($content['content2']);
//$content['title'] = utf8_decode($content['title']);


// print_r($_SESSION);


if ( empty($content))header("Location: /index.php",TRUE,301);



$smarty->assign('content',  unserialize_content($content, true), true);





    if(IS_AJAX){
    

        if(!$LU->isLoggedIn()){
        }else{
            include 'liveuser/index.php';
        }



    

        ob_start();
        //var_dump($_REQUEST);
        print_r($_REQUEST);
        $ajax_request = ob_get_contents();
        ob_end_clean();
        
        $logger->log('ajax requests '. $ajax_request, PEAR_LOG_DEBUG );
        $logger->log('ajax respond '. serialize($_response), PEAR_LOG_DEBUG );
        
    
        header('Expires: Mon, 25 Dec 1976 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-type: application/json; charset=utf-8');

        echo json_encode($_response);
        exit;
    }




if(!$LU->isLoggedIn()){

    //include 'allevo/libs/forms/login.php';

    $smarty->display('finishers/index.tpl');
    
}else{


    //include 'allevo/libs/forms/login.php';
    include 'liveuser/index.php';

    $smarty->display('finishers/index.tpl');
}

?>