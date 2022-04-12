<?php
    // Define o Fuso Horário
    date_default_timezone_set('America/Sao_Paulo');

    // Inclue objetos referentes as Permissões
    include_once "../../../config/permissions.php";
    $EditableBy = new Permission();

    if($EditableBy->admin('igot') || $EditableBy->general() || $EditableBy->guerreiro($_POST['idGuerreiro'])){ // Se acesso autorizado
        // Inclue objetos referentes a bases de dados
        include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
        // Instancia o objeto referente a base de dados
        $database = new Database();
        // Conecta à base de dados
        $db = $database->open();

        // Identifica o Tipo de ação a ser executada
        switch($_POST['action']){
            // Associa um Guerreiro a uma Torre
            case 'insert':
                // Verifica se trata de um alistamento para definição do Status de Aprovação padrão
                if($_POST['idPosicao']==1){ $StatusAprovacao=3; } // Status - Aprovado
                else { // Caso contrário define o Status de Aprovação padrão baseado na permissão do Usuário 
                    if($EditableBy->admin('igot') || $EditableBy->general()){
                        if(!isset($_POST['idStatusAprovacao'])){ // Verifica se foi informado algum Status de Aprovação
                            $StatusAprovacao = 3; // Status - Aprovado
                        } else {
                            $StatusAprovacao = $_POST['idStatusAprovacao'];
                        }
                    } else {
                        $StatusAprovacao=1; // Status - Pendente Aprovação
                    }
                }
                // SQL Query baseada na permissão
                $sql = "INSERT INTO ".TBL_GUERREIROSNASTORRES." (idGuerreiro, idTorre, idAndar, Proprietario, StatusAprovacao, RegistradoPor) VALUES ({$_POST['idGuerreiro']}, {$_POST['idTorre']}, {$_POST['idPosicao']}, {$_SESSION['user']['id']}, {$StatusAprovacao}, {$_SESSION['user']['id']})";
                // Executa a Query
                $db->query($sql);

                // Identifica o ID da nova associação de Guerreiro com Torre
                // SQL Query
                $sql = "SELECT id, Guerreiro, Reino, Castelo, Alianca, Torre, Posicao, Medalhas, StatusAprovacao FROM ".VIEW_GUERREIROSNASTORRES." ORDER BY id DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Guerreiro = $row['Guerreiro'];
                    $Reino = $row['Reino'];
                    $Castelo = $row['Castelo'];
                    $Alianca = $row['Alianca'];
                    $Torre = $row['Torre'];
                    $Posicao = $row['Posicao'];
                    $Medalhas = $row['Medalhas'];
                    $StatusAprovacao = $row['StatusAprovacao'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Guerreiro' => $Guerreiro,
                    'Reino' => $Reino,
                    'Castelo' => $Castelo,
                    'Alianca' => $Alianca,
                    'Torre' => $Torre,
                    'Posicao' => $Posicao,
                    'Medalhas' => $Medalhas,
                    'StatusAprovacao' => $StatusAprovacao
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Guerreiro associado a torre com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza a associação de Guerreiro com Torre
            case 'update':
                // Identifica o Dia e Horário atual
                $now = date('Y-m-d H:i:s', time());
                // Define o Status de Aprovação padrão baseado na permissão do Usuário
                if($EditableBy->admin('igot') || $EditableBy->general()){
                    if(!isset($_POST['idStatusAprovacao'])){ // Verifica se foi informado algum Status de Aprovação
                        $StatusAprovacao = 3; // Status - Aprovado
                    } else {
                        $StatusAprovacao = $_POST['idStatusAprovacao'];
                    }
                } else {
                    $StatusAprovacao=1; // Status - Pendente Aprovação
                }
                // SQL Query
                $sql = "UPDATE ".TBL_GUERREIROSNASTORRES." SET idGuerreiro={$_POST['idGuerreiro']}, idTorre={$_POST['idTorre']}, idAndar={$_POST['idPosicao']}, StatusAprovacao={$StatusAprovacao}, ModificadoEm='{$now}', ModificadoPor={$_SESSION['user']['id']} WHERE idGuerreiroTorre={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica Reino, Castelo, Aliança e Medalhas da associação do Guerreiro com a Torre
                // SQL Query
                $sql = "SELECT id, Guerreiro, Reino, Castelo, Alianca, Torre, Posicao, Medalhas, StatusAprovacao FROM ".VIEW_GUERREIROSNASTORRES." WHERE id={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Guerreiro = $row['Guerreiro'];
                    $Reino = $row['Reino'];
                    $Castelo = $row['Castelo'];
                    $Alianca = $row['Alianca'];
                    $Torre = $row['Torre'];
                    $Posicao = $row['Posicao'];
                    $Medalhas = $row['Medalhas'];
                    $StatusAprovacao = $row['StatusAprovacao'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Guerreiro' => $Guerreiro,
                    'Reino' => $Reino,
                    'Castelo' => $Castelo,
                    'Alianca' => $Alianca,
                    'Torre' => $Torre,
                    'Posicao' => $Posicao,
                    'Medalhas' => $Medalhas,
                    'StatusAprovacao' => $StatusAprovacao
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Associação de Guerreiro com Torre atualizada com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Guerreiro da Torre
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_GUERREIROSNASTORRES." WHERE idGuerreiroTorre={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Guerreiro excluído da Torre com sucesso',
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