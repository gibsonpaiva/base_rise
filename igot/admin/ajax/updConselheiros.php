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
            // Cadastra um novo Conselheiro
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_CONSELHEIROSDOSREINOS." (idGuerreiro, idReino, idPatente) VALUES ({$_POST['idConselheiro']}, {$_POST['idReino']}, {$_POST['idPatente']})";
                // Executa a Query
                $db->query($sql);

                // Identifica o ID do novo Conselheiro para retorno
                // SQL Query
                $sql = "SELECT id, Conselheiro, Reino, Patente FROM ".VIEW_CONSELHEIROSDOSREINOS." ORDER BY id DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $conselheiro = $row['Conselheiro'];
                    $reino = $row['Reino'];
                    $patente = $row['Patente'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Conselheiro' => $conselheiro,
                    'Reino' => $reino,
                    'Patente' => $patente
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Conselheiro cadastrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza o Conselheiro
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_CONSELHEIROSDOSREINOS." SET idGuerreiro='{$_POST['idConselheiro']}', idReino={$_POST['idReino']}, idPatente={$_POST['idPatente']} WHERE idConselheiroReino={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica o nome do Conselheiro e do Reino para retorno
                // SQL Query
                $sql = "SELECT id, Conselheiro, Reino, Patente FROM ".VIEW_CONSELHEIROSDOSREINOS." WHERE id={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $conselheiro = $row['Conselheiro'];
                    $reino = $row['Reino'];
                    $patente = $row['Patente'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Conselheiro' => $conselheiro,
                    'Reino' => $reino,
                    'Patente' => $patente
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Conselheiro atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Conselheiro
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_CONSELHEIROSDOSREINOS." WHERE idConselheiroReino={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Conselheiro excluído com sucesso',
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