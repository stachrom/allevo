﻿<?php

session_start();


include 'set_env.php';
include 'locale/german.php';
	

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



// print_r($_SESSION);


if ( empty($content) && $id!=1 )header("Location: /index.php",TRUE,301);



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