<?php
    // Inclue objetos referentes as Permissões
    include_once "../../../config/permissions.php";
    $EditableBy = new Permission();

    if($EditableBy->admin('igot')){ // Se acesso autorizado
        // Inclue objetos referentes a bases de dados
        include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
        // Instancia o objeto referente a base de dados
        $database = new Database();
        // Conecta à base de dados
        $db = $database->open();

        // Identifica o Tipo de ação a ser executada
        switch($_POST['action']){
            // Cadastra um novo Reino
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_REINOS." (NomeReino, idExercito) VALUES ('{$_POST['Nome']}', {$_POST['idExercito']})";

                // Executa a Query
                $db->query($sql);

                // Identifica o ID do novo item para retorno
                // SQL Query
                $sql = "SELECT idReino FROM ".TBL_REINOS." WHERE NomeReino='{$_POST['Nome']}'";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $idReino = $row['idReino'];
                }

                // Identifica o Exército do novo item para retorno
                // SQL Query
                $sql = "SELECT NomeExercito FROM ".TBL_EXERCITOS." WHERE idExercito='{$_POST['idExercito']}'";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $Exercito = $row['NomeExercito'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $idReino,
                    'nome' => $_POST['Nome'],
                    'exercito' => $Exercito
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Reino cadastrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Item de Menu
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_REINOS." SET NomeReino='{$_POST['Nome']}', idExercito={$_POST['idExercito']}";
                if(isset($_POST['Descricao'])){$sql .= ", Descricao='{$_POST['Descricao']}'";}                
                $sql .= " WHERE idReino={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica o Exército para retorno
                // SQL Query
                $sql = "SELECT NomeExercito FROM ".TBL_EXERCITOS." WHERE idExercito='{$_POST['idExercito']}'";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $Exercito = $row['NomeExercito'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $_POST['id'],
                    'nome' => $_POST['Nome'],
                    'exercito' => $Exercito
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Reino atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui um Item de Menu
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_REINOS." WHERE idReino={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Reino excluído com sucesso',
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