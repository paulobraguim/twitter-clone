<?php
	session_start();

	if (!isset($_SESSION['login'])) {
	    header("Location:index.php?param=1");
	}

	//Quantidade de tweets
	
	require_once("classes/DAO/twitterDao.php");    

    $twitterDao = new twitterDAO();		

    $id_user = $_SESSION['codigo'];

    //Quantidade de Seguidores

    $twitterDaoSeg = new twitterDAO();
?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">

		<title>Twitter clone</title>
		
		<!-- jquery - link cdn -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

		<!-- bootstrap - link cdn -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

		<script type="text/javascript">
			$(document).ready(function(){
				//Associar o evento click ao botão

				$('#btn_procurar_pessoas').click(function(){

					if ($('#nome_pessoa').val().length > 0) {
						$.ajax({
							url: 'get_pessoas.php',
							method:'post',
							data: $('#form_procurar_pessoas').serialize(),
							success: function(data){
								$('#pessoas').html(data);

								$('.btn_seguir').click(function(){
									var id_user = $(this).data('id_usuario');

									$('#btn_seguir_'+id_user).hide();
									$('#btn_deixar_seguir_'+id_user).show();
									
									$.ajax({
										url:'seguir.php',
										method: 'post',
										data:{seguir_id_usuario: id_user},
										success: function(data){											
										}
									});
								});

								$('.btn_deixar_seguir').click(function(){
									var id_deixar_user = $(this).data('id_usuario');

									$('#btn_seguir_'+id_deixar_user).show();
									$('#btn_deixar_seguir_'+id_deixar_user).hide();

									$.ajax({
										url: 'deixar_seguir.php',
										method: 'post',
										data:{deixar_seguir: id_deixar_user},
										success: function(data){											
										}
									});
								});
							}
						});
					}

				});

			});
		</script>
	
	</head>

	<body>

		<!-- Static navbar -->
	    <nav class="navbar navbar-default navbar-static-top">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <img src="imagens/icone_twitter.png" />
	        </div>
	        
	        <div id="navbar" class="navbar-collapse collapse">
	          <ul class="nav navbar-nav navbar-right">
	          	<li><a href="home.php">Home</a></li>
	            <li><a href="logout.php">Sair</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>


	    <div class="container">

	    	<div class="col-md-3">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<h4><?= $_SESSION['login'] ?></h4>

	    				<hr />

	    				<div class="col-md-6">
	    					TWEETS<br/> <?php echo $twitterDao->qtdTweets($id_user); ?>
	    				</div>

	    				<div class="col-md-6">
	    					SEGUIDORES<br /> <?php echo $twitterDaoSeg->qtdSeguidores($id_user); ?>
	    				</div>
	    			</div>
	    		</div> 
	    	</div>

			<div class="col-md-6">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<form id="form_procurar_pessoas" class="input-group">
	    					<input type="text" id="nome_pessoa" name="nome_pessoa" class="form-control" placeholder="Quem você está procurando?" maxlength="140" />
	    					<span class="input-group-btn">
	    						<button class="btn btn-default" id="btn_procurar_pessoas" type="button">Procurar</button>
	    					</span>
	    				</form>
	    			</div>
	    		</div>

	    		<div id="pessoas" class="list-group"></div>
			</div>

			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-body">
						
					</div>
				</div>
			</div>
		</div>


	    </div>
	
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	</body>
</html>