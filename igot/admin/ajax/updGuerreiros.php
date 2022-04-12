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
            // Cadastra um novo Guerreiro
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_GUERREIROS." (idExercito, idUsuario, Ativo) VALUES ({$_POST['idExercito']}, {$_POST['idUsuario']}, {$_POST['Status']})";
                // Executa a Query
                $db->query($sql);

                // Identifica o ID do novo Guerreiro para retorno
                // SQL Query
                $sql = "SELECT id, Guerreiro, Exercito, Ativo FROM ".VIEW_GUERREIROS." ORDER BY id DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $guerreiro = $row['Guerreiro'];
                    $exercito = $row['Exercito'];
                    if($row['Ativo']) {
                        $status = "Ativo";
                    } else {
                        $status = "Inativo";
                    }
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Status' => $status,
                    'Guerreiro' => $guerreiro,
                    'Exercito' => $exercito
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Guerreiro cadastrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza o Conselheiro
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_GUERREIROS." SET idExercito='{$_POST['idExercito']}', idUsuario={$_POST['idUsuario']}, Ativo={$_POST['Status']} WHERE idGuerreiro={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica o nome do Guerreiro e do Exercito para retorno
                // SQL Query
                $sql = "SELECT id, Guerreiro, Exercito, Ativo FROM ".VIEW_GUERREIROS." WHERE id={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $guerreiro = $row['Guerreiro'];
                    $exercito = $row['Exercito'];
                    if($row['Ativo']) {
                        $status = "Ativo";
                    } else {
                        $status = "Inativo";
                    }
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Status' => $status,
                    'Guerreiro' => $guerreiro,
                    'Exercito' => $exercito
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Guerreiro atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Guerreiro
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_GUERREIROS." WHERE idGuerreiro={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Guerreiro excluído com sucesso',
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