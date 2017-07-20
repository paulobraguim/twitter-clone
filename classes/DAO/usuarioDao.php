<?php
	require_once("db.class.php");

	class usuarioDAO{
		public function __construct(){
			$this->con = new db();
			$this->pdo = $this->con->connect();
		}

		function cadastrar(Usuario $usuario){
			try{
				$stmt = $this->pdo->prepare("INSERT INTO usuarios (usuario, email, senha) VALUES(?, ?, ?)");

				$stmt->bindValue(1, $usuario->getNome());
				$stmt->bindValue(2, $usuario->getEmail());
				$stmt->bindValue(3, $usuario->getSenha());

				return $stmt->execute();
			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}

		}

		function validaAcesso($user, $senha){

			session_start();

			try{
				$stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE BINARY usuario = ? AND senha = ?');

				$stmt->bindParam(1, $user);

				$cript = sha1(md5($senha));
				
				$stmt->bindParam(2, $cript);

				$stmt->execute();

				$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if (count($users) <= 0) {
					
					header('Location: index.php?param=1');
					
				}else{				
					foreach ($users as $key => $value) {
						$_SESSION['login'] = $value['usuario'];
						$_SESSION['codigo'] = $value['id'];
					}
					
					header('Location: home.php');
				}
			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}
		}

		function checharUser($user, $email){
			$user_exist = false;
			$email_exist = false;
			try{				
				$stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE BINARY usuario = ?');

				$stmt->bindParam(1, $user);

				$stmt->execute();

				$checar = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if (count($checar) > 0) {					
					$user_exist = true;
				}

				$stmt2 = $this->pdo->prepare('SELECT * FROM usuarios WHERE BINARY email = ?');

				$stmt2->bindParam(1, $email);

				$stmt2->execute();

				$checarEmail = $stmt2->fetchAll(PDO::FETCH_ASSOC);

				if (count($checarEmail) > 0) {					
					$email_exist = true;					
				}

				if ($user_exist || $email_exist) {
					$retorno = '';

					if ($user_exist) {
						$retorno .= 'erro_user=1&';
					}

					if ($email_exist) {
						$retorno .= 'erro_email=1&';
					}

					header('Location: inscrevase.php?'.$retorno);

					die();
				}

				return true;
				

			}catch(PDOException $e){
				echo "Erro: " . $e->getMessage();
			}
		}

	}
?>