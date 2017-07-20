<?php	
	require_once("classes/DAO/usuarioDAO.php");
    require_once("classes/Entidades/usuario.php");

	$usuarioDAO = new usuarioDAO();
	$usuario = new Usuario();

	$req = filter_input_array(INPUT_POST, FILTER_DEFAULT);

	$user = $req['usuario'];
	$email = $req['email'];
	$senha = $req['senha'];

	//Validação do campo user

	if (empty($user)) {
		echo 'Você precisa preencher o campo Usuário';
		return false;
	}

	//Validação do campo E-mail

	$email = str_replace(" ", "", $email);
	$email = str_replace("/", "", $email);
	$email = str_replace("@.", "@", $email);
	$email = str_replace(".@", "@", $email);
	$email = str_replace(",", ".", $email);
	$email = str_replace(";", ".", $email);

	if (strlen($email) < 8 || substr_count($email, "@") != 1 || substr_count($email, ".") == 0) {
		echo "O E-mail digitador é inválido!";
		return false;
	}

	//Validação do campo senha

	if ($senha == '') {
		echo 'Você precisa preencher o campo Senha';
		return false;
	}

	if (strstr($senha, ' ') != false) {
		echo "A senha não pode conter espaços em branco.";	
		return false;
	}

	if (strlen($senha) < 6) {
		echo "A senha deve conter pelo menos 6 caracteres";
		return false;
	}

	//Checa se usuário já existe no BD

	$usuarioDAO->checharUser($user, $email);

	//Insere o usuário no banco de dados

	$cript = sha1(md5($senha));

	$usuario->setNome($user);
    $usuario->setEmail($email);
    $usuario->setSenha($cript);

    if ($usuarioDAO->cadastrar($usuario)) {
      echo "Cadastro realizado com sucesso!<br/>";
    }else{
      echo "Erro: E-mail já cadastrado no nosso sistema!";
    }	

    // Envia E-mail para usuário com login e senha

    require 'PHPMailer\PHPMailerAutoload.php';
	
	
	$mail = new PHPMailer(); 
	//$mail->SMTPDebug = true; //habilita o debug se parâmetro for true
	$mail->isSMTP(); //seta o tipo de protocolo
	$mail->Host = 'smtp.gmail.com'; //define o servidor smtp
	$mail->SMTPAuth = true; //habilita a autenticação via smtp
	$mail->SMTPOptions = [ 'ssl' => [ 'verify_peer' => false ] ];
	$mail->SMTPSecure = 'tls'; //tipo de segurança
	$mail->Port = 587; //porta de conexão
	
	//dados de autenticação no servidor smtp
	$mail->Username = ''; //usuário do smtp (email cadastrado no servidor)
	$mail->Password = ''; //senha ****CUIDADO PARA NÃO EXPOR ESSA INFORMAÇÃO NA INTERNET OU NO FÓRUM DE DÚVIDAS DO CURSO****
	
	//dados de envio de e-mail
	$mail->addAddress($email); //e-mails que receberam a mesagem
	
	//configuração da mensagem
	$mail->isHTML(true); //formato da mensagem de e-mail
	$mail->Subject = 'Bem vindo ao nosso sistema!'; //assunto
	$mail->Body    = '<html>
						<body>
							<h1>Seja bem vindo ao Twitter Clone</h1>
							<p>Usuário:' . $user . '</p><br/>
							<p>Senha:' . $senha . '</p><br/>
						</body>
					  </html>'; //Se o formato da mensagem for HTML você poderá utilizar as tags do HTML no corpo do e-mail
	$mail->AltBody = 'Caso não seja suportado o HTML, aqui vai a mensagem em texto'; //texto alternativo caso o html não seja suportado
	
	//envio e testes
	if(!$mail->send()) { //Neste momento duas ações são feitas, primeiro o send() (envio da mensagem) que retorna true ou false, se retornar false (não enviado) juntamente com o operador de negação "!" entra no bloco if.
		echo 'A mensagem não pode ser enviada ';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Uma mensagem com login e senha foi enviada para o seu E-mail!';
	}
?>