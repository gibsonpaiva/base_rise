<?php
    // Inclue objetos referentes a bases de dados
    include_once "db.php";
    // Instancia o objeto referente a base de dados
    $database = new Database();

    // Nome das Tabelas
    $tbl_perfil = "filebox.rise_usuarios";

    // Monta a Query com base na tabela solicitada
    switch($_GET['content']){
        case 'imagem':
            // Identifica o Dia e Horário atual
            $now = date("Y-m-d H:i:s");
            // Identifica o Usuário corrente
            $user = $_POST['user'];
            // Prepara a imagem para Upload
            $image = addslashes(file_get_contents($_FILES['imagem']['tmp_name']));
            $nome = $_FILES['imagem']['name'];
            $size = $_FILES['imagem']['size'];
            $type = $_FILES['imagem']['type'];
            // SQL Query
            $sql = "SELECT Arquivo FROM $tbl_perfil WHERE idUsuario=$user";

            // Executa a Query
            $result = $database->run($sql);
            // Verifica se o usuário já possui imagem no banco
            if(($result->num_rows)>0){ // Atualiza imagem existente
                // SQL Query
                $sql = "UPDATE $tbl_perfil SET `Nome` = '$nome', `Arquivo` = '$image', `Formato`='$type', `Tamanho`='$size', `ModificadoEm`='$now' WHERE `idUsuario` = $user";
            }
            else{ // Insere uma nova imagem
                // SQL Query
                $sql = "INSERT INTO $tbl_perfil (Nome, Arquivo, Formato, Tamanho, idUsuario, ModificadoEm) values ('$nome','$image','$type','$size','$user','$now')";
            }
            // Executa a Query
            $result = $database->run($sql);
            // Retorna para página inicial            
            header("Location: ../home.php");
        break;
    }
?>