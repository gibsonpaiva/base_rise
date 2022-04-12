<?php
	// Inicia uma Sessão se ainda não tiver iniciado
	if(session_id() == '') { session_start(); }

	// Autentica o Usuário no Domínio
	if(isset($_POST['username']) && isset($_POST['password'])) {
		// Instancia objeto com funções LDAP
		//// include_once 'ldap.php';
		//// $ldap = new LDAP();
		
		// Autentica o usuário
		//// if($ldap->openADConnect($_POST['username'], $_POST['password'])) {
			// Gera um novo ID para a sessão
			session_regenerate_id();
			//echo session_id();			

			// Instancia objeto com funções para manipulação da database
			include_once 'db.php';
			$db_rise = new RISE();
			$db_igot = new IGOT();

			// Obtém as propriedades do usuário através de consulta LDAP no AD
			//// $userProperties = $ldap->getObjectProperties($_POST['username'], array("displayname", "department", "title"));
			// Ecerra a conexão com o AD
			//// $ldap->closeADConnect();

			// Obtém dados do usuário corrente na base de dados
			$current_user = $db_rise->getUserByName($_POST['username']);
			// Se o usuário não existir na base de usuários cadastra o mesmo
			if(!isset($current_user)){
				// Cadastra o usuário na base do RISE
				$db_rise->newUser($_POST['username'], $userProperties[0]['displayname'][0], $userProperties[0]['department'][0]);
				// Obtém dados do usuário corrente, recém cadastrado
				$current_user = $db_rise->getUserByName($_POST['username']);
			}
			
			// Define as variáves de Sessão com as propriedades do Usuário corrente
			$_SESSION['user']['id'] = $current_user['idUsuario'];
			$_SESSION['user']['name'] = $current_user['NomeExibicao'];
			$_SESSION['user']['department'] = $current_user['Departamento'];
			$_SESSION['user']['title'] = $userProperties[0]['title'][0];
			foreach(array("rise", "igot") as $territorio){ // Grupos de que é membro por território
				$_SESSION[$territorio]['groups'] = $db_rise->getUserGroups($current_user['idUsuario'], $territorio);
			}
			// Define as variáveis de Sessão com as propriedades do Guerreiro (IGOT)
			$guerreiro = $db_igot->getGuerreiros($_SESSION['user']['id']);
			$_SESSION['igot']['Guerreiro'] = $guerreiro[0];

			// Redireciona para a página inicial
			header("Location: /home.php");
    	} else {
			// Monta mensagem dinâmica
			$_SESSION['message'] = "
			<div class='alert alert-danger' role='alert'> Usuário e/ou senha incorreto(s). 
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>&times;</span>
				</button>
			</div>";
			// Redireciona para a página inicial
    		header("Location: ../index.php");
    	}
	//// }
?>