<?php
    // Define o Fuso Horário
    date_default_timezone_set('America/Sao_Paulo');

    // Inclue objetos referentes as Permissões
    include_once "../../../config/permissions.php";
    $EditableBy = new Permission();
    $autorized = false;

    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia o objeto referente a base de dados
    $database = new Database();
    // Conecta à base de dados
    $db = $database->open();

    // Nome das Tabelas
    $table = "igot.eventos"; 
    $view = "igot.view_eventos";

    // Valida a Permissão
    if($_POST['action']=='insert' || $EditableBy->admin('igot')){
        $autorized = true;
    } else {
        // SQL Query para Validação do Exército e Proprietário
        $sql = "SELECT idExercito, idProprietario FROM $view WHERE id={$_POST['id']}";
        // Executa a Query
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        // Verifica se o Usuário é General do Guerreiro ou Proprietário do Registro
        if($EditableBy->general($row['idExercito']) || $EditableBy->proprietario($row['idProprietario'])){ $autorized = true; }
    }

    if($autorized){ // Se acesso autorizado
        // Identifica o Tipo de ação a ser executada
        switch($_POST['action']){
            // Registra um Evento
            case 'insert':
                // Define o Status de Aprovação padrão baseado na permissão do Usuário
                if($EditableBy->admin('igot') || $EditableBy->general()){
                    $StatusAprovacao=3; // Status - Aprovado
                    if(isset($_POST['idStatusAprovacao'])){$StatusAprovacao=$_POST['idStatusAprovacao'];} // Atualiza o Status de Aprovação para o postado
                } else {
                    $StatusAprovacao=1; // Status - Pendente Aprovação
                }
                // Ajusta os valores das Datas se vazias
                if($_POST['DataInicioEvento']==""){$_POST['DataInicioEvento']="null";}else{$_POST['DataInicioEvento']="'{$_POST['DataInicioEvento']}'";}
                if($_POST['DataConclusao']==""){$_POST['DataConclusao']="null";}else{$_POST['DataConclusao']="'{$_POST['DataConclusao']}'";}
                if($_POST['DataExpiracaoEvento']==""){$_POST['DataExpiracaoEvento']="null";}else{$_POST['DataExpiracaoEvento']="'{$_POST['DataExpiracaoEvento']}'";}
                // Ajusta o valor do Custo
                if($_POST['Custo'] != ""){
                    $Custo = str_replace("R$ ", "", $_POST['Custo']);
                    $Custo = str_replace(".", "", $Custo);
                    $Custo = str_replace(",", ".", $Custo);
                } else {
                    $Custo = "0.00";
                }
                // SQL Query
                $sql = "INSERT INTO ".TBL_EVENTOS." (idTipo, idGuerreiro, idTorre, idStatus, CustoEvento, DataInicioEvento, DataConclusaoEvento, DataExpiracaoEvento, Proprietario, StatusAprovacao, RegistradoPor) VALUES ({$_POST['idTipo']}, {$_POST['idGuerreiro']}, {$_POST['idTorre']}, {$_POST['idStatus']}, {$Custo}, {$_POST['DataInicioEvento']}, {$_POST['DataConclusao']}, {$_POST['DataExpiracaoEvento']}, {$_SESSION['user']['id']}, {$StatusAprovacao}, {$_SESSION['user']['id']})";
                // Executa a Query
                $db->query($sql);

                // Aguarda os segundo(s) dentro dos parêntesis
                sleep(1);

                // Identifica o ID do novo registro
                // SQL Query
                $sql = "SELECT id, Categoria, Tipo, Guerreiro, NomeAlianca, Torre, Custo, Status, DataInicioEvento, DataConclusao, DataExpiracaoEvento, StatusAprovacao FROM ".VIEW_EVENTOS." WHERE idTipo={$_POST['idTipo']} AND idGuerreiro={$_POST['idGuerreiro']} AND idTorre={$_POST['idTorre']} AND idStatus={$_POST['idStatus']} ORDER BY id DESC LIMIT 1";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Categoria = $row['Categoria'];
                    $Tipo = $row['Tipo'];
                    $Guerreiro = $row['Guerreiro'];
                    $Alianca = $row['NomeAlianca'];
                    if($row['Torre'] == null) {$Torre = "";} else {$Torre = $row['Torre'];}
                    $Custo = "R$ {$row['Custo']}";
                    $Status = $row['Status'];
                    if($row['DataInicioEvento'] == null) {$DataInicioEvento = "";} else {$DataInicioEvento = $row['DataInicioEvento'];}                    
                    if($row['DataConclusao'] == null) {$DataConclusao = "";} else {$DataConclusao = $row['DataConclusao'];}
                    if($row['DataConclusao'] == null) {$DataExpiracaoEvento = "";} else {$DataExpiracaoEvento = $row['DataExpiracaoEvento'];}
                    $StatusAprovacao = $row['StatusAprovacao'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Categoria' => $Categoria,
                    'Tipo' => $Tipo,
                    'Guerreiro' => $Guerreiro,
                    'Alianca' => $Alianca,
                    'Torre' => $Torre,
                    'Custo' => $Custo,
                    'Status' => $Status,
                    'DataInicioEvento' => $DataInicioEvento,
                    'DataConclusao' => $DataConclusao,
                    'DataExpiracaoEvento' => $DataExpiracaoEvento,
                    'StatusAprovacao' => $StatusAprovacao
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Evento registrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Atualiza um Registro de Evento
            case 'update':
                // Identifica o Dia e Horário atual
                $now = date('Y-m-d H:i:s', time());
                // Define o Status de Aprovação padrão baseado na permissão do Usuário
                if($EditableBy->admin('igot') || $EditableBy->general()){
                    if(!isset($_POST['idStatusAprovacao'])){
                        $StatusAprovacao = 3; // Status - Aprovado
                    } else {
                        $StatusAprovacao = $_POST['idStatusAprovacao'];
                    }
                } else {
                    $StatusAprovacao=1; // Status - Pendente Aprovação
                }
                // Ajusta os valores das Datas se vazias
                if($_POST['DataInicioEvento']==""){$_POST['DataInicioEvento']="null";} else {$_POST['DataInicioEvento']="'{$_POST['DataInicioEvento']}'";}
                if($_POST['DataConclusao']=="") {$_POST['DataConclusao']="null";} else {$_POST['DataConclusao']="'{$_POST['DataConclusao']}'";}
                if($_POST['DataExpiracaoEvento']=="") {$_POST['DataExpiracaoEvento']="null";} else {$_POST['DataExpiracaoEvento']="'{$_POST['DataExpiracaoEvento']}'";}
                // SQL Query
                $sql = "UPDATE ".TBL_EVENTOS." SET idStatus={$_POST['idStatus']}, DataInicioEvento={$_POST['DataInicioEvento']}, DataConclusaoEvento={$_POST['DataConclusao']}, DataExpiracaoEvento={$_POST['DataExpiracaoEvento']}, StatusAprovacao={$StatusAprovacao}, ModificadoEm='{$now}', ModificadoPor={$_SESSION['user']['id']}";
                if(isset($_POST['idProprietario'])){ $sql .= ", Proprietario={$_POST['idProprietario']}"; }
                if(isset($_POST['idEvento'])){ $sql .= ", idEvento='{$_POST['idEvento']}'"; }
                if(isset($_POST['idTorre'])){ $sql .= ", idTorre='{$_POST['idTorre']}'"; }
                if(isset($_POST['idTipo'])){ $sql .= ", idTipo='{$_POST['idTipo']}'"; }
                if(isset($_POST['idGuerreiro'])){ $sql .= ", idGuerreiro='{$_POST['idGuerreiro']}'"; }
                if(isset($_POST['Notas'])){ $sql .= ", Notas='{$_POST['Notas']}'"; }
                if(isset($_POST['Custo'])){
                    if($_POST['Custo'] != ""){
                        $Custo = str_replace("R$ ", "", $_POST['Custo']);
                        $Custo = str_replace(".", "", $Custo);
                        $Custo = str_replace(",", ".", $Custo);
                        $sql .= ", CustoEvento='{$Custo}'";
                    } else {
                        $sql .= ", CustoEvento='0.00'";
                    }
                }
                if(isset($_POST['Certificado'])){ $sql .= ", idCertificado={$_POST['Certificado']}"; } else { ", Certificado=null"; }
                $sql .= " WHERE idEvento={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Identifica os novos valores do registro atualizado
                // SQL Query
                $sql = "SELECT id, Categoria, Tipo, Guerreiro, NomeAlianca, Torre, Custo, Status, DataInicioEvento, DataConclusao, DataExpiracaoEvento, StatusAprovacao FROM ".VIEW_EVENTOS." WHERE id={$_POST['id']}";
                // Executa a Query
                $result = $db->query($sql);
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();                
                    $id = $row['id'];
                    $Categoria = $row['Categoria'];
                    $Tipo = $row['Tipo'];
                    $Guerreiro = $row['Guerreiro'];
                    $Alianca = $row['NomeAlianca'];                
                    if($row['Torre'] == null) {$Torre = "";} else {$Torre = $row['Torre'];}
                    if($_POST['Custo'] == null) {$Custo = "R$ 0,00";} elseif(substr($_POST['Custo'], 0, 2)=="R$") {$Custo = $_POST['Custo'];} else {$Custo = "R$ {$_POST['Custo']}";}
                    $Status = $row['Status'];
                    $DataInicioEvento = $row['DataInicioEvento'];
                    $DataConclusao = $row['DataConclusao'];
                    $DataExpiracaoEvento = $row['DataExpiracaoEvento'];
                    $StatusAprovacao = $row['StatusAprovacao'];
                }

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Categoria' => $Categoria,
                    'Tipo' => $Tipo,
                    'Guerreiro' => $Guerreiro,
                    'Alianca' => $Alianca,
                    'Torre' => $Torre,
                    'Custo' => $Custo,
                    'Status' => $Status,                
                    'DataInicioEvento' => $DataInicioEvento,
                    'DataConclusao' => $DataConclusao,
                    'DataExpiracaoEvento' => $DataExpiracaoEvento,
                    'StatusAprovacao' => $StatusAprovacao
                    
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Registro de Evento atualizado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Registro de Evento
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM ".TBL_EVENTOS." WHERE idEvento={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Registro de Evento excluído com sucesso',
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