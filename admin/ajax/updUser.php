<?php
    // Verifica se possui permissão administrativa
    include_once "../../config/permissions.php"; // Inclue o objeto referente a verificação de permissão
    $EditableBy = new Permission();

    if($EditableBy->admin('rise')){ // Se acesso autorizado
        // Inclue objetos referentes a bases de dados
        include_once "../../config/db.php";
        // Instancia o objeto referente a base de dados
        $database = new Database();
        // Conecta à base de dados
        $db = $database->open();

        // Identifica o Tipo de ação a ser executada
        switch($_POST['action']){
            // Cadastra um novo Usuário
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_USUARIOS." (NomeUsuario, NomeExibicao, Departamento, Ativo) VALUES ('{$_POST['username']}', '{$_POST['displayname']}', '{$_POST['department']}', 1)";
                // Executa a Query
                $db->query($sql);

                // SQL Query
                $sql = "SELECT idUsuario AS id, NomeUsuario AS Usuario, NomeExibicao AS Nome, Departamento FROM ".TBL_USUARIOS." WHERE NomeUsuario='{$_POST['username']}' AND NomeExibicao='{$_POST['displayname']}' ORDER BY id DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $usuario = $row['Usuario'];
                    $nome = $row['Nome'];
                    $departamento = $row['Departamento'];
                }

                // Prepara os valores a serem retornados
                $userData = array(
                    'id' => $id,
                    'username' => $usuario,
                    'displayname' => $nome,
                    'department' => $departamento
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Usuário cadastrado com sucesso',
                    'data' => $userData
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza o Usuário
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_USUARIOS." SET NomeUsuario='{$_POST['username']}', NomeExibicao='{$_POST['displayname']}', Departamento='{$_POST['department']}' WHERE idUsuario={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // SQL Query
                $sql = "SELECT idUsuario AS id, NomeUsuario AS Usuario, NomeExibicao AS Nome, Departamento FROM ".TBL_USUARIOS." WHERE idUsuario={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $usuario = $row['Usuario'];
                    $nome = $row['Nome'];
                    $departamento = $row['Departamento'];
                }

                // Prepara os valores a serem retornados
                $userData = array(
                    'id' => $id,
                    'username' => $usuario,
                    'displayname' => $nome,
                    'department' => $departamento
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Usuário atualizado com sucesso',
                    'data' => $userData
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Usuário
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_USUARIOS." WHERE idUsuario={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Usuário removido com sucesso',
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;
        }

        // Encerra a conexão com a base de dados
        $database->close($db);

    } else { // Se acesso negado
        // Prepara mensagem de retorno
        $returnData = array(
            'status' => '',
            'msg' => 'Não autorizado!'
        );
        // Retorna os novos valores para atualização do DataGrid
        echo json_encode($returnData);
    }
?>