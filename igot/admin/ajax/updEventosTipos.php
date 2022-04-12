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
            // Cadastra um Tipo de Evento
            case 'insert':
                // SQL Query
                $sql = "INSERT INTO ".TBL_EVENTOSTIPOS." (idCategoria, idAlianca, Tipo, DataInicio, DataFim, LinkInscricao) VALUES ({$_POST['idCategoria']}, {$_POST['idAlianca']}, '{$_POST['Tipo']}', '{$_POST['DataInicio']}', '{$_POST['DataFim']}', '{$_POST['LinkInscricao']}')";
                // Executa a Query
                $db->query($sql);

                // Aguarda 1 segundo(s)
                sleep(1);

                // Identifica o ID do novo registro
                // SQL Query
                $sql = "SELECT id, Categoria, Alianca, Tipo, DataInicio, DataFim, LinkInscricao FROM ".VIEW_EVENTOSTIPOS." WHERE idCategoria={$_POST['idCategoria']} AND idAlianca={$_POST['idAlianca']} AND Tipo='{$_POST['Tipo']}' AND DataInicio_YMD='{$_POST['DataInicio']}' AND DataFim_YMD='{$_POST['DataFim']}' AND LinkInscricao='{$_POST['LinkInscricao']}' ORDER BY id DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Categoria = $row['Categoria'];
                    $Alianca = $row['Alianca'];
                    $Tipo = $row['Tipo'];
                    $DataInicio = $row['DataInicio'];
                    $DataFim = $row['DataFim'];
                    $LinkInscricao = $row['LinkInscricao'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Categoria' => $Categoria,
                    'Alianca' => $Alianca,
                    'Tipo' => $Tipo,
                    'DataInicio' => $DataInicio,
                    'DataFim' => $DataFim,
                    'LinkInscricao' => $LinkInscricao,
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Tipo de Evento cadastrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Evento
            case 'update':
                // SQL Query
                $sql = "UPDATE ".TBL_EVENTOSTIPOS." SET idCategoria={$_POST['idCategoria']}, idAlianca={$_POST['idAlianca']}, Tipo='{$_POST['Tipo']}', DataInicio='{$_POST['DataInicio']}', DataFim='{$_POST['DataFim']}', LinkInscricao='{$_POST['LinkInscricao']}' WHERE idTipo={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica os novos valores do registro atualizado
                // SQL Query
                $sql = "SELECT id, Categoria, Alianca, Tipo, DataInicio, DataFim, LinkInscricao FROM ".VIEW_EVENTOSTIPOS." WHERE id={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Categoria = $row['Categoria'];
                    $Alianca = $row['Alianca'];
                    $Tipo = $row['Tipo'];
                    $DataInicio = $row['DataInicio'];
                    $DataFim = $row['DataFim'];
                    $LinkInscricao = $row['LinkInscricao'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Categoria' => $Categoria,
                    'Alianca' => $Alianca,
                    'Tipo' => $Tipo,
                    'DataInicio' => $DataInicio,
                    'DataFim' => $DataFim,
                    'LinkInscricao' => $LinkInscricao
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Tipo de Evento atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Evento
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_EVENTOSTIPOS." WHERE idTipo={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Tipo de Evento excluído com sucesso',
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