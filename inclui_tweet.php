<?php
	session_start();

	if (!isset($_SESSION['login'])) {
	    header("Location:index.php?param=1");
	}

	require_once("classes/DAO/twitterDao.php");
    require_once("classes/Entidades/tweet.php");

    $twitterDao = new twitterDAO();
	$tweet = new Tweet();
	
	$texto = $_POST['texto_tweet'];
	$id_user = $_SESSION['codigo'];

	if (empty($texto) || empty($id_user)) {
		die();
	} 

	$tweet->setId($id_user);
	$tweet->setTweet($texto);

	$twitterDao->tweetar($tweet);	

?>