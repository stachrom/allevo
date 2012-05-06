<?php


$LUOptions =
array(
        'debug' => false,
        'session'  => array(
            'name'     => 'PHPSESSION',             // liveuser session name
            'varname'  => 'ludata'                  // liveuser session var name
        ),
        'login' => array(
            'force'    => false                     // should the user be forced to login
        ),
        'logout' => array(
            'destroy'  => false                      // whether to destroy the session on logout
        ),
        'cookie' => array(
            'name' => 'AllevoRemeberMe',            // name of the Remember me cookie
            'lifetime' => 360,                      // cookie lifetime in days
            'path' => '/',                          // cookie path ?
            'domain' => null,                       // cookie domain ?
            'secret' => '786341allevolieet98845',
            'savedir' => $_allevo_config['app_root'].'session/cookie' , // absolute path to writeable directory (no trailing slash) ?
            'secure' => false,                      // whether cookie only send over secure connection
        ),


        'authContainers' => array(
              'allevo' => array(
                'type'          => 'MDB2',          // auth container name
                'expireTime'    => 604800,            // max lifetime of a session in seconds
                'idleTime'      => 43200,            // max time between 2 requests
                'allowDuplicateHandles' => 0,
                'allowEmptyPasswords'   => 0,       //
                'passwordEncryptionMode'=> 'MD5',
                'storage' => array(
                    'dsn' => $dsn,
                    'alias' => array(               // contains any additional or non-default field alias
                        'lastlogin' => 'lastlogin',
						'created' => 'created',
                        'is_active' => 'is_active',
                        'owner_user_id' => 'owner_user_id',
                        'owner_group_id' => 'owner_group_id',
                        'email' => 'email',
                        'auth_user_id' => 'auth_user_id',
                        'vorname' => 'vorname',
                        'nachname' => 'nachname',
                        'geschlecht' => 'geschlecht',
                        'strasse' => 'strasse',
                        'strassen_nummer' => 'strassen_nummer',
                        'plz' => 'plz',
                        'stadt' => 'stadt',
                        'land' => 'land',
                        'internet' => 'internet',
                        'tel' => 'tel',
                        'fax' => 'fax',
                        'mobile' => 'mobile',
                        'bemerkungen' => 'bemerkungen',
						'avatar' => 'avatar'
                    ),
                    'fields' => array(              // contains any additional or non-default field types
                        'lastlogin' => 'timestamp',
						'created' => 'timestamp',
                        'is_active' => 'boolean',
                        'owner_user_id' => 'integer',
                        'owner_group_id' => 'integer',
                        'email' => 'text',
                        'lastlogin' => 'timestamp',
                        'vorname' => 'text',
                        'nachname' => 'text',
                        'geschlecht' => 'text',
                        'strasse' => 'text',
                        'strassen_nummer' => 'text',
                        'plz' => 'integer',
                        'stadt' => 'text',
                        'land' => 'text',
                        'internet' => 'text',
                        'tel' => 'text',
                        'fax' => 'text',
                        'mobile' => 'text',
                        'bemerkungen' => 'text',
			'avatar' => 'blob'
                    ),
                    'tables' => array(              // contains additional tables or fields in existing tables
                        'users' => array(
                            'fields' => array(
                                'lastlogin' => false,
								'created' => false,
                                'is_active' => false,
                                'owner_user_id' => false,
                                'owner_group_id' => false,
                                'email' => false,
                                'lastlogin' => false,
                                'vorname' => false,
                                'nachname' => false,
                                'geschlecht' => false,
                                'strasse' => false,
                                'strassen_nummer' => false,
                                'plz' => false,
                                'stadt' => false,
                                'land' => false,
                                'internet' => false,
                                'tel' => false,
                                'fax' => false,
                                'mobile' => false,
                                'bemerkungen' => false,
				'avatar' => false
                            )
                        )
                    )
                )
            ),
				
				
				
/*		'horde' => array(
                'type'          => 'MDB2',          // auth container name
                'expireTime'    => 604800,            // max lifetime of a session in seconds
                'idleTime'      => 43200,            // max time between 2 requests
                'allowDuplicateHandles' => 0,
                'allowEmptyPasswords'   => 0,       //
                'passwordEncryptionMode'=> 'SHA1',
					 'secret'        => 'asdfeasdf',
                'storage' => array(
                    'dsn' => $_allevo_config['dsn_horde'],
                    'alias' => array(               // contains any additional or non-default field alias
                       'handle' => 'user_uid',
							  'passwd' => 'user_pass'
                    ),
                    'fields' => array(              // contains any additional or non-default field types
                        'user_uid' => 'text',
								'user_pass' => 'text'
                    ),
                    'tables' => array(              // contains additional tables or fields in existing tables
                        'horde_users' => array(
                            'fields' => array(
                                'user_uid'  => false,
										  'user_pass' => false
							
                            )
                        )
                    )
                )
            )
				
*/
				
        ),
        'permContainer' => array(
            'type' => 'Complex',
            'storage' => array(
                'MDB2' => array(                    // storage container name
                    'dsn' => $dsn,
                    'prefix' => 'liveuser_',         // table prefix
                    'tables' => array(
								'groups' => array(
										'fields' => array(
												'is_active' => false,
												'owner_user_id' => false
												)
										)
								),
							  'fields' => array(
									'is_active' => 'boolean',
									'owner_user_id' => 'integer'
								),
                    		'alias'  => array(
					       		'is_active' => 'is_active',
									'owner_user_id' => 'owner_user_id'
								)
                		)
            		)
        			)
    			);

# Liveuser instanz erzeugen
//$usr = LiveUser::singleton($conf);



class LU_Default_observer{

    function notify(&$notification){
	 	global $smarty;
		global $LU;
		global $_response;

		
	 	$smarty->assign('LU_observer', $notification->getNotificationName() );

		switch ($notification->getNotificationName()) {
			 case 'onLogin' :					
					$_response['status'] = 200;
					$_response['statusmsg'] = $LU->getProperty('auth_user_id').' erfolgreich eingeloggt';

				  break;
			 case 'onFailedLogin':
					$_response['status'] = 400;
					$_response['statusmsg'] = 'Passwort oder Username is falsch';

				  break;
			 case 'postLogout':
					 header('Location: index.php');
				  break;	  
		}
		
		

    }
}




	$LU =& LiveUser::factory($LUOptions);
	
	$obs = new LU_Default_observer();


	$LU->dispatcher->addObserver(array(&$obs, 'notify'));
	




    if (!$LU->init()) {
        $LU->getErrors();
        die();
    }



    $handle =  (array_key_exists('handle',     $_REQUEST)) ? $_REQUEST['handle'] : null;
    $passwd =  (array_key_exists('passwd',     $_REQUEST)) ? $_REQUEST['passwd'] : null;
    $logout =  (array_key_exists('logout',     $_REQUEST)) ? $_REQUEST['logout'] : false;
    $remember =(array_key_exists('rememberMe', $_REQUEST) && ($_REQUEST['rememberMe'] == 1)) ? true : false;


    if ($logout) {
        $LU->logout(true);
		// $LU->logout(false);// does not delete the RememberMe cookie 
        # wohin soll ich geleitet werden, wenn ich ausgeloggt werde?
                        
    } elseif(!$LU->isLoggedIn() || ($handle && $LU->getProperty('handle') != $handle)) {
	
	
		ob_start();
			//var_dump($_REQUEST);
			print_r($_SESSION);
			$ajax_request = ob_get_contents();
        ob_end_clean();
		
		//$logger->log('session shit '. $ajax_request, PEAR_LOG_DEBUG );
	
	

		if (!$handle) {
			$LU->login(null, null, true);							
		}else{
			$LU->login($handle, $passwd, $remember);  
		}
	}

# LiveUser Admin Instanz erzeugen

$lu_admin =& LiveUser_Admin::factory($LUOptions);
$lu_admin->init();


?>
