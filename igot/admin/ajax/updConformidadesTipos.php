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
            // Cadastra um Tipo de Conformidade
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_CONFORMIDADESTIPOS." (TipoConformidade) VALUES ('{$_POST['Nome']}')";
                // Executa a Query
                $db->query($sql);

                // Aguarda 1 segundo(s)
                sleep(1);

                // Identifica o ID do novo registro
                // SQL Query
                $sql = "SELECT idTipoConformidade AS id, TipoConformidade AS Nome FROM ".TBL_CONFORMIDADESTIPOS." WHERE TipoConformidade='{$_POST['Nome']}' ORDER BY id DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Nome = $row['Nome'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'nome' => $Nome
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Tipo de Conformidade cadastrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Conformidade
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_CONFORMIDADESTIPOS." SET TipoConformidade='{$_POST['Nome']}' WHERE idTipoConformidade={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica os novos valores do registro atualizado
                // SQL Query
                $sql = "SELECT idTipoConformidade AS id, TipoConformidade AS Nome FROM ".TBL_CONFORMIDADESTIPOS." WHERE idTipoConformidade={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Nome = $row['Nome'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'nome' => $Nome
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Tipo de Conformidade atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Conformidade
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_CONFORMIDADESTIPOS." WHERE idTipoConformidade={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Tipo de Conformidade excluído com sucesso',
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