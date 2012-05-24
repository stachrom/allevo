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

   $date     = array_key_exists('date', $_GET)   ? (string) trim($_GET['date'])   : false ;
   $date     = array_key_exists('date', $_POST)  ? (string) trim($_POST['date'])  : $date;
   $date     = filter_var($date , FILTER_SANITIZE_STRING); 
 
   $start = array_key_exists('start', $_GET)   ? (int) trim($_GET['start']): mktime(0, 0, 0, date("m"), date("d"), date("Y"));
   $start = preg_match($pattern['int_kronolith'], $start)  ? $start : mktime(0, 0, 0, date("m"), date("d"), date("Y"));

   $stop = array_key_exists('stop', $_GET)   ? (int) trim($_GET['stop']): mktime(0, 0, 0, date("m"), date("d")+6, date("Y"));
   $stop = preg_match($pattern['int_kronolith'], $stop )  ? $stop : mktime(0, 0, 0, date("m"), date("d")+6, date("Y"));
   
   $addSQL        = array( 'where' => 'active = 1');
   $id            = preg_match($pattern['int'], $_GET['id'])  ? (int)$_GET['id']  : $standard_id;
   $current_node  = $NestedSets->pickNode($id, true);
 

   if($_REQUEST['cmd']== home or $_REQUEST['id']==1 ){
      unset($_SESSION['level_1']);
      unset($_SESSION['level_2']);
      unset($_SESSION['level_3']);
      unset($_SESSION['level_4']);
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

   if($navigation_1 = $NestedSets->getChildren(1, true, true, false, $addSQL)){
  
        foreach($navigation_1 as $key => $value){
            // Exclude the news childrens from the sub-navigation
            /*
            if( $value['name'] !== 'News' AND $children = $NestedSets->getChildren($value['id'], true, true, false, $addSQL)){
                $navigation_1[$key]['subnavigation'] = $children;
            }
             */
             
            // BGPictures --> slideshow
            if($value['name']=='Backgroundpictures'){
               if($children = $NestedSets->getChildren($value['id'], true, true, false, $addSQL)){
                        foreach($children as $key => $value ){
                            $BGPictures[$key] =& $mdb2->queryRow('SELECT sidepictures  FROM nested_set_content WHERE nested_set_id ='.$value['id'].'');  
                            $BGPictures[$key] = unserialize_content($BGPictures[$key]);  
                        }
                        
						shuffle($BGPictures);
                  $smarty->assign('BGPictures', $BGPictures, true);
               }
            }               
        }
   }

   if(!empty($current_node)){

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
               unset($_SESSION['level_5']);
               unset($_SESSION['level_6']);
               break;    
            case '5':
                $_SESSION['level_5']=$current_node['id'];
                $navigation_5 = $NestedSets->getChildren($current_node['id'], true, true, false, $addSQL);
                unset($_SESSION['level_6']);
                break;              
      }
      
      
     
      if($breadcrumb = $NestedSets->getParents($id, true, true, false, $addSQL)){ 
         $smarty->assign('breadcrumb',  $breadcrumb, true);
         
         foreach($breadcrumb as $key => $value){

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
                    $navigation_3 = $NestedSets->getChildren($key, true, true, false, $addSQL);    
                    break;
               case '4':
                    $_SESSION['level_4'] = $key;
                    $navigation_4 = $NestedSets->getChildren($key, true, true, false, $addSQL);    
                    break;
               case '5':
                    $_SESSION['level_5'] = $key;
                    $navigation_5 = $NestedSets->getChildren($key, true, true, false, $addSQL);     
                    break;
               case '6':
                    $_SESSION['level_6'] = $key;
                    $navigation_6 = $NestedSets->getChildren($key, true, true, false, $addSQL);    
                    break;      
            }
         }
      
       }
      

   }

   function prepareNavigation($navigation=array()){
   
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
   

   $link_id =& $mdb2->queryOne('SELECT link FROM nested_set WHERE id = '.$mdb2->quote($id));
   $content =& $mdb2->queryRow('SELECT * FROM nested_set_content WHERE nested_set_id = '.$id, '', MDB2_FETCHMODE_ASSOC);


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
   $smarty->display('stachura/index.tpl');
}else{
   //include 'allevo/libs/forms/login.php';
   include 'liveuser/index.php';
   $smarty->display('stachura/index.tpl');
}

?>