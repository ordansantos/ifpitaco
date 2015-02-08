<?php
		
	function redirectPost ($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
		$data = trim(curl_exec($ch));
		curl_close($ch);
		return trim($data);
	}
	
	function redirectGet ($url){
		$cURL = curl_init($url);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		$data = trim(curl_exec($cURL));
		curl_close($cURL);
		return trim($data);
	}

	
?>
