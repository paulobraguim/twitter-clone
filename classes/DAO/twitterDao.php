<?php
	
	require_once("db.class.php");

	class twitterDAO{
		public function __construct(){
			$this->con = new db();
			$this->pdo = $this->con->connect();
		}

		function tweetar(Tweet $tweet){
			try{
				$stmt = $this->pdo->prepare("INSERT INTO tweet (id_usuario, tweet) VALUES(?, ?)");

				$stmt->bindValue(1, $tweet->getId());
				$stmt->bindValue(2, $tweet->getTweet());

				return $stmt->execute();
			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}
		}

		function excluirTweet($id_usuario, $id_tweet){
			try{
				$stmt = $this->pdo->prepare("DELETE FROM tweet WHERE id_usuario = ? AND id_tweet = ?");

				$stmt->bindValue(1, $id_usuario);
				$stmt->bindValue(2, $id_tweet);

				return $stmt->execute();
			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}
		}

		function buscarTweets($id){			
			try{
				$stmt = $this->pdo->prepare("SELECT DATE_FORMAT(t.data_inclusao, '%d %b %Y %T') AS data_inclusao, t.tweet, t.id_tweet, t.id_usuario, u.usuario, u.id FROM tweet AS t JOIN usuarios AS u ON(t.id_usuario = u.id) WHERE id_usuario = ? OR id_usuario IN(SELECT seguindo_id_usuario FROM usuarios_seguidores WHERE id_usuario = ?) ORDER BY data_inclusao DESC");

				$stmt->bindValue(1, $id);
				$stmt->bindValue(2, $id);
				$stmt->execute();

				$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if (count($tweets) > 0) {
					
					foreach ($tweets as $key => $value) {
						echo '<a href="#" class="list-group-item">';
							echo '<h4 class="list-group-item-heading">'. $value['usuario'] .'<small> - ' . $value["data_inclusao"] . '</small></h4>';
							echo '<p class="list-group-item-text">'.$value['tweet'] . '<br/><br/>';

							if ($id == $value['id_usuario']) {		
								echo '<span class="input-group-btn">';
															
								echo'<button type="button" id="btn_exc_'.$value['id_tweet'].'" class="btn btn-primary btn_exc" data-id_twitter="'. $value['id_tweet'] .'">Excluir o tweet</button>';	
															
								echo '</span>';				
								
							}							
							
							echo'</p>';
						echo '</a>';
					}
					
				}else{				
					echo "Erro ao tentar carregar os tweets!";
				}

			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}
		}

		function buscarPessoas($parametro, $id){
			try{
				$stmt = $this->pdo->prepare("SELECT u.*, us.* FROM usuarios AS u LEFT JOIN usuarios_seguidores AS us ON(us.id_usuario = ? AND u.id = us.seguindo_id_usuario) WHERE u.usuario like ? AND u.id <> ?;");

				$stmt->bindValue(1, $id);
				$stmt->bindValue(2, '%'.$parametro.'%');
				$stmt->bindValue(3, $id);
				$stmt->execute();

				$pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if (count($pessoas) > 0) {
					foreach ($pessoas as $key => $value) {
						echo '<a href="#" class="list-group-item">';
							echo '<strong>'. $value['usuario'] .'</strong> <small> - '. $value['email'] .'</small>';
							echo '<p class="list-group-item-text pull-right">';

							$esta_seguindo = isset($value['id_usuario_seguidor']) && !empty($value['id_usuario_seguidor']) ? 'S' : 'N';
							$btn_seguir_display = 'block';
							$btn_deixar_seguir_display = 'block';

							if ($esta_seguindo == 'N') {
								$btn_deixar_seguir_display = 'none';
							}else{
								$btn_seguir_display = 'none';
							}
							
							echo'<button type="button" style="display:'.$btn_seguir_display.'" id="btn_seguir_'.$value['id'].'" class="btn btn-default btn_seguir" data-id_usuario="'. $value['id'] .'">Seguir</button>';

							echo'<button type="button" style="display:'.$btn_deixar_seguir_display.'" id="btn_deixar_seguir_'.$value['id'].'" class="btn btn-primary btn_deixar_seguir" data-id_usuario="'. $value['id'] .'">Deixar de seguir</button>';
							echo'</p>';
							echo '<div class="clearfix"></div>';
						echo '</a>';
					}
				}else{
					echo 'Erro ao procurar pessoas: Não encontramos pessoas com este nickname';
				}
			}catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}

		function seguir($id_usuario, $seguir_id_usuario){
			try{
				$stmt = $this->pdo->prepare("INSERT INTO usuarios_seguidores (id_usuario, seguindo_id_usuario) VALUES(?, ?)");

				$stmt->bindValue(1, $id_usuario);
				$stmt->bindValue(2, $seguir_id_usuario);

				return $stmt->execute();
			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}	
		}

		function unfollow($id_usuario, $deixar_seguir){
			try{
				$stmt = $this->pdo->prepare("DELETE FROM usuarios_seguidores WHERE id_usuario = ? AND seguindo_id_usuario = ?");

				$stmt->bindValue(1, $id_usuario);
				$stmt->bindValue(2, $deixar_seguir);

				return $stmt->execute();
			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}	
		}

		function qtdTweets($id){
			try{
				$stmt = $this->pdo->prepare("SELECT COUNT(*) AS qtd_tweets FROM tweet WHERE id_usuario = ?");

				$stmt->bindValue(1, $id);				

				$stmt->execute();
				
				$quantidade =  $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$qtd = 0;

				foreach ($quantidade as $key => $value) {
					$qtd += $value['qtd_tweets'];
				}

				return $qtd;

			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}
		}

		function qtdSeguidores($id){
			try{
				$stmt = $this->pdo->prepare("SELECT COUNT(*) AS qtd_seguidores FROM usuarios_seguidores WHERE seguindo_id_usuario = ?");

				$stmt->bindValue(1, $id);				

				$stmt->execute();
				
				$quantidade =  $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$qtd = 0;

				foreach ($quantidade as $key => $value) {
					$qtd += $value['qtd_seguidores'];
				}

				return $qtd;

			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}
		}	
	}

?>