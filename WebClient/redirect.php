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
	
	function redirectPostFileFiscalizacao($url){
		
		if ($_FILES['imagem']['name']){
		$post = array('ramo_id' => $_POST['ramo_id'],
				'usuario_id' => $_POST['usuario_id'],
				'comentario' => $_POST['comentario'],
				'imagem'=>'@'.$_FILES['imagem']['tmp_name']);
		} else 
			$post = array('ramo_id' => $_POST['ramo_id'],
					'usuario_id' => $_POST['usuario_id'],
					'comentario' => $_POST['comentario']		
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result=curl_exec ($ch);
		curl_close ($ch);
		echo trim($result);
	}
	
	
	function redirectPostFileNewEnquete($url){
	
		if ($_FILES['imagem']['name']){
			$post = array('titulo' => $_POST['titulo'],
					'usuario_id' => $_POST['usuario_id'],
					'opt_1' => $_POST['opt_1'],
					'qtd_opt' => $_POST['qtd_opt'],
					'imagem'=>'@'.$_FILES['imagem']['tmp_name']);
		} else
			$post = array('titulo' => $_POST['titulo'],
					'usuario_id' => $_POST['usuario_id'],
					'opt_1' => $_POST['opt_1'],
					'qtd_opt' => $_POST['qtd_opt']
			);
			
			for ($i = 2; $i <= $_POST['qtd_opt']; $i++){
				$post[opt_.$i] = $_POST[opt_.$i];
			}
			
	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			$result=curl_exec ($ch);
			curl_close ($ch);
			echo trim($result);
	}

	
?>
