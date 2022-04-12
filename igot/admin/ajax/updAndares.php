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
            // Cadastra um novo Andar
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_ANDARES." (Andar, TipoGuerreiro, MedalhasConceder, MedalhasAcumuladas) VALUES ({$_POST['Andar']}, '{$_POST['Nome']}', {$_POST['MedalhasConceder']}, {$_POST['MedalhasAcumuladas']})";

                // Executa a Query
                $db->query($sql);

                // Identifica o ID do novo item para retorno
                // SQL Query
                $sql = "SELECT idAndar FROM ".TBL_ANDARES." WHERE TipoGuerreiro='{$_POST['Nome']}'";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $idAndar = $row['idAndar'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $idAndar,
                    'andar' => $_POST['Andar'],
                    'nome' => $_POST['Nome'],
                    'MedalhasConceder' => $_POST['MedalhasConceder'],
                    'MedalhasAcumuladas' => $_POST['MedalhasAcumuladas']
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Andar cadastrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Andar
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_ANDARES." SET Andar={$_POST['Andar']}, TipoGuerreiro='{$_POST['Nome']}', MedalhasConceder={$_POST['MedalhasConceder']}, MedalhasAcumuladas={$_POST['MedalhasAcumuladas']} WHERE idAndar={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $_POST['id'],
                    'andar' => $_POST['Andar'],
                    'nome' => $_POST['Nome'],
                    'MedalhasConceder' => $_POST['MedalhasConceder'],
                    'MedalhasAcumuladas' => $_POST['MedalhasAcumuladas']
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Andar atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui um Andar
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_ANDARES." WHERE idAndar={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Andar excluído com sucesso',
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