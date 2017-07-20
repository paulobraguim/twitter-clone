<?php
	date_default_timezone_set('America/Sao_Paulo');

	class db{
		private $host;
		private $user;
		private $pass;
		private $db;
		private $file;
		public $pdo;

		function connect(){
			try{
				$this->host = 'localhost';
				$this->user = 'root';
				$this->pass = '';
				$this->db = 'db_twitter';

				$parametros = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"); //Definimos a conexão com o banco no padrão UTF-8

				$this->file = "mysql:host=" . $this->host . ";dbname=" . $this->db;

				$this->pdo = new PDO($this->file, $this->user, $this->pass, $parametros);

				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        		$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        		$this->pdo->setAttribute(PDO::ATTR_PERSISTENT, true);

        		if (!$this->pdo) {
           			echo "Erro na conexão";
       			}

        		return $this->pdo;

			}catch(PDOException $e){
				echo 'Erro ao tentar se conectar: ' . $e->getMessage();
			}
		}
		
	}

?>