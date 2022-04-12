<?php
    // Inclue objetos referentes as Permissões
    include_once "../../config/permissions.php";
    $MemberOf = new Permission();

    // Inicia a Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id()==''){ session_start(); }

    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia o objeto referente a base de dados
    $database = new Database();

    // Nome das Tabelas
    $tbl_guerreiros = "igot.guerreiros"; 
    $view_guerreiros = "igot.view_guerreiros";
    $tbl_reinos = "igot.reinos";
    $tbl_castelos = "igot.castelos";
    $tbl_torres = "igot.torres";
    $tbl_aprovacaostatus = "rise.aprovacao_status";
    $tbl_EventosTipos = "igot.eventos_tipos";
    $tbl_eventostatus = "igot.eventos_status";
    
    // Identifica as opções a serem retornadas, tipo de Query a ser Executada
    switch($_GET['return']){
        case 'guerreiros':
            if(isset($_GET['groups']) && $_GET['groups']==true){
                // SQL Query - Retorna somente o Guerreiro
                $sql = "SELECT id, Guerreiro AS Label FROM {$view_guerreiros} WHERE id={$_SESSION['igot']['Guerreiro']['id']}";
                // SQL Query - Retorna os Guerreiro com base no Exército do General
                if($MemberOf->general()){ $sql = "SELECT id, Guerreiro AS Label FROM {$view_guerreiros} WHERE idExercito={$_SESSION['igot']['Guerreiro']['idExercito']} ORDER BY Guerreiro"; }
                // SQL Query - Retorna todos os Guerreiros para o IGOT Admin
                if($MemberOf->admin('igot')){ $sql = "SELECT id, Guerreiro AS Label FROM {$view_guerreiros} ORDER BY Guerreiro"; }
            }else if(isset($_GET['exercito'])){
                // SQL Query - Retorna os Guerreiros com base no(s) Exército(s)                
                $sql = "SELECT id, Guerreiro AS Label FROM {$view_guerreiros} WHERE idExercito in ({$_GET['exercito']}) ORDER BY Guerreiro";
            } else {
                // SQL Query - Retorna todos os Guerreiros se não envolver Grupo de Permissões nem Exército(s)
                $sql = "SELECT id, Guerreiro AS Label FROM {$view_guerreiros} ORDER BY Guerreiro";
            }
        break;

        case 'reinos':
            if(isset($_GET['exercito'])){
                // SQL Query - Retorna os Reinos com base no(s) Exercito(s)                
                $sql = "SELECT idReino AS id, NomeReino AS Label FROM {$tbl_reinos} WHERE idExercito in ({$_GET['exercito']}) ORDER BY NomeReino";
            } else {
                // SQL Query - Retorna todos os Reinos
                $sql = "SELECT idReino AS id, NomeReino AS Label FROM {$tbl_reinos} ORDER BY NomeReino";
            }
        break;

        case 'castelos':
            if(isset($_GET['reino'])){
                // SQL Query - Retorna os Castelos com base no(s) Reino(s)
                $sql = "SELECT idCastelo AS id, NomeCastelo AS Label FROM {$tbl_castelos} WHERE idReino in ({$_GET['reino']}) ORDER BY NomeCastelo";
            }
            else if(isset($_GET['reinos'])){
                // SQL Query - Retorna os Castelos com base no(s) Reino(s)
                $sql = "SELECT idCastelo AS id, NomeCastelo AS Label FROM {$tbl_castelos} WHERE idReino in ({$_GET['reinos']}) ORDER BY NomeCastelo";
            }            
            else {
                // SQL Query - Retorna todos os Castelos
                $sql = "SELECT idCastelo AS id, NomeCastelo AS Label FROM {$tbl_castelos} ORDER BY NomeCastelo";
            }
        break;

        case 'torres':
            if(isset($_GET['castelo']) &&  isset($_GET['alianca'])){
                // SQL Query - Retorna as Torres com base no(s) Castelo(s) e na Aliança(s)
                $sql = "SELECT idTorre AS id, NomeTorre AS Label FROM {$tbl_torres} WHERE idCastelo in ({$_GET['castelo']}) AND idAlianca in ({$_GET['alianca']}) ORDER BY NomeTorre";
            } elseif(isset($_GET['castelo']) &&  !isset($_GET['alianca'])){
                // SQL Query - Retorna as Torres com base no(s) Castelo(s)
                $sql = "SELECT idTorre AS id, NomeTorre AS Label FROM {$tbl_torres} WHERE idCastelo in ({$_GET['castelo']}) ORDER BY NomeTorre";
            }elseif(!isset($_GET['castelo']) &&  isset($_GET['alianca'])){
                // SQL Query - Retorna as Torres com base na(s) Aliança(s)
                $sql = "SELECT idTorre AS id, NomeTorre AS Label FROM {$tbl_torres} WHERE idAlianca in ({$_GET['alianca']}) ORDER BY NomeTorre";
            } else {
                // SQL Query - Retorna todas as Torres
                $sql = "SELECT idTorre AS id, NomeTorre AS Label FROM {$tbl_torres} ORDER BY NomeTorre";
            }
        break;

        case 'statusaprovacao':
            if(isset($_GET['groups']) && $_GET['groups']==true){
                // SQL Query - Retorna somente o status Pendente
                $sql = "SELECT idStatus AS id, Status AS Label FROM {$tbl_aprovacaostatus} WHERE id=3";
                // SQL Query - Retorna todas as opções de Status de Aprovação
                if($MemberOf->admin('igot') || $MemberOf->general()){ $sql = "SELECT idStatus AS id, Status AS Label FROM {$tbl_aprovacaostatus} ORDER BY Label"; }
            } else {
                // SQL Query - Retorna todas as opções de Status de Aprovação
                $sql = "SELECT idStatus AS id, Status AS Label FROM {$tbl_aprovacaostatus} ORDER BY Label";
            }
        break;

        case 'eventosstatus':
            // SQL Query - Retorna as opções de Status de Evento
            $sql = "SELECT idStatus AS id, Status AS Label FROM {$tbl_eventostatus} ORDER BY Label";
        break;

        case 'eventostipos':
            if(isset($_GET['categoria'])){
                // SQL Query - Retorna os Tipos de Eventos com base na(s) Categoria(s) de Evento
                $sql = "SELECT idTipo AS id, Tipo AS Label FROM {$tbl_EventosTipos} WHERE idCategoria in ({$_GET['categoria']}) ORDER BY Tipo";
            } else {
                // SQL Query - Retorna todos os Tipos de Eventos
                $sql = "SELECT idTipo AS id, Tipo AS Label FROM {$tbl_EventosTipos} ORDER BY Tipo";
            }
        break;

        case 'proprietarios':
            if(isset($_GET['guerreiro'])){
                // SQL Query - Retorna o Guerreiro solicitado com seu ID de Usuário
                $sql = "SELECT idUsuario AS id, Guerreiro AS Label FROM {$view_guerreiros} WHERE id={$_GET['guerreiro']}";
                // SQL Query - Retorna o Guerreiro solicitado e ele mesmo em caso de Admin do IGOT ou General 
                if($MemberOf->admin('igot') || $MemberOf->general()){ $sql = "SELECT idUsuario AS id, Guerreiro AS Label FROM {$view_guerreiros} WHERE id in ({$_GET['guerreiro']}, {$_SESSION['igot']['Guerreiro']['id']})"; }

            } else {
                // SQL Query - Retorna o idUsuario de todos os Guerreiros
                $sql = "SELECT idUsuario AS id, Guerreiro AS Label FROM {$view_guerreiros} ORDER BY Guerreiro";
            }
        break;

        
    }

    // Executa a Query
    $result = $database->run($sql);
    // Remove o objeto referente a base de dados
    unset($database);

    // Certifica de que há resultado
    if(mysqli_num_rows($result)>0) {        
        // Motagem das opções do combobox para seleção
        while($row = $result->fetch_assoc()){
            echo '<option value="'.$row['id'].'">'.$row['Label'].'</option>'; // Incremento nas opções do ComboBox
        }
    }
    
?>