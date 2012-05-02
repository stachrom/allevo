<?php
// Client specific variables
//allevo Config array
$_allevo_config = array(	
   'log' => array(
      'enabled' => true,
      'level' => 7,  //0 -7 
      'file' => array(
         'append' => true,
         'locking'  => false,
         'mode'  => '0644',
         'dirmode'  => '0755',
         'lineFormat'  => '%1$s %2$s [%3$s] %4$s %5$s %6$s %7$s %8$s',
         'timeFormat'  => '%b %d %H:%M:%S' 
		),
      'mail' => array(
         'from' => "webpage@allevo.ch",
         'subject'  => "allevo error log",
         'preamble'  => '',
         'lineFormat'  => '%1$s %2$s [%3$s] %4$s %5$s %6$s %7$s %8$s',
         'timeFormat'  => '%b %d %H:%M:%S',
         'mailBackend'  => '',
         'mailParams'  => ''
      ),					
      'name' => '/log/allevo.log' 
   ),
   'htdocs_dir' => 'htdocs',
   'horde' => array(
      'module' => array( 
         'kronolith' => array (),
         'turba'     => array(
            'sources' => array('LofVYGVu5LFPRRgUSwaR4uA')
         )
      )
   ),
   'app_root' => str_replace("htdocs", '', $_SERVER["DOCUMENT_ROOT"]),
   'relativ_upload_path' => 'img/upload/',		
   'version' => '1.0',
   'template_engine' => 'smarty',
   'locale' => array('de_CH.utf8', 'de_DE.UTF8', 'de_DE.ISO8859-1', 'de_DE', 'de', 'ge'),
   'yui'  => array(
      'version' => "2.8.1",
      'load' => '',
      'allowRollups'  => true,
      'base' => '',
      'filter' => 'YUI_DEBUG',
      'loadOptional' => '',
      'combine'  => true,
      'comboBase'  => ''
   ),
   'dsn'  => array(
      'phptype'  => 'mysql',
      'username' => 'root',
      'password' => '',
      'hostspec' => 'localhost',
      'database' => 'finishers',
      'charset'  => 'utf8',
      'new_link'  => true
   ),
   'dsn_horde'  => array(
      'phptype'  => 'mysql',
      'username' => 'root',
      'password' => '',
      'hostspec' => 'localhost',
      'database' => 'horde_4',
      'charset'  => 'utf8',
      'new_link'  => true
   ),				
   'dsn_yuitable'  => array(
      'phptype'  => 'mysql',
      'username' => 'root',
      'password' => '',
      'hostspec' => 'localhost',
      'database' => 'allevo',
      'charset'  => 'utf8',
      'new_link'  => true
   ),			
   'adresse'  => array(
      'name'     => 'Stachura',             
      'vorname'  => 'Roman',
      'strase'   => 'moosackerstrasse',
      'nummer'   => '19',
      'plz'      => '8405',
      'email'    => 'info@stachura.ch',
   ),
   'module'  => array(	
      'google'  => array(				
         'maps'  => array(
            'api_key'   => 'ABQIAAAAtY6cA31_J5PKzXWqu2xuBhRwWqG_G-LkagiCytLZFcaS0g4qRRQ7JdO6jAXo1U4oryuGt7FC5xjFlQ',
            'latitude'  => '47.54663986006874', # http://mapki.com/ #
            'longitude' => '8.844938278198242', # http://www.mapbuilder.net/ #
            'zoom'      => '13'
         )
      ),
      'rss'  => array(				
         'xml'   => true,
      )
   ),
);
// Password
require_once ('password.php'); 

// bellow do not touch anything


// Setup include path
$host = $_SERVER['HTTP_HOST'];
$app_root = $_allevo_config['app_root'];
$pear = dirname(__FILE__).'/../pear/PEAR';
$horde_pear = '/home/www/stachura.ch/htdocs/horde/libs';
//$zend_framework = dirname(__FILE__).'/../zendframework/library';


// header pictures must contain  string = /img/
$header_pictures = $app_root . '/img/template/stadttheater/header';


 define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
 define('SMARTY_DIR', $app_root.'smarty/3.x/libs/');
 define('HORDE_BASE', '/home/www/stachura.ch/htdocs/horde');
 
// Load the Horde Framework Core
set_include_path($app_root.'libs/'. PATH_SEPARATOR. $pear . PATH_SEPARATOR. $horde_pear );


//require_once HORDE_BASE . '/lib/Application.php';
require_once HORDE_BASE . '/libs/Horde/Autoloader/Default.php';
require_once HORDE_BASE . '/libs/Horde/Serialize.php';

//Horde_Registry::appInit('horde');



/* PEAR base class. */
include_once SMARTY_DIR . 'Smarty.class.php';
require_once ('PEAR.php');
require_once ('Log.php');
require_once ('DB/NestedSet.php');
require_once ('DB/NestedSet/Output.php');
require_once ('MDB2.php');
require_once ('HTML/Menu.php');
require_once ('HTML/Menu/DirectTreeRenderer.php');
require_once ('HTML/QuickForm.php');
require_once ('HTML/QuickForm/CAPTCHA/Figlet.php');
require_once ('HTML/QuickForm/Renderer/ArraySmarty.php');
require_once ('LiveUser.php');
require_once ('LiveUser/Admin.php');
require_once ('Benchmark/Timer.php');
require_once ('pear_error_handler.php');


/*
require_once  HORDE_BASE . '/libs/Horde/Text/Filter.php';
require_once 'Horde/RPC.php';
require_once 'Horde/String.php';
require_once 'Horde/Text/Filter.php';


include 'Horde/Support/Uuid.php';
include 'Horde/Support/Guid.php';
include 'Horde/Support/Randomid.php';
include 'Horde/date/Date.php';
*/
include 'tager/manager.php';


// HORDE RPC parameter 


// XML-RPC endpoint
	$rpc_endpoint = 'http://horde4.finishers.ch/rpc.php';
	
	
	//$rpc_endpoint = 'http://www.stachura.ch/horde/rpc.php';

	// XML-RPC options, usually username and password
	$rpc_options = array(
		'request.username' => 'admin',
		'request.password' => 'admin',
		'request.timeout'  => 5
	);
	
	$rpc_parameter = array(
		
	);


// timer  benchmaerk
	$timer = new Benchmark_Timer(TRUE);
	$timer->start();





// Change error handling as necessary
// PEAR_ERROR_RETURN, PEAR_ERROR_PRINT, PEAR_ERROR_TRIGGER, PEAR_ERROR_DIE or PEAR_ERROR_CALLBACK
// PEAR::setErrorHandling(PEAR_ERROR_PRINT);
// error handling
// Pear Error handling

		$logger = &Log::singleton('file', $app_root.$_allevo_config['log']['name'], 'allevo', $_allevo_config['log']['file']);
		$mask = Log::MAX($_allevo_config['log']['level']);
		$logger->setMask($mask);

	if($_allevo_config['log']['enabled'] == false){
		define("LOGGING", TRUE);
		
		function errorHandler($code, $message, $file, $line){
			 global $logger;
		
			 /* Map the PHP error to a Log priority. */
			 switch ($code) {
			 case E_WARNING:
			 case E_USER_WARNING:
				  $priority = PEAR_LOG_WARNING;
				  break;
			 case E_NOTICE:
			 case E_USER_NOTICE:
				  $priority = PEAR_LOG_NOTICE;
				  break;
			 case E_ERROR:
			 case E_USER_ERROR:
				  $priority = PEAR_LOG_ERR;
				  break;
			 default:
				  $priority = PEAR_LOG_INFO;
			 }
		
			 $logger->log($message . ' in ' . $file . ' at line ' . $line, $priority);
		}

		set_error_handler('errorHandler');
		

	}else{
	
		//error_reporting(0);

	
	}
		
		$timer->setMarker('logging');	
	

	 //$logger->log("Log entry $i", PEAR_LOG_EMERG );
	 //$logger->log("Log entry $i", PEAR_LOG_ALERT );
	 //$logger->log("Log entry $i", PEAR_LOG_CRIT );
	 //$logger->log("Log entry $i", PEAR_LOG_ERR );
	 //$logger->log("Log entry notice", PEAR_LOG_NOTICE );
	 //$logger->log("Log entry warning", PEAR_LOG_WARNING );
	 //$logger->log("Log entry info", PEAR_LOG_INFO );
	 //$logger->log("Log entry debug", PEAR_LOG_DEBUG );
	 //$logger->log(serialize($_SERVER), PEAR_LOG_INFO );	 


	if(LOGGING == true){
		//PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'eHandler');
	}else{
	
		
	
	}
			


// --------------------------- SMARTY  ----------------


	$smarty =& new Smarty;
	
	$smarty->template_dir = $app_root .'/templates/';
	$smarty->compile_dir  = $app_root .'/smarty/compile';
	$smarty->cache_dir    = $app_root .'/smarty/cache';
	$smarty->config_dir   = $app_root .'/smarty/configs/';
	
    $smarty->force_compile = true;
	$smarty->debugging = false; //$_allevo_config['log']['enabled'];
	$smarty->setCompileCheck = true;
	$smarty->caching = false;
	$smarty->allow_php_templates=false;
	$smarty->cache_lifetime = 300;

	$timer->setMarker('smarty');

// --------------------------- Datenbank einstellungen ----------------


	$options = array(
		 'debug'       => 5,
		 'portability' => MDB2_PORTABILITY_ALL,
	);
	

	
	$dsn = $_allevo_config['dsn'];
	
	// uses MDB2::factory() to create the instance
	// and also attempts to connect to the host

	$mdb2 =& MDB2::connect($_allevo_config['dsn'], $options);
	
	for ($__i = 0; $__i < count($GLOBALS['_MDB2_databases']); $__i++) { 
    $GLOBALS['_MDB2_databases'][$__i]->dsn['password'] = '* hidden *'; 
    $GLOBALS['_MDB2_databases'][$__i]->connected_dsn['password'] = '* hidden *'; 
} 
	
	
	$mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
	
	if (PEAR::isError($mdb2)) {
		 die($mdb2->getMessage());
	}

	$timer->setMarker('mbd2');


# authentifikation und rechte ermittelt via liveuser

 	require_once 'liveuser/config.php';
	$timer->setMarker('liveuser config');
		
	if($LU->isLoggedIn()){
	 require_once 'liveuser/konstanten.php';
	 $timer->setMarker('liveuser konstanten');	
	}



// ---------------- sprache ermitteln. --------------------

  
		if(empty($_SESSION['sprache'])){
				$_SESSION['sprache'] = 'de';
				setlocale(LC_ALL, $_allevo_config['locale']);
			 }elseif( isset($_GET['language'])){
				switch ($_GET['language']) {
				  case 'de':
					  $_SESSION['sprache'] = 'de';
					  $_allevo_config['locale'] = array('de_CH.utf8', 'de_DE.UTF8', 'de_DE.ISO8859-1', 'de_DE', 'de', 'ge');
					  break;
				  case 'en':
					  $_SESSION['sprache'] = 'en';
					  $_allevo_config['locale'] = array('en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US');
					  break;
				  case 'fr':
					  $_SESSION['sprache'] = 'fr';
					  $_allevo_config['locale'] = array('fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
					  break;
					default:  
					  $_SESSION['sprache'] = 'de';
					  $_allevo_config['locale'] = array('de_CH.utf8', 'de_DE.UTF8', 'de_DE.ISO8859-1', 'de_DE', 'de', 'ge');
					  break;
				}
				setlocale(LC_ALL, $_allevo_config['locale']);
				
		}else{
		setlocale(LC_ALL, $_allevo_config['locale']);
		
		}
		
		
	// ################################################## //			
	// ---------------- Nested_sets. --------------------	//
	// ################################################## //			

	
		$nestedSets_param = array(
    	'id'        => 'id', # required
    	'parent_id' => 'rootid', # required
    	'order_num' => 'norder', # required
    	'level'     => 'level', # required
    	'left_id'   => 'l', # required
    	'right_id ' => 'r', # required
    	'name'      => 'name',
    	'active'    => 'active',
    	'owner_user_id' => 'owner_user_id',
		'owner_group_id' => 'owner_group_id',
    	'link' => 'link',
		'rewrite' => 'rewrite',
		'query_string' => 'query_string',
		'uuid' => 'uuid'
	);
	
	$NestedSets =& DB_NestedSet::factory('DB', $dsn, $nestedSets_param);


	$NestedSets->setAttr(array(
        'node_table' => 'nested_set',
        'lock_table' => 'nested_set_locks',
        'secondarySort' => 'left_id',
        'debug' => '0'
    	));
		
	$NestedSets->setsortMode('SLV');		
	
	$rootnodes = $NestedSets->getRootNodes(true);		
		
		
		
foreach($rootnodes as $key => $value){

			switch ($SERVER_NAME[0]) {
			 
			 case 'www':
					 if($value['id'] == 1 ){
							$standard_id = $value['id'];
						}
				  break;  
			default:
					$standard_id = 1;
		}
	
}	
	
		
		
		

	
	
// time funktion.

function secondsToTime($seconds)
					{
						 // extract hours
						 $hours = floor($seconds / (60 * 60));
					 
						 // extract minutes
						 $divisor_for_minutes = $seconds % (60 * 60);
						 $minutes = floor($divisor_for_minutes / 60);
					 
						 // extract the remaining seconds
						 $divisor_for_seconds = $divisor_for_minutes % 60;
						 $seconds = ceil($divisor_for_seconds);
					 
						 // return the final array
						 $obj = array(
							  "h" => (int) $hours,
							  "m" => (int) $minutes,
							  "s" => (int) $seconds,
						 );
						 
						 // 2 digit string:  leading 0 ? 
						 foreach($obj as $key => $value){
							 if($value < 10){
								$obj2Digits[$key] = (string)"0".$value;
							 }else{
								$obj2Digits[$key] = (string) $value;
							 }
						 }
						 
						 // all the day long? 
						 if( $obj2Digits['h'] ==23 and $obj2Digits['m']== 59 ){
						  $string = "ganztägig";
						 }else{
						  $string = $obj2Digits['h'].":".$obj2Digits['m'].":".$obj2Digits['s'];
						 }
						
						 
						 return $string;
					}	
		
	
		
// ---------------- Regex Pattern -------------------- 
		$pattern = array();
		$pattern['bin']	         ='/^[01]+$/';
		$pattern['int']	         ='/^[0-9]+$/';
		$pattern['alphanumeric']	='/^[a-zA-Z0-9_äÄöÖüÜß]+$/u';	
		$pattern['password']	      ='/^[a-zA-Z0-9-_?+*%$!&☺®.©()=@¦§äÄöÖüÜß]+$/u';
		$pattern['datum']='/^(0[1-9]|[12][0-9]|3[01])[\/\-](0[1-9]|1[012])[\/\-]\d{4}$/'; // DD-MM-YYYY or DD/MM/YYYY
		$pattern['username']	      ='/^[a-zA-Z0-9-_äÄöÖüÜß]+$/u';
		$pattern['rightlevel']	   ='/^[1-3]+$/u';
		$pattern['eventUID']='/^[0-9]{14}[.][a-zA-Z0-9\-_]{23}@.+(.ch)$/';
		$pattern['objectid']='/^[a-zA-Z0-9\-]{23}+$/';
		$pattern['turbaID']='/^[a-zA-Z0-9\-]{23}+$/';
		$pattern['0-5']='/^[0-5]{1}+$/';
		$pattern['int_kronolith']='/^[0-9]{10}?$/';
		$pattern['text']='/^[0-9a-zA-Z-]+$/';
		$pattern['filter']='/^[ABCJSMFK45]{1,2}+$/';
		
	
		



		$liveuser = array(
			'loggedIn' => $LU->isLoggedIn(),
			'handle' => $LU->getProperty('handle'),
			'Last_Login' => date('d.m.Y H:i', $LU->getProperty('lastlogin')),
			'status' => $LU->getStatus(),
			'auth_user_id' => $LU->getProperty('auth_user_id'),
			'owner_user_id' => $LU->getProperty('owner_user_id'),
			'owner_group_id' => $LU->getProperty('owner_group_id'),
			'perm_user_id' => $LU->getProperty('perm_user_id'),
			'is_active' => $LU->getProperty('is_active'),
			'email' => $LU->getProperty('email') 
			);
			
			
			
	
	$params_get_groups = array( 
							'filters' => array(
                 			'perm_user_id' => $liveuser['perm_user_id']
                			) 
							);
					 
	$user_is_in_group = $lu_admin->perm->getGroups($params_get_groups);
	
	

	
	
	
function check_dir($dir_img){
	if (is_dir($dir_img)) {
		 if ($dh = opendir($dir_img)) {
			  while (($file = readdir($dh)) !== false) {
				 $server_bilder[] = $file;
			  }
			  closedir($dh);
			  return $server_bilder;
		 }
	}
}





function unserialize_content($content=0, $single_content=false){

	global $server_bilder;
	global $mdb2;
	global $smarty;
	$tager = new Content_Tagger($mdb2);

	if (is_array($content)){

			foreach($content as $key => $value)
			{
					
				$content[$key] = unserialize($value)  ? unserialize($value) : $value;

				if( $key == 'query_string' and $single_content == true)
				{
						if(!is_array($temp_array = unserialize($value)))
						{
							 
						}else{

								if (array_key_exists('start', $temp_array)) 
								{
									 $start = $temp_array['start'];
								}else{
									 $start = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
								}
							
								if (array_key_exists('stop', $temp_array)) 
								{
									 $stop = $temp_array['stop'];
								}else{
									 $stop = mktime(0, 0, 0, date("m"), date("d")+6, date("Y"));
								}
						
								foreach(unserialize($value) as $k => $value)
								{
									switch ($k) {
										 case "trainings":
											 $tags = explode(" ", $value);
											 $smarty->assign('calendar_tags', $tags , true);
											break;
										case "eventUID":
										global $pattern;
						
											$eventUID  =  preg_match($pattern['eventUID'], trim($value))  ? trim($value)  : false;

											if($eventUID){
								
												get_kronolith_event($eventUID);
											}
									
											  break;
										case "turbaID":
											   get_turba_contact($value);
											  break;	  
									}
								}
						}
						
						if ($single_content == true)
						{
							$smarty -> assign('stopDate',  date("Y.m.d", $stop), true);
							$smarty -> assign('startDate', date("Y.m.d", $start), true);
							
							$range = $stop - $start;
							
							do {
								$calendar_dates[] = date("Y,m,d", $start+$range);
								$range = $range - (60*60*24); 
							} while ($range > 0);
							
								$calendar_dates[] = date("Y,m,d", $start);
								$smarty -> assign('calendar_dates',  implode("/", $calendar_dates), true);
						}
				}
						if( $key == 'gallery')
						{
							if(!is_array(unserialize($value) )  ) {
							 $content['gallery'] = false ;
							}else{
							 // content von db und filesystem abgleichen
							 $content['gallery'] = array_intersect($content['gallery'], $server_bilder);
							}
						}
						if( $key == 'sidepictures')
						{ 
							if(!is_array(unserialize($value))){
								$content['sidepictures'] = false;
							}else{
							// content von db und filesystem abgleichen 
							$content['sidepictures'] = array_intersect($content['sidepictures'], $server_bilder);

							
							} 

						}
						if( $key == 'uuid')
						{
							$query = "SELECT object_id FROM rampage_objects
							 WHERE object_name ='".$value."'";
							 
							if($objectId = $mdb2->queryOne($query))
							{
								$args['offset'] = $offset;
								$args['limit']  = $limit;
								$args['q']      = "";
								$args['objectId'] = $objectId;
				
								$content['tags'] = $tager->getTags($args);
							}
						}	
						
				}
				
				return $content;

	}else{
		// there is no content. what shall we do?
	}

}



		$smarty->assign('liveuser', $liveuser, true);
		$smarty->assign("Adresse", $_allevo_config['adresse'], true);
		$smarty->assign("version", $_allevo_config['version']);
		$smarty->assign("template_name", "stadttheaterolten");
		$smarty->assign('pfad', "", false);
		$timer->setMarker('ende config');

?>
