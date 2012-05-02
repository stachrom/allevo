<?php
//include the files from the PHP FT client library
include('fusiontable/clientlogin.php');
include('fusiontable/sql.php');
include('fusiontable/file.php');
	// Table id
	$tableid = 964141;
	
	//Enter your username and password
	$username = "stachrom";
	$password = "rnga jfvo vgua zvrr";
	
	$fusion_token =& $mdb2->query('SELECT * FROM fusiontable WHERE id=1');
	
	
	while (($row = $fusion_token->fetchRow(MDB2_FETCHMODE_ASSOC))) {
	
		 $token = $row['token'];
		 $timestamp = strtotime($row['timestamp']);
		 
	}
	


	// Get auth token - it would be better to save the token in a secure database
	// rather than requesting it with every page load.
	// $token = ClientLogin::getAuthToken($username, $password);
	
	// after one week get a new token!
	$week = (60*60*24*7);
	$today = time();

	if(  $today - $timestamp  >= $week ){

		$mdb2->loadModule('Extended');
	
		$fields_values = array(
				'token'    => ClientLogin::getAuthToken($username, $password),
				'timestamp'=> date( 'Y-m-d H:i:s', time() )
		);

		$types = array('text', 'timestamp');
	
		$affectedRows = $mdb2->extended->autoExecute('fusiontable', $fields_values, MDB2_AUTOQUERY_UPDATE, 'id = '.$mdb2->quote(1, 'integer'), $types);

		$token = $fields_values['token'];

	}
	
$ftclient = new FTClientLogin($token);

?>