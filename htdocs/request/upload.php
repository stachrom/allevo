<?php
	if (isset($_COOKIE['PHPSESSID'])) {
	 session_id($_COOKIE['PHPSESSID']);
	}
	session_start();

	$sesseiontest =  session_id();
	include '../set_env.php';

	$logger->log("session ". serialize($_SESSION), PEAR_LOG_DEBUG );
	$logger->log(" before set_env session ". serialize($sesseiontest), PEAR_LOG_DEBUG );
	
	$dir = $_allevo_config['htdocs_dir'].'/'.$_allevo_config['relativ_upload_path'];


	require_once "HTTP/Upload.php";
	require_once 'Image/Transform.php';
	
	$uploadErrors = array(
		UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
		UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
		UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
		UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
		UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
		UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
		UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.'
	);



	if(!$LU->isLoggedIn()){
		//$logger->log("cookie ". serialize($_COOKIE['PHPSESSID']), PEAR_LOG_DEBUG );
		//$logger->log("REQUEST ". serialize($_REQUEST), PEAR_LOG_DEBUG );
		//$logger->log("File ". serialize($_FILES), PEAR_LOG_DEBUG );
		//$logger->log("SERVER ". serialize($_SERVER), PEAR_LOG_DEBUG );	 
		//$logger->log("Liveuser ". serialize($liveuser), PEAR_LOG_DEBUG );		 
		//print_r($_REQUEST);		 		
		//echo json_encode(array(error => "Please login to access the content"));

	}else{
		//$logger->log("tadddddaaa Logged in ", PEAR_LOG_DEBUG );
		// print_r($_REQUEST);	
		//echo json_encode(array(error => "you are logged in"));
	 	//exit;
	}
	
	
	//create transform driver object


	$upload = new HTTP_Upload("de");

	$files = $upload->getFiles();

	foreach($files as $file){
				
				
				
		if ($file->isValid()) {
			$moved = $file->moveTo($_allevo_config['app_root'].$dir);
			
			if (!PEAR::isError($moved)) {

				$img_name = $file->getProp('name');
				$path = '../img/upload/';
				
				$it = Image_Transform::factory('GD');
				
				$it->load( $path.$img_name);
				$it->scaleByX(700);
				$it->save( $path.'700px/'.$img_name );
				$it->free();
				
				$it->load($path.$img_name);
				$it->scaleByX(446);
				$it->save($path.'446px/'.$img_name);
				$it->free();
				
				$it->load($path.$img_name);
				$it->scaleByX(280);
				$it->save($path.'280px/'.$img_name);
				$it->free();
				
				$it->load($path.$img_name);
				$it->scaleByX(140);
				$it->save($path.'140px/'.$img_name);
				$it->free();

				$logger->log("uploader success:". 'File was moved to upload/' . $file->getProp('name'), PEAR_LOG_DEBUG );
			} else {
				$logger->log("uploader REQUEST:". $moved->getMessage(), PEAR_LOG_DEBUG );
			}
			
		} elseif ($file->isMissing()) {
					 $logger->log("uploader problem". "No file was provided.", PEAR_LOG_DEBUG );
		} elseif ($file->isError()) {
					 $logger->log("uploader error". $file->errorMsg(), PEAR_LOG_DEBUG );	 
		}
	}


	
	
	if($_POST['action'] == 'sendmail'){
	
	function check_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	

	
	$name           = check_input($_POST['namen']);
	$vorname        = check_input($_POST['vornamen']);
	$mitgliedschaft = check_input($_POST['mitgliedschaft']);
	$strasse        = check_input($_POST['strasse']);
	$ort            = check_input($_POST['ort']);
	$plz            = check_input($_POST['plz']);
	$email          = check_input($_POST['email']);
	$telp           = check_input($_POST['telefon']['telp']);
	$telm           = check_input($_POST['telefon']['telm']);
	$telg           = check_input($_POST['telefon']['telg']);
	$beruf          = check_input($_POST['beruf']);
	$message        = check_input($_POST['message']);
	

	
	$rpc_parameters_turba_import = array(
		array( 
				'firstname' => $name ,
        		'lastname' => $vorname,                               
				'birthday' => sprintf('%04d',$_REQUEST['geburtsdatum']['Y']).'-'.sprintf('%02d',$_REQUEST['geburtsdatum']['F']).'-'.sprintf('%02d',$_REQUEST['geburtsdatum']['d']), 
				'eintritt' => date("Y").'-'.date("m").'-'.date("d"),
				'homeStreet' => $strasse,
				'homeCity' => $ort,
				'homePostalCode' => $plz,
				'homeCountry' => 'Schweiz',
				'email' => $email,
				'homePhone' => $telp,
				'workPhone' => $telg,
				'cellPhone' => $telm ,
				'role' => $beruf,
				'memberstatus' => 'Eintritt',
				'mitgliederbeitrag' => 0,
				'notes' => $message.' Mitglied: '.$mitgliedschaft
				),
		 'contentType' => 'array',
		 'addressbook' => 'JjpRIWVu5LFPetWj7aTul4A'

 	);

	
		try {

			$http_client = new Horde_Http_Client($rpc_options);
		
			$result  = Horde_Rpc::request(
				'jsonrpc',
				$GLOBALS['rpc_endpoint'],
				'contacts.import',
				$http_client,
				$rpc_parameters_turba_import
				);
	
		}catch (Exception $e) {	

			echo 'Caught exception: ',  $e->getMessage(), "\n";

		}
	
	
	
	
	
	}
	
	
	$_response = array(	
				'post_variables' => $_POST,
				'file_variables' => $_FILES,
				'error' => ''
				);

    
        header('Expires: Mon, 25 Dec 1976 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-type: application/json; charset=utf-8');

        echo json_encode($_response);
		
        exit;


	
	/*

			header("Content-type: text/plain");
			
			echo "{\n";
			echo "  'post_variables': {\n"; 
			foreach ($_POST as $k => $v) {
				echo "    " . $k . ": '" . $v . "'\n";
			}
			echo "  },\n"; 
			echo "  files: [\n";
			
			foreach($_FILES as $k => $v){
				 echo "    {\n";
				 $ec = $_FILES[$k]['error'];

				 if ($ec !== UPLOAD_ERR_OK){
					echo "    error: '" . $uploadErrors[$ec] . "',\n";
				 }
					echo "      size: " . $_FILES[$k]['size'] . ",\n";
					echo "      name: '" . $_FILES[$k]['name'] . "',\n";
					echo "      md5: '" . md5(file_get_contents($_FILES[$k]['tmp_name'])) . "'\n";
					echo "    },\n";
			}
			
			echo "  ]\n";
			echo "}\n";
			

*/


?>