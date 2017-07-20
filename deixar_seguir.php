<?php
	session_start();

	if (!isset($_SESSION['login'])) {
	    header("Location:index.php?param=1");
	}

	require_once("classes/DAO/twitterDao.php");    

    $twitterDao = new twitterDAO();		
	
	$id_user = $_SESSION['codigo'];
	$deixar_seguir = $_POST['deixar_seguir'];

	if (empty($id_user) || empty($deixar_seguir)) {
		die();
	} 

	$twitterDao->unfollow($id_user, $deixar_seguir);	

?>