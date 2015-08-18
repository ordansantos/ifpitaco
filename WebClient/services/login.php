

<?php
	
	include("redirect.php");
	include("getRoot.php");

	$url = getRoot();

	$usuario = redirectPost($url."WebService/postLogin");

	if ($usuario != '0'){
		session_start();
                $usuario = json_decode($usuario);
		$_SESSION['id_usuario'] = $usuario->id_usuario;
                $_SESSION['foto'] = $usuario->perfil;
                $_SESSION['name'] = $usuario->nm_usuario;
                $_SESSION['grupo'] = $usuario->grupo;
                echo '1';
	}else{
            echo '0';
        }
	
	
?>
