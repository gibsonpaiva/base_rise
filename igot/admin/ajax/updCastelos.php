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
            // Cadastra um novo Castelo
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_CASTELOS." (idReino, NomeCastelo) VALUES ({$_POST['idReino']}, '{$_POST['Castelo']}')";
                // Executa a Query
                $db->query($sql);

                // Identifica o ID do novo Castelo e Nome do Reino em que o mesmo pertence para retorno
                // SQL Query
                $sql = 
                "SELECT
                    castelos.idCastelo AS 'idCastelo',
                    reinos.NomeReino AS 'NomeReino'
                FROM ".TBL_CASTELOS." AS castelos
                    INNER JOIN ".TBL_REINOS." AS reinos ON castelos.idReino = reinos.idReino
                WHERE castelos.NomeCastelo='{$_POST['Castelo']}'";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $idCastelo = $row['idCastelo'];
                    $Reino = $row['NomeReino'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $idCastelo,
                    'Castelo' => $_POST['Castelo'],
                    'Reino' => $Reino
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Castelo cadastrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza o Castelo
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_CASTELOS." SET idReino={$_POST['idReino']}, NomeCastelo='{$_POST['Castelo']}' WHERE idCastelo={$_POST['idCastelo']}";
                // Executa a Query
                $db->query($sql);

                // Identifica o nome do Reino em que o Castelo pertence para retorno
                // SQL Query
                $sql = "SELECT NomeReino FROM ".TBL_REINOS." WHERE idReino={$_POST['idReino']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $Reino = $row['NomeReino'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'Castelo' => $_POST['Castelo'],
                    'Reino' => $Reino
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Castelo atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Castelo
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_CASTELOS." WHERE idCastelo={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Castelo excluído com sucesso',
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