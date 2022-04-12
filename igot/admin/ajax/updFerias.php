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

            // recusa atualiza o status da ferias 
            case 'updateRecusa':
                // SQL Query
                $sql = "UPDATE ".TBL_EVENTOS." SET StatusAprovacao=2 WHERE idEvento={$_POST['idEvento']}";
               // Executa a Query
               $db->query($sql);

               // Prepara os valores a serem retornados
               $returnData = array(
                   'status' => 'ok',
                   'msg' => 'Patente alterada com sucesso',
               );
               // Retorna os novos valores para atualização do DataGrid
               echo json_encode($returnData);
            break;

              // Aprova atualiza o status da ferias 
             case 'updateAprova':
             // SQL Query
             $sql = "UPDATE ".TBL_EVENTOS." SET StatusAprovacao=3 WHERE idEvento={$_POST['idEvento']}";
            // Executa a Query
            $db->query($sql);

            // Prepara os valores a serem retornados
            $returnData = array(
                'status' => 'ok',
                'msg' => 'Patente alterada com sucesso',
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