<?php

	session_start();

	if (!isset($_SESSION['login'])) {
	    header("Location:index.php?param=1");
	}

	require_once("classes/DAO/twitterDao.php");    

    $twitterDao = new twitterDAO();		
	
	$id_user = $_SESSION['codigo'];
	$seguir_id_usuario = $_POST['seguir_id_usuario'];

	if (empty($id_user) || empty($seguir_id_usuario)) {
		die();
	} 

	$twitterDao->seguir($id_user, $seguir_id_usuario);	

?>