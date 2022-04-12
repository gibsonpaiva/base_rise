<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }

    // Inclue objetos referentes a bases de dados
    include_once "../../config/db.php";
    $database = new Database();
    // Conecta à base de dados
    $db = $database->open();
    
     // Identifica o Tipo de ação a ser executada
    switch($_POST['action']){        

        case 'update':
            $sql = "UPDATE ".TBL_FORUMTOPICOS." SET Assunto='{$_POST['Assunto']}', idTipoTopico='{$_POST['idTipoTopico']}', Postagem='{$_POST['FormEditTopico']}',  ModificadoPor={$_SESSION['user']['id']} WHERE idTopico = '{$_POST['idTopico']}'";
            // Executa a Query
            $db->query($sql);
            // SQL Query
            //$sql = 'SELECT idTopico, idResposta, Resposta, RegistradoEm, idRegistradoPor FROM ".VIEW_FORUMRESPOSTAS." WHERE idResposta= '.$_POST['idResposta'].' AND idRegistradoPor='.$_SESSION['user']['id'].' ORDER BY idResposta DESC LIMIT 1';
            
            // Executa a Query
            $result = $db->query($sql);
            if(mysqli_num_rows($result)>0) {
                $row = $result->fetch_assoc();
                $idTopico = $row['idTopico'];
                $idResposta = $row['idResposta'];
                $Resposta = $row['Resposta'];
                $StatusAprovacao = $row['StatusAprovacao'];
                $idRegistradoPor = $row['idRegistradoPor'];
                $RegistradoPor = $row['RegistradoPor'];
                $RegistradoEm = $row['RegistradoEm'];
            }

            // Prepara os valores a serem retornados
            $RespostaData = array(
                'idTopico' => $idTopico,
                'idResposta' => $idResposta,
                'Resposta' => $Resposta,
                'StatusAprovacao' => $StatusAprovacao,
                'idRegistradoPor' => $idRegistradoPor,
                'RegistradoPor' => $RegistradoPor,
                'RegistradoEm' => $RegistradoEm
            );
            $returnData = array(
                'status' => 'ok',
                'msg' => 'Resposta inserida com sucesso!',
                'data' => $RespostaData
            );
            // Retorna os novos valores para atualização do DataGrid
            echo json_encode($returnData);
        break;

        case 'delete':
            // SQL Query
            $sql = "DELETE FROM ".TBL_FORUMRESPOSTAS." WHERE idTopico={$_POST['id']}";

            $db->query($sql);

            $sql = "DELETE FROM ".TBL_FORUMTOPICOS." WHERE idTopico={$_POST['id']}";
            
            // Executa a Query
            $db->query($sql);

            // Prepara os valores a serem retornados
            $returnData = array(
                'status' => 'ok',
                'msg' => 'Comentario removido com sucesso',
            );
            // Retorna os novos valores para atualização do DataGrid
            echo json_encode($returnData);
        break;
    }
?>
