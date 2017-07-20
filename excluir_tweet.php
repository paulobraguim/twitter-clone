<?php

	session_start();

	if (!isset($_SESSION['login'])) {
	    header("Location:index.php?param=1");
	}

	require_once("classes/DAO/twitterDao.php");

	 $twitterDao = new twitterDAO();

	 $id_user = $_SESSION['codigo'];
	 $id_tweet = $_POST['id_tweet'];

	 if (empty($id_user) || empty($id_tweet)) {
		die();
	} 

	$twitterDao->excluirTweet($id_user, $id_tweet);

?>