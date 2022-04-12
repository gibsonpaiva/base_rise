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
            // Cadastra uma nova Aliança
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_ALIANCAS." (NomeAlianca) VALUES ('{$_POST['Nome']}')";

                // Executa a Query
                $db->query($sql);

                // Identifica o ID do novo item para retorno
                // SQL Query
                $sql = "SELECT idAlianca FROM ".TBL_ALIANCAS." WHERE NomeAlianca='{$_POST['Nome']}'";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $idAlianca = $row['idAlianca'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $idAlianca,
                    'nome' => $_POST['Nome']
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Aliança cadastrada com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Item de Menu
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_ALIANCAS." SET NomeAlianca='{$_POST['Nome']}' WHERE idAlianca={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $_POST['id'],
                    'nome' => $_POST['Nome']
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Aliança atualizada com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui um Item de Menu
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_ALIANCAS." WHERE idAlianca={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Aliança excluída com sucesso'
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