<?php
    // Inicia uma Sessão se ainda não tiver iniciado
    if(session_id() == '') { session_start(); }

	// Verifica se o usuário está autenticado
	if(isset($_SESSION['user']['id'])) {
        // Redefine todas as variaveis da Sessão
        session_unset();
        // Encerra a Sessão
        session_destroy();

        // Redireciona para a página inicial			
		header("Location: /index.php");
	}
?>
