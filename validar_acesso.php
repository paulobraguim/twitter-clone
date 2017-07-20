<?php
	
	require_once("classes/DAO/usuarioDAO.php");
	$usuarioDAO = new usuarioDAO();

	$req = filter_input_array(INPUT_POST, FILTER_DEFAULT);

	$user = $req['usuario'];	
	$senha = $req['senha'];
		
	$usuarioDAO->validaAcesso($user, $senha);

?>