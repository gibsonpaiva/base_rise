<?php
    // Inicia uma Sessão se ainda não tiver iniciado
    if(session_id() == '') { session_start(); }

    // Verifica se o usuário está autenticado
	if(isset($_SESSION['user']['id'])) {
        // Inclue objetos referentes a bases de dados
        include_once "config/db.php";
        $db_filebox = new FileBox();
        // Carrega o Arquivo
        $arquivo = $db_filebox->loadFile($_GET['id']);
        // Prepara o Arquivo para Exibição
        /*switch(strtolower(pathinfo($arquivo['Nome']))){ // Define o tipo de conteudo conforme a extensão do arquivo
            case "pdf": $ctype = "application/pdf"; break;
            case "jpg": $ctype = "image/jpeg"; break;
            case "png": $ctype = "image/png"; break;
            case "gif": $ctype = "image/gif"; break;
            default: $ctype = "application/force-download"; break;
		}
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private");
		header("Content-Type: {$ctype}");
		header("Content-Disposition: attachment; filename={$arquivo['Nome']};");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: {$arquivo['Tamanho']}");
		//ob_clean();
        //flush();*/
        header("Content-type: {$arquivo['Formato']}");
        header("Content-lenght: {$arquivo['Tamanho']}");
        header("Content-Disposition: attachment; filename={$arquivo['Nome']};");
        header("Content-Description: PHP Generated Data");
        // Exibe o Arquivo
        echo $arquivo['Arquivo'];
    } else {
        // Caso não esteja conectado, redireciona para a página inicial
        header("Location: /index.php");
    }
?>