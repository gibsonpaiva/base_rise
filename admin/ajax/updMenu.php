<?php
    // Verifica se possui permissão administrativa
    include_once "../../config/permissions.php"; // Inclue o objeto referente a verificação de permissão
    $EditableBy = new Permission();

    if($EditableBy->admin('rise')){ // Se acesso autorizado
        // Inclue objetos referentes a bases de dados
        include_once "../../config/db.php";
        $database = new Database();
        // Conecta à base de dados
        $db = $database->open();

        // Identifica o Tipo de ação a ser executada
        switch($_POST['action']){
            // Cadastra um novo Item de Menu
            case 'insert':
                // Obtém a última posição de menu
                $sql = "SELECT MAX(Ordem) AS LastItem FROM ".TBL_MENU;
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $ordem = $row['LastItem'];
                    $ordem++;
                }

                // Registra o novo item de Menu
                // SQL Query
                $sql = "INSERT INTO ".TBL_MENU." (Icone, Titulo, Link, MenuPai, Ordem) VALUES ('{$_POST['icone']}', '{$_POST['titulo']}', '{$_POST['link']}', {$_POST['menupai']}, {$ordem})";
                // Executa a Query
                $db->query($sql);

                // Identifica o ID do novo item de Menu para retorno
                // SQL Query
                $sql = "SELECT idMenu FROM ".TBL_MENU." WHERE Titulo='{$_POST['titulo']}' AND Ordem={$ordem}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $idMenu = $row['idMenu'];
                }

                // Define o Título do MenuPai para retorno
                if($_POST['menupai']==0){
                    $parent="";
                } else {
                    // SQL Query
                    $sql = "SELECT Titulo FROM ".TBL_MENU." WHERE idMenu={$_POST['menupai']}";
                    // Executa a Query
                    $result = $db->query($sql);
                    if(mysqli_num_rows($result)>0) {
                        $row = $result->fetch_assoc();
                        $parent = $row['Titulo'];
                    }
                }

                // Prepara os valores a serem retornados
                $menuData = array(
                    'idmenu' => $idMenu,
                    'icone' => $_POST['icone'],
                    'titulo' => $_POST['titulo'],
                    'link' => $_POST['link'],
                    'menupai' => $parent
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Item de menu criado com sucesso',
                    'data' => $menuData
                );

                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Item de Menu
            case 'update':
                if($_POST['menupai']=="") {
                    $_POST['menupai']=0;
                }
                // SQL Query
                $sql = "UPDATE ".TBL_MENU." SET Icone='{$_POST['icone']}', Titulo='{$_POST['titulo']}', Link='{$_POST['link']}', MenuPai={$_POST['menupai']} WHERE idMenu={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Define o Título do MenuPai para retorno
                if($_POST['menupai']==0){
                    $parent="";
                } else {
                    // SQL Query
                    $sql = "SELECT Titulo FROM ".TBL_MENU." WHERE idMenu={$_POST['menupai']}";
                    // Executa a Query
                    $result = $db->query($sql);
                    if(mysqli_num_rows($result)>0) {
                        $row = $result->fetch_assoc();
                        $parent = $row['Titulo'];
                    }
                }

                // Prepara os valores a serem retornados
                $menuData = array(
                    'icone' => $_POST['icone'],
                    'titulo' => $_POST['titulo'],
                    'link' => $_POST['link'],
                    'menupai' => $parent
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Item de menu atualizado com sucesso',
                    'data' => $menuData
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui um Item de Menu
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_MENU." WHERE idMenu={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Item de menu removido com sucesso',
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;
            
            // Reordena o Menu atualizando os valores da coluna Ordem
            default:
                if (isset($_POST['position'])){
                    for ($i=0; $i<count($_POST['position']); $i++){
                        // SQL Query
                        $sql = "UPDATE ".TBL_MENU." SET Ordem={$i} WHERE idMenu={$_POST['position'][$i]}";
                        // Executa a Query
                        $db->query($sql);
                    }
                    // Prepara os valores a serem retornados
                    $returnData = array(
                        'status' => 'ok',
                        'msg' => 'Menu reordenado com sucesso',
                    );
                    // Retorna os novos valores para atualização do DataGrid
                    echo json_encode($returnData);
                }
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