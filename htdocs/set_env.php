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
         'database' => 'allevo_dev',
         'charset'  => 'utf8',
         'new_link'  => true
      ),
      'adresse'  => array(
         'name'     => 'Stachura',             
         'vorname'  => 'Roman',
         'strasse'   => '',
         'nummer'   => '',
         'plz'      => '',
         'email'    => '',
      ),
      'module'  => array(	
         'google'  => array(				
            'maps'  => array(
               'api_key'   => '',
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

   // Setup include path
   $host = $_SERVER['HTTP_HOST'];
   $app_root = $_allevo_config['app_root'];
   $pear = dirname(__FILE__).'/../pear/PEAR';
   $horde_pear = '/home/www/stachura.ch/htdocs/horde/libs';

   // header pictures must contain  string = /img/
   $header_pictures = $app_root . '/img/template/stadttheater/header';
   
   define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
   define('SMARTY_DIR', $app_root.'smarty/3.x/libs/');

   // Load the Horde Framework Core
   set_include_path(get_include_path() . PATH_SEPARATOR .$app_root.'libs/'. PATH_SEPARATOR. $pear . PATH_SEPARATOR. $horde_pear );

   //require_once HORDE_BASE . '/lib/Application.php';
   require_once 'Horde/Autoloader/Default.php';
   require_once 'Horde/Serialize.php';

   //Horde_Registry::appInit('horde');

   /* PEAR base class. */
   include_once SMARTY_DIR . 'Smarty.class.php';
   require_once ('PEAR.php');
   require_once ('Log.php');
   require_once ('DB/NestedSet.php');
   require_once ('DB/NestedSet/Output.php');
   require_once ('MDB2.php');
   require_once ('LiveUser.php');
   require_once ('LiveUser/Admin.php');
   require_once ('Benchmark/Timer.php');
   require_once ('pear_error_handler.php');
   
   include_once ('tager/manager.php');   
   include_once ('password.php');
   include_once ('global_functions.php'); 

   // HORDE RPC parameter 
   // JSON-RPC endpoint
   $rpc_endpoint = 'http://horde4.finishers.ch/rpc.php';

   $rpc_options = array(
      'request.username' => 'admin',
      'request.password' => '',
      'request.timeout'  => 5
   );
      
   $rpc_parameter = array();

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
      define("LOGGING", true);
         
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
      define("LOGGING", false);
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
   //$smarty->setCompileCheck = true;
   $smarty->caching = false;
   $smarty->allow_php_templates=false;
   $smarty->cache_lifetime = 300;

   $timer->setMarker('smarty');
   


// --------------------------- Datenbank einstellungen ----------------


   $options = array(
      'debug'       => 0,
      'portability' => MDB2_PORTABILITY_ALL,
   );
	
   $dsn = $_allevo_config['dsn'];

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

   $SERVER_NAME = explode(".", $_SERVER["SERVER_NAME"]);   

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
	
// ---------------- Regex Pattern --------------------
 
   $pattern = array();
   $pattern['bin']            ='/^[01]+$/';
   $pattern['int']	         ='/^[0-9]+$/';
   $pattern['int_kronolith']  ='/^[0-9]{10}?$/';
   $pattern['alphanumeric']	='/^[\w_äÄöÖüÜß]+$/u';	
   $pattern['password']	      ='/^[a-zA-Z0-9-_?+*%$!&☺®.©()=@¦§äÄöÖüÜß]+$/u';
   $pattern['datum']          ='/^(0[1-9]|[12][0-9]|3[01])[\/\-](0[1-9]|1[012])[\/\-]\d{4}$/'; // DD-MM-YYYY or DD/MM/YYYY
   $pattern['username']	      ='/^[\w\-_äÄöÖüÜß]+$/u';
   $pattern['rightlevel']	   ='/^[1-3]+$/u';
   $pattern['eventUID']       ='/^[0-9]{14}[.][a-zA-Z0-9\-_]{23}@.+(.ch)$/';
   $pattern['objectid']       ='/^[\w\-]{23}+$/';
   $pattern['turbaID']        ='/^[\w\-]{23}+$/';
   $pattern['0-5']            ='/^[0-5]{1}+$/';
   $pattern['text']           ='/^[\w\s\-]+$/';
   $pattern['filter']         ='/^[ABCJSMFK45]{1,2}+$/';
   $pattern['nodeName']       ='/^[\w\s\-\'"]{1,255}+$/';


// ---------------- Liveuser --------------------  
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

   $smarty->assign('liveuser', $liveuser, true);
   $smarty->assign("Adresse",  $_allevo_config['adresse'], true);
   $smarty->assign("version",  $_allevo_config['version']);
   $smarty->assign("template_name", "stadttheaterolten");
   $smarty->assign('pfad', "", false);
   
   $timer->setMarker('ende config');
   
?>
