<?php
	session_start();

	if (!isset($_SESSION['login'])) {
	    header("Location:index.php?param=1");
	}

	//Quantidade de tweets
	
	require_once("classes/DAO/twitterDao.php");    

    $twitterDao = new twitterDAO();		

    $id_user = $_SESSION['codigo'];

   echo 'TWEETS<br/>' . $twitterDao->qtdTweets($id_user);

?>
