<?php
	session_start();
	include '../set_env.php';
	
	$dir = "htdocs/".$_allevo_config['relativ_upload_path'];
	$durchgang=0;
	
	$action 	= array_key_exists('action', $_GET)   ? (string) trim($_GET['action'])   : false ;
	$action 	= array_key_exists('action', $_POST)  ? (string) trim($_POST['action'])  : $action;
	$action  = preg_match($pattern['alphanumeric'], $action)  ? $action  : '';
	
	
	$tag_id = array_key_exists('tag_id', $_GET)   ? (int) trim($_GET['tag_id'])  : "" ;
	$tag_id = array_key_exists('tag_id', $_POST)  ? (int) trim($_POST['tag_id']) : $tag_id;
	$tag_id = preg_match($pattern['int'], $tag_id )  ? $tag_id   : "";
	
	
	$load_all_pictures = false;

	
	/*
		header('Expires: Sat, 25 Dec 1976 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Content-type: application/json; charset=utf-8');
		
	*/	
	if(IS_AJAX){	
		header('Cache-Control: no-cache, must-revalidate');
		header("Pragma: no-cache");
		header('Content-type: application/json; charset=utf-8');
		header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")-2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	}
	
	
	if(!$LU->isLoggedIn()){
	
		if(IS_AJAX){

		$media['photos'][$durchgang]['img']="not_logged_in.gif";
		$media['photos'][$durchgang]['dir']="htdocs/img/admin/allevo/";
		$media['photos'][$durchgang]['path']="img/admin/allevo/";
		$media['photos'][$durchgang]['title']="you are not logged in";
		$media['photos'][$durchgang]['basket']="";
		$media['photos'][$durchgang]['extension']="gif";
		
	
		 echo json_encode($media);
		 exit;
		}
	}else{
		// you are logged in !
		
		
	$user = array( $liveuser['handle']);
	$tager = new Content_Tagger($mdb2);
	
	
	/* @param array  $args  Search criteria:
     *   limit      Maximum number of objects to return.
     *   offset     Offset the results. Only useful for paginating, and not recommended.
     *   tagId      Return objects related through one or more tags.
     *   notTagId   Don't return objects tagged with one or more tags.
     *   typeId     Only return objects with a specific type.
     *   objectId   Return objects with the same tags as $objectId.
     *   userId     Limit results to objects tagged by a specific user.
     *
     * @return array  An array of object ids.
     */
	 
	 if($tag_id != false || $tag_id != 0 ){

	 	$args['offset'] = 0;
		$args['limit']  = 100;
		$args['tagId']  = $tag_id;
		$args['typeId'] = "bilder";
		//$args['userId'] = $user;

		$getagte_bilder =  $tager->getObjects($args);

		$media['tags']['data'] = $getagte_bilder;

	 
	  }else{
	  
	  $load_all_pictures = true;
	  
	  }
   


		if (!$LU->checkRight(MEDIA_VIEW)){
					echo json_encode(array(error => "keine Rechte den Inhalt anzuschauen"));
		}else{
			

			if($action == "getimg" ){
				if ($handle = opendir($_allevo_config['app_root'].$dir)) { // get conntent from the filesystem
					 while (false !== ($file = readdir($handle))) {
					 
						  if ($file != "." && $file != "..") {
						  
							$pieces = explode(".", $file);

							if(array_search($pieces[0], $getagte_bilder ) || $load_all_pictures == true ){

								switch ($pieces[1]) {
								 case 'doc':
								 case 'pdf':
								 		$media['doc'][$durchgang]['file']=$file;
										$media['doc'][$durchgang]['dir']=$dir;
										$media['doc'][$durchgang]['path']=$_allevo_config['relativ_upload_path'];
										$media['doc'][$durchgang]['title']=$pieces[0];
										$media['doc'][$durchgang]['basket']="";
										$media['doc'][$durchgang]['extension']=$pieces[1];
									  break;
								 case 'jpg':
								 case 'jpeg':
								 case 'png':
								 case 'gif':
										$media['photos'][$durchgang]['img']=$file;
										$media['photos'][$durchgang]['dir']=$dir;
										$media['photos'][$durchgang]['path']=$_allevo_config['relativ_upload_path'].'140px/';
										$media['photos'][$durchgang]['title']=$pieces[0];
										$media['photos'][$durchgang]['basket']="";
										$media['photos'][$durchgang]['extension']=$pieces[1];
									  break;
								}

							}
								
								$durchgang = $durchgang+1;
						  }
					 }
					 closedir($handle);
				}
			
			}// action getimg
			
			
			
			
			
			
			
	}//  MEDIA_VIEW
	
	
	
			if (!$LU->checkRight(MEDIA_DELETE)){
				echo json_encode(array(error => "keine Rechte den Inhalt zu löschen"));
			}else{
				if($action == "delete" );
			}
		
		
			if (!$LU->checkRight(MEDIA_EDIT)){
				echo json_encode(array(error => "keine Rechte den Inhalt zu ändern"));
			}else{
				if($action == "edit");
			}
}


	if(IS_AJAX){

		ob_start();
		//var_dump($_REQUEST);
		print_r($_REQUEST);
		$ajax_request = ob_get_contents();
		ob_end_clean();
		
		$logger->log('ajax requests '. $ajax_request, PEAR_LOG_DEBUG );
		


		echo json_encode($media);
		exit;
	}else{


echo'
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
			<html> 
			<head> 
				 <title>YUI: Editor Image Browser</title> 
				 <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/reset-fonts-grids/reset-fonts-grids.css"> 
				 <style type="text/css" media="screen"> 
				 #doc {
					  min-width: 500px;
					  width: 90%;
				 }
				 #images p {
					  float: left;
					  padding: 2px;
					  margin: 2px;
					  border: 1px solid black;
					  cursor: pointer;
				 }
				 #images img {
					  max-width:100px;
					  max-heigth:100px;
				 }
				 </style> 
			</head> 
			<body class="yui-skin-sam"> 
			<div id="doc" class="yui-t7"> 
			 <p>Click auf das Bild um es im Editor zu platzieren.</p> 
			<div id="images"> ';

	foreach($media['photos'] as $key => $value){
		echo '<p><img src="http://'.$host.'/'.$_allevo_config['relativ_upload_path'].$value["img"].'" title="Click me" ></p>';
	}
	

echo'

</div> 
</div> 
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script type="text/javascript"> ';

echo"
(function() {
	
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event,
        //myEditor = window.opener.YAHOO.widget.EditorInfo.getEditorById('editor');
		myEditor = window.opener.YUI.allevo.editor;
        //Get a reference to the editor on the other page
    
    //Add a listener to the parent of the images
    Event.on('images', 'click', function(ev) {
        var tar = Event.getTarget(ev);
        //Check to see if we clicked on an image
        if (tar && tar.tagName && (tar.tagName.toLowerCase() == 'img')) {
            //Focus the editor's window
            myEditor._focusWindow();
            //Fire the execCommand for insertimage
            myEditor.execCommand('insertimage', tar.getAttribute('src', 2));
            //Close this window
            window.close();
        }
    });
    //Internet Explorer will throw this window to the back, this brings it to the front on load
    Event.on(window, 'load', function() {
        window.focus();
    });
})();



</script> 
</body> 
</head> ";


	

	
	}
























?>