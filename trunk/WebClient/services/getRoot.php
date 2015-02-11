<?php
	
	function getRoot(){
		
		$protocol = 'http://';
		
		$hostname = getenv('HTTP_HOST');
		//return $protocol.$hostname.'/';
		return 'localhost/';
	}
	
?>
