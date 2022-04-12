<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    
    // Inclue objetos referentes a bases de dados
    include_once "../../config/db.php";
    $database = new Database();
    // Conecta à base de dados
    $db = $database->open();   

    // SQL Query
    $sql = "INSERT INTO ".TBL_FORUMTOPICOS." (Assunto, idTipoTopico, Postagem, RegistradoPor) VALUES ('{$_POST['assunto']}','{$_POST['NewCategoria']}','{$_POST['postagem']}','{$_SESSION['user']['id']}')";
    // Executa a Query
    $db->query($sql);

    // Redireciona para a páginas Tópicos
    header("Location:  /../../forum/topicos.php"); 
?>