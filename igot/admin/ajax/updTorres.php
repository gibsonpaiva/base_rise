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
            // Cadastra uma Torre
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_TORRES." (idCastelo, idAlianca, NomeTorre) VALUES ({$_POST['idCastelo']}, {$_POST['idAlianca']}, '{$_POST['Torre']}')";
                // Executa a Query
                $db->query($sql);

                // Aguarda 1 segundo(s)
                sleep(1);

                // Identifica o ID do novo registro
                // SQL Query
                $sql = "SELECT idTorre, Reino, Castelo, Alianca, Torre FROM ".VIEW_TORRES." WHERE idCastelo={$_POST['idCastelo']} AND idAlianca={$_POST['idAlianca']} AND Torre='{$_POST['Torre']}' ORDER BY idTorre DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['idTorre'];
                    $Reino = $row['Reino'];
                    $Castelo = $row['Castelo'];
                    $Alianca = $row['Alianca'];
                    $Torre = $row['Torre'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Reino' => $Reino,
                    'Castelo' => $Castelo,
                    'Alianca' => $Alianca,
                    'Torre' => $Torre
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Torre cadastrada com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Evento
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_TORRES." SET idCastelo={$_POST['idCastelo']}, idAlianca={$_POST['idAlianca']}, NomeTorre='{$_POST['Torre']}' WHERE idTorre={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica os novos valores do registro atualizado
                // SQL Query
                $sql = "SELECT idTorre, Reino, Castelo, Alianca, Torre FROM ".VIEW_TORRES." WHERE idTorre={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['idTorre'];
                    $Reino = $row['Reino'];
                    $Castelo = $row['Castelo'];
                    $Alianca = $row['Alianca'];
                    $Torre = $row['Torre'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Reino' => $Reino,
                    'Castelo' => $Castelo,
                    'Alianca' => $Alianca,
                    'Torre' => $Torre
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Torre atualizada com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Evento
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_TORRES." WHERE idTorre={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Torre excluída com sucesso',
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