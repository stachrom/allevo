<?php

	function eHandler($errObj){
		 echo('<hr /><span style="color: red;">' . $errObj->getMessage() . ':<br />' . $errObj->getUserInfo() . '</span><hr />');
		 $debug_backtrace = debug_backtrace();
		 array_shift($debug_backtrace);
		 $message= 'Debug backtrace:'."\n";
	
		 foreach ($debug_backtrace as $trace_item) {
			  $message.= "\t" . '    @ ';
			  if (array_key_exists('file', $trace_item)) {
					$message.= basename($trace_item['file']) . ':' . $trace_item['line'];
			  } else {
					$message.= '- PHP inner-code - ';
			  }
			  $message.= ' -- ';
			  if (array_key_exists('class', $trace_item)) {
					$message.= $trace_item['class'] . $trace_item['type'];
			  }
			  $message.= $trace_item['function'];
	
			  if (array_key_exists('args', $trace_item) && is_array($trace_item['args'])) {
					$message.= '('.@implode(', ', $trace_item['args']).')';
			  } else {
					$message.= '()';
			  }
			  $message.= "\n";
		 }
		 echo "<pre>$message</pre>";
	}
	
	function jsonHandler($errObj){
	
	$respond['error']['pear']['message'] = $errObj->getMessage();
	$respond['error']['pear']['UserInfo'] = $errObj->getUserInfo();
	
	
	if(IS_AJAX){

		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Content-type: application/json; charset=utf-8');

		echo json_encode($respond);
		exit;
	}else{
	
	print_r($errObj->getMessage());
	
	}	 
		 
	}