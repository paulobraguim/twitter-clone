<?php

	session_start();

	if (!isset($_SESSION['login'])) {
	    header("Location:index.php?param=1");
	}

	require_once("classes/DAO/twitterDao.php");
	
    
    $twitterDAO = new twitterDAO();

	$id_user = $_SESSION['codigo'];

	$twitterDAO->buscarTweets($id_user);

?>