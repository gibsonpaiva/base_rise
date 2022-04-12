<?php
    // Função para incorporar as opções do ComboBox no código HTML
    function getOptions($options, $filter=""){
        $_GET['return']=$options;
        if($filter!=""){
            // Prepara o parâmetro GET com base na string "Filtro"
            $_GET[substr($filter, 0, strpos($filter, "="))]=substr($filter, strpos($filter, "=")+1, strlen($filter));
        }
        ob_start();
        require("options.php");
        return ob_get_clean();
    }

    // Inclue objetos referentes as Permissões
    require_once "../../config/permissions.php";
    $EditableBy = new Permission();

    // Inclue objetos referentes a bases de dados
    require_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia o objeto referente a base de dados
    $database = new Database();

    // Declara a variável $_GET['type'] se esta não tiver sido informada
    if(!isset($_GET['type'])){ $_GET['type'] = ""; }

    // Monta a Query com base no retorno solicitado
    switch($_GET['return']){
        case 'GuerreirosNasTorres':
            // SQL Query
            if(isset($_GET['Medalhas'])) {
                // Prepara Query com total de Medalhas
                $sql = 'SELECT SUM(Medalhas) AS TotalMedalhas FROM '.VIEW_GUERREIROSNASTORRES.' WHERE 1';
            } else {
                // Prepara Query de Guerreiros Nas Torres
                $sql = 'SELECT id, idGuerreiro, Guerreiro, idExercitoGuerreiro, idReino, Reino, idCastelo, Castelo, idAlianca, Alianca, idTorre, Torre, idPosicao, Posicao, Medalhas, idStatusAprovacao, StatusAprovacao, idProprietario FROM '.VIEW_GUERREIROSNASTORRES.' WHERE 1';
            }            
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['exercito'])){ $sql .= " AND (idExercitoGuerreiro in ({$_GET['exercito']}) OR idExercitoReino in ({$_GET['exercito']}))"; }
            if(isset($_GET['guerreiro'])){ $sql .= " AND idGuerreiro in ({$_GET['guerreiro']})"; }
            if(isset($_GET['reino'])){ $sql .= " AND idReino in ({$_GET['reino']})"; }
            if(isset($_GET['castelo'])){ $sql .= " AND idCastelo in ({$_GET['castelo']})"; }
            if(isset($_GET['alianca'])){ $sql .= " AND idAlianca in ({$_GET['alianca']})"; }
            if(isset($_GET['torre'])){ $sql .= " AND idTorre in ({$_GET['torre']})"; }
            if(isset($_GET['posicao'])){ $sql .= " AND idPosicao in ({$_GET['posicao']})"; }
            if(isset($_GET['StatusAprovacao'])){ $sql .= " AND idStatusAprovacao in ({$_GET['StatusAprovacao']})"; }
            //Incrementa a Query com a ordenação
            $sql .= " ORDER BY {$_GET['sort']}";
        break;

        case 'ConselheirosDosReinos':
            // SQL Query
            $sql = 'SELECT id, Exercito, idConselheiro, Conselheiro, idReino, Reino, idPatente, Patente, Descricao FROM '.VIEW_CONSELHEIROSDOSREINOS.' WHERE 1';
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['exercito'])){ $sql .= " AND idExercito in ({$_GET['exercito']})"; }
            if(isset($_GET['guerreiro'])){ $sql .= " AND idConselheiro in ({$_GET['guerreiro']})"; }
            if(isset($_GET['reino'])){ $sql .= " AND idReino in ({$_GET['reino']})"; }
            if(isset($_GET['patente'])){ $sql .= " AND idPatente in ({$_GET['patente']})"; }
            //Incrementa a Query com a ordenação
            $sql .= " ORDER BY {$_GET['sort']}";
        break;

        case 'Eventos':
            // SQL Query
            if(isset($_GET['Custo'])){
                // Prepara Query com total do Custo
                $sql = 'SELECT SUM(Custo) AS TotalCusto FROM '.VIEW_EVENTOS.' WHERE 1';
            } else {
                // Prepara Query de Eventos Registrados
                $sql = 
                'SELECT
                    id,
                    idTipo, Tipo, 
                    idCategoria, Categoria, 
                    idExercito, Exercito, 
                    idGuerreiro, Guerreiro, 
                    idTorre, Torre, 
                    idAlianca, NomeAlianca, 
                    DataInicioEvento, DataInicioEvento_YMD, 
                    DataConclusao, DataConclusao_YMD, 
                    DataExpiracaoEvento, DataExpiracaoEvento_YMD, 
                    idStatus, Status, 
                    Moedas, 
                    Custo, 
                    Notas, 
                    idProprietario, Proprietario,
                    idCertificado,
                    idStatusAprovacao, StatusAprovacao, 
                    RegistradoPor, 
                    RegistradoEm, RegistradoEm_YMD, 
                    ModificadoPor,
                    ModificadoEm 
                FROM '.VIEW_EVENTOS.' 
                WHERE 1';
            }
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['id'])){ $sql .= " AND id={$_GET['id']}"; }
            if(isset($_GET['tipo'])){ $sql .= " AND idTipo in ({$_GET['tipo']})"; }
            if(isset($_GET['exercitos'])){ $sql .= " AND idExercito in ({$_GET['exercitos']})"; }
            if(isset($_GET['guerreiro'])){ $sql .= " AND idGuerreiro in ({$_GET['guerreiro']})"; }
            if(isset($_GET['status'])){ $sql .= " AND idStatus in ({$_GET['status']})"; }
            if(isset($_GET['torre'])){ $sql .= " AND idTorre in ({$_GET['torre']})"; }
            if(isset($_GET['alianca'])){ $sql .= " AND idAlianca in ({$_GET['alianca']})"; }
            if(isset($_GET['categoria'])){ $sql .= " AND idCategoria in ({$_GET['categoria']})"; }
            if(isset($_GET['StatusAprovacao'])){ $sql .= " AND idStatusAprovacao in ({$_GET['StatusAprovacao']})"; }
            if(isset($_GET['RegistradoEntre'])){ $sql .= " AND (RegistradoEm_YMD BETWEEN {$_GET['RegistradoEntre']})"; }
            if(isset($_GET['ConcluidoEntre'])){ $sql .= " AND (DataConclusao_YMD BETWEEN {$_GET['ConcluidoEntre']})"; }
            if(isset($_GET['Certificado'])){
                if($_GET['Certificado'] == 1){
                    $sql .= " AND (idCertificado IS NOT NULL)";
                } else {
                    $sql .= " AND (idCertificado IS NULL)";
                }
            }
            //Incrementa a Query com a ordenação
            if(isset($_GET['sort'])){ $sql .= " ORDER BY {$_GET['sort']}"; }
        break;

        case 'Arquivos':
            // Prepara Query de arquivos com eventos Registrados
            $sql =
            'SELECT idArquivo, Categoria, idTipoEvento, TipoEvento, Guerreiro, Torre, Alianca, DataInicioEvento, DataConclusao FROM '.VIEW_ARQUIVOS_EVENTOS.' WHERE idTIpoArquivo = '.$_GET['idTipoArquivo'];
                            
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['idArquivo'])){ $sql .= " AND idArquivo={$_GET['idArquivo']}"; }
            if(isset($_GET['tipoEvento'])){ $sql .= " AND idTipoEvento in ({$_GET['tipoEvento']})"; }
            if(isset($_GET['guerreiro'])){ $sql .= " AND idGuerreiro in ({$_GET['guerreiro']})"; }            
            if(isset($_GET['torre'])){ $sql .= " AND idTorre in ({$_GET['torre']})"; }
            if(isset($_GET['alianca'])){ $sql .= " AND idAlianca in ({$_GET['alianca']})"; }
            if(isset($_GET['categoria'])){ $sql .= " AND idCategoria in ({$_GET['categoria']})"; }           
            if(isset($_GET['RegistradoEntre'])){ $sql .= " AND (RegistradoEm_YMD BETWEEN {$_GET['RegistradoEntre']})"; }
            if(isset($_GET['ConcluidoEntre'])){ $sql .= " AND (DataConclusao_YMD BETWEEN {$_GET['ConcluidoEntre']})"; }
            
            //Incrementa a Query com a ordenação
            if(isset($_GET['sort'])){ $sql .= " ORDER BY {$_GET['sort']}"; }
        break;

        case 'BuscaAvancada':
            // Prepara Query de arquivos com eventos Registrados
            $sql =
            'SELECT idArquivo, TipoArquivo, Categoria, TipoEvento, Guerreiro, Torre, Alianca, DataInicioEvento, DataConclusao FROM '.VIEW_ARQUIVOS_EVENTOS.' WHERE 1 ';
                            
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['idArquivo'])){ $sql .= " AND idArquivo={$_GET['idArquivo']}"; }
            if(isset($_GET['tipoEvento'])){ $sql .= " AND idTipoEvento in ({$_GET['tipoEvento']})"; }
            if(isset($_GET['guerreiro'])){ $sql .= " AND idGuerreiro in ({$_GET['guerreiro']})"; }            
            if(isset($_GET['torre'])){ $sql .= " AND idTorre in ({$_GET['torre']})"; }
            if(isset($_GET['alianca'])){ $sql .= " AND idAlianca in ({$_GET['alianca']})"; }
            if(isset($_GET['categoria'])){ $sql .= " AND idCategoria in ({$_GET['categoria']})"; }           
            if(isset($_GET['RegistradoEntre'])){ $sql .= " AND (RegistradoEm_YMD BETWEEN {$_GET['RegistradoEntre']})"; }
            if(isset($_GET['ConcluidoEntre'])){ $sql .= " AND (DataConclusao_YMD BETWEEN {$_GET['ConcluidoEntre']})"; }
            
            //Incrementa a Query com a ordenação
            if(isset($_GET['sort'])){ $sql .= " ORDER BY {$_GET['sort']}"; }
        break;

        case 'EventosTipos':
            // SQL Query
            $sql = 'SELECT id, idCategoria, Categoria, idAlianca, Alianca, Tipo, DataInicio, DataFim, LinkInscricao FROM '.VIEW_EVENTOSTIPOS.' WHERE 1';
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['categoria'])){ $sql .= " AND idCategoria in ({$_GET['categoria']})"; }
            if(isset($_GET['alianca'])){ $sql .= " AND idAlianca in ({$_GET['alianca']})"; }
            if(isset($_GET['proximos'])){ $sql .= " AND DataInicio_YMD >= DATE(NOW())"; } // Filtro para Exibição somente dos Eventos Futuros
            //Incrementa a Query com a ordenação
            $sql .= " ORDER BY {$_GET['sort']}";
        break;
        
        case 'Conformidades':
            // SQL Query
            $sql = 'SELECT id, Conformidade AS Nome, idTipoConformidade AS idTipo, TipoConformidade AS Tipo, idAlianca, Alianca FROM '.VIEW_CONFORMIDADES.' WHERE 1';
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['alianca'])){ $sql .= " AND idAlianca in ({$_GET['alianca']})"; }
            if(isset($_GET['tipo'])){ $sql .= " AND idTipoConformidade in ({$_GET['tipo']})"; }
            //Incrementa a Query com a ordenação
            $sql .= " ORDER BY {$_GET['sort']}";
        break;

        case 'Reinos':
            switch($_GET['type']){ // Identifica o tipo de retorno solicitado
                case 'List': // lista de reinos 
                    // SQL Query
                    $sql = 'SELECT idReino AS id, Reino AS Nome FROM '.VIEW_REINOS.' ORDER BY Nome LIMIT 9 OFFSET '.$_GET['pag'];
                break;

                case 'Form': //formulario para editar o reino
                    $sql = 'SELECT  idReino, Reino, idExercito, Exercito, Descricao FROM '.VIEW_REINOS.' WHERE idReino = '.$_GET['id'];
                break;
            }
        break;

        case 'Torres':
            //SQL Query
            $sql = 'SELECT idReino, Reino, idCastelo, Castelo, idAlianca, Alianca, idTorre AS id, Torre FROM '.VIEW_TORRES.' WHERE 1';
            // Incrementa a Query com os filtros selecionados
            if(isset($_GET['reino'])){ $sql .= " AND idReino in ({$_GET['reino']})"; }
            if(isset($_GET['castelo'])){ $sql .= " AND idCastelo in ({$_GET['castelo']})"; }
            if(isset($_GET['alianca'])){ $sql .= " AND idAlianca in ({$_GET['alianca']})"; }
            //Incrementa a Query com a ordenação
            if(isset($_GET['sort'])){ $sql .= " ORDER BY {$_GET['sort']}"; }
        break;
        
        case 'PortalDoReino':
            switch($_GET['frame']){ // Identifica a parte do Portal do Castelo a ser retornada
                case "Cabecalho": // Cabeçalho do Portal
                    // SQL Query
                    $sql = 'SELECT Reino, Exercito FROM '.VIEW_REINOS.' WHERE idReino='.$_GET['id'];
                break;

                case "Perfil": // Perfil Quantitativo do Reino
                    // SQL Query
                    $sql = 
                    '   SELECT
                            SUM(QtdeComandantes) AS Comandantes,
                            SUM(QtdeCavaleiros) AS Cavaleiros,
                            SUM(QtdeSoldados) AS Soldados,
                            SUM(QtdeRecrutas) AS Recrutas,
                            SUM(QtdeMedalhas) AS Medalhas
                        FROM (
                            SELECT
                                IF(idPosicao=4,COUNT(idPosicao),0) AS QtdeComandantes,
                                IF(idPosicao=3,COUNT(idPosicao),0) AS QtdeCavaleiros,
                                IF(idPosicao=2,COUNT(idPosicao),0) AS QtdeSoldados,
                                IF(idPosicao=1,COUNT(idPosicao),0) AS QtdeRecrutas,
                                SUM(Medalhas) AS QtdeMedalhas
                            FROM '.VIEW_GUERREIROSNASTORRES.'
                            WHERE idReino='.$_GET['id'].'
                            GROUP By Posicao
                        ) AS Qtde
                    ';
                break;

                case "Descricao": // Texto descritivo sobre o Reino
                    // SQL Query
                    $sql = 'SELECT Descricao FROM '.VIEW_REINOS.' WHERE idReino='.$_GET['id'];
                break;

                case "Grafico": // Gráfico Quantitativo por Castelos do Reino
                    $sql = 'SELECT idCastelo, Castelo, SUM(Medalhas) AS QtdeMedalhas FROM '.VIEW_GUERREIROSNASTORRES.' WHERE idReino='.$_GET['id'].' GROUP BY idCastelo';
                    //Incrementa a Query com a ordenação
                    $sql .= " ORDER BY {$_GET['sort']}";
                break;
            }
        break;

        case 'PortalDoCastelo':
            switch($_GET['frame']){ // Identifica a parte do Portal do Castelo a ser retornada
                case "Cabecalho": // Cabeçalho do Portal
                    // SQL Query
                    $sql = 'SELECT Reino, Castelo FROM '.VIEW_CASTELOS.' WHERE idCastelo='.$_GET['id'];
                break;

                case "Perfil": // Perfil Quantitativo do Castelo
                    // SQL Query
                    $sql = 
                    '   SELECT
                            SUM(QtdeComandantes) AS Comandantes,
                            SUM(QtdeCavaleiros) AS Cavaleiros,
                            SUM(QtdeSoldados) AS Soldados,
                            SUM(QtdeRecrutas) AS Recrutas,
                            SUM(QtdeMedalhas) AS Medalhas
                        FROM (
                            SELECT
                                IF(idPosicao=4,COUNT(idPosicao),0) AS QtdeComandantes,
                                IF(idPosicao=3,COUNT(idPosicao),0) AS QtdeCavaleiros,
                                IF(idPosicao=2,COUNT(idPosicao),0) AS QtdeSoldados,
                                IF(idPosicao=1,COUNT(idPosicao),0) AS QtdeRecrutas,
                                SUM(Medalhas) AS QtdeMedalhas
                            FROM '.VIEW_GUERREIROSNASTORRES.'
                            WHERE idCastelo='.$_GET['id'].'
                            GROUP By Posicao
                        ) AS Qtde
                    ';
                break;

                case "Descricao": // Texto descritivo sobre o Castelo
                    // SQL Query
                    $sql = 'SELECT Descricao FROM '.VIEW_CASTELOS.' WHERE idCastelo='.$_GET['id'];
                break;

                case "Grafico": // Gráfico Quantitativo por Torres do Castelo
                    $sql = 'SELECT idTorre, Torre, SUM(Medalhas) AS QtdeMedalhas FROM '.VIEW_GUERREIROSNASTORRES.' WHERE idCastelo='.$_GET['id'].' GROUP BY idTorre';
                    //Incrementa a Query com a ordenação
                    $sql .= " ORDER BY {$_GET['sort']}";                    
                break;
            }
        break;

        case 'PortalDaTorre':
            switch($_GET['frame']){ // Identifica a parte do Portal da Torre a ser retornada
                case "Cabecalho": // Cabeçalho do Portal da Torre
                    // SQL Query
                    $sql = 'SELECT Torre, Alianca, Reino, Castelo FROM '.VIEW_TORRES.' WHERE idTorre='.$_GET['id'];
                break;

                case "Perfil": // Perfil Quantitativo da Torre
                    // SQL Query
                    $sql = 
                    '   SELECT
                            SUM(QtdeComandantes) AS Comandantes,
                            SUM(QtdeCavaleiros) AS Cavaleiros,
                            SUM(QtdeSoldados) AS Soldados,
                            SUM(QtdeRecrutas) AS Recrutas,
                            SUM(QtdeMedalhas) AS Medalhas
                        FROM (
                            SELECT
                                IF(idPosicao=4,COUNT(idPosicao),0) AS QtdeComandantes,
                                IF(idPosicao=3,COUNT(idPosicao),0) AS QtdeCavaleiros,
                                IF(idPosicao=2,COUNT(idPosicao),0) AS QtdeSoldados,
                                IF(idPosicao=1,COUNT(idPosicao),0) AS QtdeRecrutas,
                                SUM(Medalhas) AS QtdeMedalhas
                            FROM '.VIEW_GUERREIROSNASTORRES.'
                            WHERE idTorre='.$_GET['id'].'
                            GROUP By Posicao
                        ) AS Qtde
                    ';
                break;

                case "Forca-Membros": // Guia Força da Torre
                    // SQL Query
                    $sql = 'SELECT idUsuario, Guerreiro, ExercitoGuerreiro, idTorre, Torre, idPosicao, Posicao FROM '.VIEW_GUERREIROSNASTORRES.' WHERE idTorre='.$_GET['id'];
                    // Incrementa a Query com a ordenação
                    if(isset($_GET['sort'])){ $sql .= " ORDER BY {$_GET['sort']}"; }
                break;
            }
        break;

        case 'Dashboard':
            switch($_GET['frame']){
                case 'AliancasBarChart':
                    $sql = 'SELECT idAlianca, Alianca, SUM(Medalhas) AS QtdeMedalhas FROM '.VIEW_GUERREIROSNASTORRES.' GROUP BY idAlianca';
                break;

                case 'AliancasKnobChart':
                    $sql = 
                    '   SELECT
                            Torres.idAlianca AS idAlianca,
                            Torres.Alianca AS Alianca,
                            Torres.qtdeTorres AS qtdeTorres,
                            Guerreiros.qtdeGuerreiros AS qtdeGuerreiros,
                            (Torres.qtdeTorres) * (Guerreiros.qtdeGuerreiros) * 10 AS MaxMedalhas,
                            Medalhas.qtdeMedalhas AS qtdeMedalhas,
                            ((Medalhas.qtdeMedalhas) / ((Torres.qtdeTorres) * (Guerreiros.qtdeGuerreiros) * 10)) * 100 AS pMedalhas
                        FROM (SELECT idAlianca, Alianca, COUNT(idTorre) AS qtdeTorres FROM '.VIEW_TORRES.' GROUP BY idAlianca) AS Torres
                            INNER JOIN (SELECT idAlianca, Alianca, COUNT(idGuerreiro) AS qtdeGuerreiros FROM '.VIEW_GUERREIROSNASTORRES.' WHERE idPosicao>1 GROUP BY idAlianca) AS Guerreiros ON Guerreiros.idAlianca = Torres.idAlianca
                            INNER JOIN (SELECT idAlianca, Alianca, SUM(Medalhas) AS qtdeMedalhas FROM '.VIEW_GUERREIROSNASTORRES.' GROUP BY idAlianca) AS Medalhas ON Medalhas.idAlianca = Torres.idAlianca
                        ORDER BY pMedalhas DESC
                    ';
                break;
            }
        break;

        case 'QuadrosDeMedalhas':
            switch($_GET['type']){ // Identifica o tipo de retorno solicitado
                default: // Quadros de Medalhas
                    // SQL Query
                    $sql  = "SET @m=0;";
                    $sql .= "SET @e=0;";
                    $sql .= "SET @r=0;";
                    $sql .= 
                    '   SELECT
                            CASE
                                WHEN @m = TotalMedalhas THEN @r
                                WHEN @e = idExercito THEN (@r := @r + 1)
                                ELSE @r := 1
                            END AS Rank,
                            (@e := idExercito) AS idExercito,
                            Exercito,
                            idGuerreiro,
                            Guerreiro,
                            (@m := TotalMedalhas) AS TotalMedalhas
                        FROM (  SELECT
                                    idExercitoGuerreiro AS idExercito,
                                    ExercitoGuerreiro AS Exercito,
                                    idGuerreiro,
                                    Guerreiro,
                                    SUM(Medalhas) AS TotalMedalhas
                                FROM '.VIEW_GUERREIROSNASTORRES.'
                                GROUP BY Guerreiro
                                ORDER BY FIELD(idExercito, '.$_GET['idExercito'].') DESC, Exercito, TotalMedalhas DESC, Guerreiro
                        ) AS Quadro
                    ';
                break;
            }
        break;

        case 'LinhaDoTempo':
            switch($_GET['type']){ // Identifica o tipo de retorno solicitado
                default: // Quadros de Medalhas
                    // SQL Query
                    $sql = 'SELECT id, Icone, DataConclusao, Categoria, idUsuario, Guerreiro, Mensagem, Tipo, Moedas, NomeAlianca, Torre, Exercito, Custo FROM '.VIEW_EVENTOS.' WHERE idStatus=4 ORDER BY DataConclusao_YMD DESC LIMIT 10';                    
                    if(isset($_GET['pag'])){ $sql .= ' OFFSET '. $_GET['pag']; } // Se informado a paginação
                break;
            }
        break;
    }

    // Executa a Query
    $result = $database->run_multi($sql);
    // Remove o objeto referente a base de dados
    unset($database);

    // Prepara variável para montagem do resultado com base no retorno solicitado
    $html = "";

    // Certifica de que há resultado
    if(mysqli_num_rows($result)>0) {
        switch($_GET['return']){
            case 'GuerreirosNasTorres':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        if(isset($_GET['Medalhas'])) {
                            // Prepara resultado de totais de Medalhas
                            $row = $result->fetch_assoc();
                            $html = $row['TotalMedalhas'];
                        } else { // Prepara a Tabela a ser impressa na tela                    
                            $optStatusAprovacao = getOptions("statusaprovacao"); // Prepara as opções de Status de Aprovação
                            $postURL = "'admin/ajax/updGuerreirosNasTorres.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                            // Montagem das linhas da tabela Guerreiros Nas Torres
                            for($i = 0; $row = $result->fetch_assoc(); $i++){
                                $contador = $i +1;   
                                $html .= "
                                    <tr name='{$row['id']}'>
                                        <td class='datagrid'>
                                            <span class=''>$contador</span>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewOnly Guerreiro'>{$row['Guerreiro']}</span>
                                            <select class='form-control form-field select2 editInput Guerreiro' name='idGuerreiro' style='display:none;'>
                                                <option value={$row['idGuerreiro']} selected='selected'>{$row['Guerreiro']}</option>
                                            </select>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewAlways Reino'>{$row['Reino']}</span>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewAlways Castelo'>{$row['Castelo']}</span>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewAlways Alianca'>{$row['Alianca']}</span>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewOnly Torre'>{$row['Torre']}</span>
                                            <select class='form-control form-field select2 editInput Torre' name='idTorre' style='display:none;'>
                                                <option value={$row['idTorre']} selected='selected'>{$row['Torre']}</option>
                                            </select>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewOnly Posicao'>{$row['Posicao']}</span>
                                            <select class='form-control form-field select2 editInput Posicao' name='idPosicao' style='display:none;'>
                                                <option value={$row['idPosicao']} selected='selected'>{$row['Posicao']}</option>
                                            </select>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways Medalhas'>{$row['Medalhas']}</span>
                                        </td>
                                        <td class='datagrid' align='center'>
                                ";
                                // Inclui edição do Status de Aprovação se for um Administrador ou General
                                if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito'])){
                                    $html .= "
                                            <span class='viewOnly StatusAprovacao'>{$row['StatusAprovacao']}</span>
                                            <select class='editInput form-control form-field input-sm select2 StatusAprovacao' name='idStatusAprovacao' style='display:none;'>
                                                <option value='{$row['idStatusAprovacao']}' selected='selected'>{$row['StatusAprovacao']}</option>
                                                {$optStatusAprovacao}
                                            </select>
                                    ";
                                } else {
                                    $html .= "
                                            <span class='viewAlways StatusAprovacao'>{$row['StatusAprovacao']}</span>
                                    ";
                                }
                                $html .= "
                                        <td class='datagrid actions' align='center'>
                                ";
                                // Inclui os botões se for um Administrador, General ou Proprietário
                                if($EditableBy->admin('igot') || $EditableBy->general($row['idExercitoGuerreiro']) || $EditableBy->proprietario($row['idProprietario'])){
                                    $html .= '
                                            <button onClick="EditGuerreirosNasTorres(this.id)" id="btnEdit'.$row['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                                            <button onClick="UpdateGuerreirosNasTorres(this.id)" id="btnSave'.$row['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                                            <button onClick="Delete(this.id, Habilidades)" id="btnDelete'.$row['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                                            <button onClick="Remove(this.id, Habilidades, '.$postURL.')" id="btnRemove'.$row['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                                            <button onclick="Cancel(this.id, Habilidades)" id="btnCancel'.$row['id'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>';
                                }
                                $html .= '
                                        </td>
                                    </tr>
                                ';
                            }
                        }
                    break;
                }
            break;

            case 'ConselheirosDosReinos':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        $postURL = "'admin/ajax/updConselheiros.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                        // Montagem das linhas da tabela Conselheiros dos Reis
                        while($row = $result->fetch_assoc()){
                            $html .= "
                                <tr name='{$row['id']}'>
                                    <td class='datagrid'>
                                        <span class='viewOnly Patente'>{$row['Patente']}</span>
                                        <select class='editInput form-control form-field input-sm select2 Patente' name='idPatente' style='display:none;'>
                                            <option value={$row['idPatente']} selected='selected'>{$row['Patente']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid' align='center'>{$row['Exercito']}</td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Conselheiro'>{$row['Conselheiro']}</span>
                                        <select class='editInput form-control form-field input-sm select2 Guerreiro' name='idConselheiro' style='display:none;'>
                                            <option value={$row['idConselheiro']} selected='selected'>{$row['Conselheiro']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Reino'>{$row['Reino']}</span>
                                        <select class='editInput form-control form-field input-sm select2 Reino' name='idReino' style='display:none;'>
                                            <option value={$row['idReino']} selected='selected'>{$row['Reino']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid' align='center'>{$row['Descricao']}</td>
                                    <td class='datagrid actions' align='center'>
                            ";
                            // Inclui os botões se for um Administrador, General ou Proprietário
                            if($EditableBy->admin('igot') || $EditableBy->general($row['idExercitoGuerreiro'])){
                                $html .= '
                                        <button onClick="EditConselheiro(this.id)" id="btnEdit'.$row['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                                        <button onClick="UpdateConselheiro(this.id)" id="btnSave'.$row['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                                        <button onClick="Delete(this.id, Conselhos)" id="btnDelete'.$row['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                                        <button onClick="Remove(this.id, Conselhos, '.$postURL.')" id="btnRemove'.$row['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                                        <button onclick="Cancel(this.id, Conselhos)" id="btnCancel'.$row['id'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>
                                ';
                            }
                            $html .= '
                                    </td>
                                </tr>
                            ';
                        }
                    break;
                }
            break;

            case 'Eventos':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        if(isset($_GET['Custo'])) {
                            // Prepara resultado de Custo Total
                            $row = $result->fetch_assoc();
                            $html = "R$&nbsp;".number_format($row['TotalCusto'], 2, ',', '.');
                        } else { // Prepara a Tabela a ser impressa na tela
                            $postURL = "'/igot/admin/ajax/updEventos.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                            $optStatusEvento = getOptions("eventosstatus"); // Prepara as opções de Status de Evento
                            $optStatusAprovacao = getOptions("statusaprovacao"); // Prepara as opções de Status de Aprovação
                            // Montagem do linhas da tabela Registro de Eventos
                            for($i = 0; $row = $result->fetch_assoc(); $i++){
                                $contador = $i +1;   
                                // Verifica se o item é editável verificando as permissões de Administrador, General e Proprietário
                                if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito']) || $EditableBy->proprietario($row['idProprietario'])){
                                    $Editable = 1; // True
                                } else {
                                    $Editable = 0; // False
                                }
                                // Monta a linha da tabela
                                $html .= "
                                    <tr name='{$row['id']}'>
                                 
                                        <td class='datagrid' align='center'>
                                            <span class=''>$contador</span>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class=''>{$row['id']}</span>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways Categoria'>{$row['Categoria']}</span>
                                        </td>
                                        <td class='datagrid' style='max-width:200px;'>
                                            <span class='viewAlways Tipo'>{$row['Tipo']}</span>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewAlways Guerreiro'>{$row['Guerreiro']}</span>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways Alianca'>{$row['NomeAlianca']}</span>
                                        </td>
                                        <td class='datagrid' style='max-width:100px;'>
                                            <span class='viewAlways Torre'>{$row['Torre']}</span>
                                        </td>
                                        <td class='datagrid' align='right'>
                                            <span class='viewOnly Custo'>R$&nbsp;".number_format($row['Custo'], 2, ',', '.')."</span>
                                            <input class='editInput form-control form-field input-sm Custo' name='Custo' data-a-sign='R$ ' data-a-sep='.' data-a-dec=',' value='{$row['Custo']}' style='display:none; text-align:right;'/>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class='viewOnly StatusEvento'>{$row['Status']}</span>
                                            <select class='editInput form-control form-field input-sm select2 StatusEvento' name='idStatus' style='display:none;'>
                                                <option value='{$row['idStatus']}' selected='selected'>{$row['Status']}</option>
                                                {$optStatusEvento}
                                            </select>
                                        </td>
                                        <td class='datagrid' align='center' style='max-width:80px;'>
                                            <span class='viewOnly DataInicioEvento'>{$row['DataInicioEvento']}</span>
                                            <input class='editInput form-control form-field input-sm' type='date' name='DataInicioEvento' value='{$row['DataInicioEvento_YMD']}' style='display:none;'>
                                        </td>
                                        <td class='datagrid' align='center' style='max-width:80px;'>
                                            <span class='viewOnly DataConclusao'>{$row['DataConclusao']}</span>
                                            <input class='editInput form-control form-field input-sm' type='date' name='DataConclusao' value='{$row['DataConclusao_YMD']}' style='display:none;'>
                                        </td>
                                        <td class='datagrid' align='center' style='max-width:80px;'>
                                            <span class='viewOnly DataExpiracaoEvento'>{$row['DataExpiracaoEvento']}</span>
                                            <input class='editInput form-control form-field input-sm' type='date' name='DataExpiracaoEvento' value='{$row['DataExpiracaoEvento_YMD']}' style='display:none;'>
                                        </td>
                                        <td class='datagrid' align='center'>
                                ";
                                // Inclui edição do Status de Aprovação se for um Administrador ou General
                                if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito'])){
                                    $html .= "
                                            <span class='viewOnly StatusAprovacao'>{$row['StatusAprovacao']}</span>
                                            <select class='editInput form-control form-field input-sm select2 StatusAprovacao' name='idStatusAprovacao' style='display:none;'>
                                                <option value='{$row['idStatusAprovacao']}' selected='selected'>{$row['StatusAprovacao']}</option>
                                                {$optStatusAprovacao}
                                            </select>
                                    ";
                                } else {
                                    $html .= "
                                            <span class='viewAlways StatusAprovacao'>{$row['StatusAprovacao']}</span>
                                    ";
                                }
                                $html .= "
                                        </td>
                                        <td class='datagrid actions' align='center'>
                                ";
                                // Inclui o botão para download do Certificado se houver
                                if($row['idCertificado']){
                                    $html .= '
                                            <input name="Certificado" value="'.$row['idCertificado'].'" class="editInput hided" style="display:none;"/>
                                            <div class="tooltip btnDownload"><a href="/download.php?id='.$row['idCertificado'].'" target="_blank" class="btn btn-sm btn-default btnDownload"><span class="fa fa-file-pdf-o"></span></a><span class="tooltiptext">Certificado</span></div>
                                    ';
                                }
                                $html .= '
                                            <div class="tooltip btnModal"><button onClick="showModalEvento(this.id, '.$Editable.')" id="btnModal'.$row['id'].'" type="button" class="btn btn-sm btn-default btnModal" style="float:none;"><span class="fa fa-list-alt"></span></button><span class="tooltiptext">Detalhes</span></div>
                                ';
                                // Inclui os botões se for um Administrador, General ou o próprio Guerreiro
                                if($Editable){
                                    $html .= '
                                            <div class="tooltip btnEdit"><button onClick="EditEventos(this.id)" id="btnEdit'.$row['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="fa fa-pencil"></span></button><span class="tooltiptext">Editar</span></div>
                                            <div class="tooltip btnSave" style="display:none;"><button onClick="UpdateEventos(this.id)" id="btnSave'.$row['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button><span class="tooltiptext">Salvar</span></div>
                                            <div class="tooltip btnDelete"><button onClick="Delete(this.id, Eventos)" id="btnDelete'.$row['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="fa fa-trash"></span></button><span class="tooltiptext">Excluir</span></div>
                                            <div class="tooltip btnRemove" style="display:none;"><button onClick="Remove(this.id, Eventos, '.$postURL.')" id="btnRemove'.$row['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button><span class="tooltiptext">Confirmar Exclusão</span></div>
                                            <div class="tooltip btnCancel" style="display:none;"><button onclick="Cancel(this.id, Eventos)" id="btnCancel'.$row['id'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button><span class="tooltiptext">Cancelar</span></div>
                                    ';
                                }
                                $html .= '
                                        </td>
                                    </tr>
                                ';
                            }
                        }
                        
                    break;

                    case 'Form': // Prepara o HTML em formato de Formulário para visualização/edição de um item
                        $row = $result->fetch_assoc();
                        // Verifica se o item é editável verificando as permissões de Administrador, General e Proprietário
                        if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito']) || $EditableBy->proprietario($row['idProprietario'])){
                            $Editable = 1; // True
                        } else {
                            $Editable = 0; // False
                        }
                        // Obtém o(s) arquivo(s) do Evento
                        $db_filebox = new FileBox();
                        $arquivos = $db_filebox->getArquivo(0, IGOT_EVENTOS, $row['id']);
                        // Monta a lista de arquivos
                        $postURL = "'/admin/ajax/upload.php'"; // Endereço da página com as funções SQL para manipulação do Arquivo
                        $files = "";
                        for ($i=0; $i<count($arquivos); $i++){
                            // Incremento da Lista de Arquivos
                            $files .= "
                                <div class='row' name={$arquivos[$i]['id']} style='padding-top:1px;padding-bottom:1px;'>
                                    <div class='col-sm-9' style='margin-top:5px;'>
                                        <input name='{$arquivos[$i]['Tipo']}' value='{$arquivos[$i]['id']}' class='editInput form-control' style='display:none;'/>
                                        <a href='/download.php?id={$arquivos[$i]['id']}' target='_blank' style='color:#555;'><span class='fa fa-download'></span> {$arquivos[$i]['Tipo']}</a>
                                    </div>
                            ";
                            // Inclui os botões se for um Administrador, General ou o próprio Guerreiro
                            if($Editable){
                                $files .= '
                                    <div class="col-sm-3">
                                        <button onClick="Delete(this.id, eventos_arquivos)" id="btnDelete'.$arquivos[$i]['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="fa fa-trash"></span></button>
                                        <button onClick="Remove(this.id, eventos_arquivos, '.$postURL.', '.IGOT_EVENTOS.')" id="btnRemove'.$arquivos[$i]['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                                    </div>
                                ';
                            }
                            $files .= "
                                </div>
                            ";  
                        }

                        // Monta o HTML do Formulário
                        $html .= "
                            <div class='row form'>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Categoria'>Categoria do Evento</label>
                                        <select class='form-control form-field select2 CategoriaEvento' name='Categoria' id='modal_eventos_categoria'"; if($Editable){$html .= ">";} else {$html .= " disabled='true'>";} $html .= "
                                            <option value='{$row['idCategoria']}' selected='selected'>{$row['Categoria']}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-sm-9'>
                                    <div class='form-group'>
                                        <label for='Evento'>Tipo de Evento</label>
                                        <div class='input-group'>
                                            <select class='editInput form-control form-field select2 TipoEvento' name='idTipo' id='modal_eventos_tipo'"; if($Editable){$html .= ">";} else {$html .= " disabled='true'>";} $html .= "
                                                <option value='{$row['idTipo']}' selected='selected'>{$row['Tipo']}</option>";
                                                $html .= getOptions("eventostipos", "categoria={$row['idCategoria']}"); // Inclui as opções de Eventos com base no Tipo
                                                $html .= "
                                                </select>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class='row form'>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Status'>Status do Evento</label>
                                        <select class='editInput form-control form-field select2 StatusEvento' name='idStatus'"; if($Editable){$html .= ">";} else {$html .= " disabled='true'>";} $html .= "
                                            <option value='{$row['idStatus']}' selected='selected'>{$row['Status']}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Custo'>Custo</label>
                                        <div class='input-group'>
                                            <div class='input-group-addon'>R$</div>
                                            <input class='editInput form-control form-field Custo' name='Custo' data-a-sep='.' data-a-dec=',' value='{$row['Custo']}'"; if($Editable){$html .= "/>";} else {$html .= " readonly/>";} $html .= "
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Moedas'>Moedas</label>
                                        <input class='form-control form-field' id='Moedas' name='Moedas' value='{$row['Moedas']}'"; if($Editable){$html .= "/>";} else {$html .= " readonly/>";} $html .= "
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Status'>Status da Aprovação</label>
                                        <select class='editInput form-control form-field select2 StatusAprovacao' name='idStatusAprovacao'"; if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito'])){$html .= ">";} else {$html .= "disabled='true'>";} $html .= "
                                            <option value='{$row['idStatusAprovacao']}' selected='selected'>{$row['StatusAprovacao']}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='row form'>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Alianca'>Aliança</label>
                                        <select class='form-control form-field select2 Alianca' name='Alianca' id='modal_eventos_alianca'"; if($Editable){$html .= ">";} else {$html .= " disabled='true'>";} $html .= "
                                            <option value='{$row['idAlianca']}' selected='selected'>{$row['NomeAlianca']}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Torre'>Torre</label>
                                        <select class='editInput form-control form-field select2 Torre' name='idTorre' id='modal_eventos_torre'"; if($Editable){$html .= ">";} else {$html .= " disabled='true'>";} $html .= "
                                            <option value='{$row['idTorre']}' selected='selected'>{$row['Torre']}</option>";
                                            $html .= getOptions("torres", "alianca={$row['idAlianca']}"); // Inclui as opções de Torres com base na Aliança do Evento
                                            $html .= "
                                        </select>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Exercito'>Exército</label>
                                        <select class='form-control form-field select2 Exercito' name='idExercito' id='modal_eventos_exercito'"; if($EditableBy->admin('igot')){$html .= ">";} else {$html .= "disabled='true'>";} $html .= "
                                            <option value='{$row['idExercito']}' selected='selected'>{$row['Exercito']}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Guerreiro'>Guerreiro</label>
                                        <select class='editInput form-control form-field select2 Guerreiro' name='idGuerreiro' id='modal_eventos_guerreiro'"; if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito'])){$html .= ">";} else {$html .= "disabled='true'>";} $html .= "                                            
                                            <option value='{$row['idGuerreiro']}' selected='selected'>{$row['Guerreiro']}</option>";
                                            $html .= getOptions("guerreiros", "exercito={$row['idExercito']}"); // Inclui as opções de Guerreiros com base no Exército do Evento
                                            $html .= "
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='row form'>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='DataInicioEvento'>Data de Início</label>
                                        <div class='input-group'>
                                            <div class='input-group-addon'><i class='fa fa-calendar'></i></div>
                                            <input class='editInput form-control form-field' name='DataInicioEvento' type='date' value='{$row['DataInicioEvento_YMD']}'"; if($Editable){$html .= "/>";} else {$html .= " readonly/>";} $html .= "
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='DataConclusao'>Data de Conclusão</label>
                                        <div class='input-group'>
                                            <div class='input-group-addon'><i class='fa fa-calendar'></i></div>
                                            <input class='editInput form-control form-field' name='DataConclusao' type='date' value='{$row['DataConclusao_YMD']}'"; if($Editable){$html .= "/>";} else {$html .= " readonly/>";} $html .= "
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='DataExpiracaoEvento'>Expira em</label>
                                        <div class='input-group'>
                                            <div class='input-group-addon'><i class='fa fa-calendar'></i></div>
                                            <input class='editInput form-control form-field' name='DataExpiracaoEvento' type='date' value='{$row['DataExpiracaoEvento_YMD']}'"; if($Editable){$html .= "/>";} else {$html .= " readonly/>";} $html .= "
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Proprietario'>Proprietário</label>
                                        <select class='editInput form-control form-field select2 Proprietario' name='idProprietario' id='modal_eventos_proprietario'"; if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito'])){$html .= ">";} else {$html .= "disabled='true'>";} $html .= "
                                            <option value='{$row['idProprietario']}' selected='selected'>{$row['Proprietario']}</option>";
                                            if($row['idProprietario'] != $_SESSION['user']['id']){ // Inclui a opção do usuário corrente se este não estiver na lista
                                                $html .= "<option value='{$_SESSION['user']['id']}'>{$_SESSION['user']['name']}</option>";
                                            } elseif($row['Proprietario'] != $row['Guerreiro']){ // Inclui o usuário do guerreiro se este não estiver na lista
                                                $html .= getOptions("proprietarios", "guerreiro={$row['idGuerreiro']}"); // Inclui as opções de Usuários com base no Guerreiro do Evento
                                            }
                                            $html .= "
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='row form'>
                                <div class='col-sm-9'>
                                    <div class='form-group'>
                                        <label for='Notas'>Observações</label>
                                        <textarea class='editInput form-control form-field' rows='5' name='Notas'"; if($Editable){$html .= ">";} else {$html .= " readonly>";} $html .= "{$row['Notas']}</textarea>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    <div class='form-group'>
                                        <label for='Arquivos'>Arquivos</label>
                                        <div class='form-control Arquivos' id='eventos_arquivos' style='width:100%;height:100%;min-height:115px;'>
                                            <div class='Uploading' style='display:none;'></div>
                                            {$files}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='row from'>
                                <div class='col-sm-12'>
                                    <span>Registrado por {$row['RegistradoPor']} em {$row['RegistradoEm']}</span>"; if($row['ModificadoPor']!=""){$html .= " | Modificado por {$row['ModificadoPor']} em {$row['ModificadoEm']}</span>";} $html .= "
                                </div>
                            </div>
                            <!-- Modal - Upload -->
                            <div class='modal fade' id='eventos_upload_modal' style='display:none;'>
                                <div class='modal-dialog modal-megamenu'>
                                    <div class='modal-content'>
                                        <form role='form' id='eventos_upload_form'>
                                            <div class='modal-header'>
                                                <button onclick='closeModalEventoUpload()' class='close' aria-label='Close' type='button'><span class='fa fa-times' aria-hidden='true'></span></button>
                                                <h4 class='modal-title'>Upload de Arquivos</h4>
                                            </div>
                                            <div class='modal-body'>
                                                <div class='row form'>
                                                    <div class='col-sm-4'>
                                                        <label for='fileToUpload'>Arquivo</label>
                                                        <input class='editInput form-control form-field' type='file' name='Arquivo' id='modal_eventos_Arquivo'>
                                                    </div>
                                                    <div class='col-sm-4'>
                                                        <label for='Tipo'>Tipo de Arquivo</label>
                                                        <select class='form-control form-field select2 TipoArquivo' name='TipoArquivo' id='modal_eventos_TipoArquivo'></select>
                                                    </div>
                                                    <div class='col-sm-4'>
                                                        <label for='Descricao'>Descrição</label>
                                                        <input class='editInput form-control form-field' type='text' name='DescricaoArquivo' id='modal_eventos_DescricaoArquivo'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='modal-footer'>
                                                <button onclick='closeModalEventoUpload()' type='button' class='btn btn-default'>Fechar</button>
                                                <button onclick='UploadFile2Evento(this.id)' id='btnUpload{$row['id']}' type='button' class='btn btn-success btnSave'><span class='fa fa-save'></span> Enviar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>            
                        ";
                    break;
                }
            break;

            case 'Arquivos':
                 switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        if(isset($_GET['Custo'])) {
                            // Prepara resultado de Custo Total
                            $row = $result->fetch_assoc();
                            $html = "R$&nbsp;".number_format($row['TotalCusto'], 2, ',', '.');
                        } else { // Prepara a Tabela a ser impressa na tela
                            $postURL = "'/igot/admin/ajax/updEventos.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                            $optStatusEvento = getOptions("eventosstatus"); // Prepara as opções de Status de Evento
                            $optStatusAprovacao = getOptions("statusaprovacao"); // Prepara as opções de Status de Aprovação
                            // Montagem do linhas da tabela Registro de Eventos
                            for($i = 0; $row = $result->fetch_assoc(); $i++){
                                $contador = $i +1;   
                                // Verifica se o item é editável verificando as permissões de Administrador, General e Proprietário
                                if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito']) || $EditableBy->proprietario($row['idProprietario'])){
                                    $Editable = 1; // True
                                } else {
                                    $Editable = 0; // False
                                }
                                // Monta a linha da tabela
                                $html .= "
                                    <tr name='{$row['idArquivo']}'>
                                       
                                        <td class='datagrid' align='center'>
                                            <div class='icheckbox_flat-blue' id='oi' aria-checked='false' aria-disabled='false'>
                                                <input class='testee' type='checkbox'  name='array[]' id='{$row['idArquivo']}' value='{$row['idArquivo']}'>
                                            </div>
                                        </td>                
                                             
                                        <td class='datagrid' align='center'>
                                            <span class=''>$contador</span>
                                        </td>      
                                        
                                        <td class='datagrid' align='center'>
                                            <span class=''>{$row['idTipoEvento']}</span>
                                        </td>      
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways Categoria'>{$row['Categoria']}</span>
                                        </td>
                                        <td class='datagrid' style='max-width:200px;'>
                                            <span class='viewAlways Tipo'>{$row['TipoEvento']}</span>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewAlways Guerreiro'>{$row['Guerreiro']}</span>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways Alianca'>{$row['Alianca']}</span>
                                        </td>
                                        <td class='datagrid' style='max-width:100px;'>
                                            <span class='viewAlways Torre'>{$row['Torre']}</span>
                                        </td>
                                        <td class='datagrid' align='center' style='max-width:80px;'>
                                            <span class='viewOnly DataInicioEvento'>{$row['DataInicioEvento']}</span>
                                        </td>
                                        <td class='datagrid' align='center' style='max-width:80px;'>
                                            <span class='viewOnly DataConclusao'>{$row['DataConclusao']}</span>
                                        </td>
                                       
                                        <td class='datagrid actions' align='center'>
                                            <div class='tooltip btnDownload'><a href='/download.php?id={$row['idArquivo']}' target='_blank' class='btn btn-sm btn-default btnDownload'><span class='fa fa-file-pdf-o'></span></a><span class='tooltiptext'>Certificado</span></div>
                                        </td>
                                    </tr>"
                                ;
                            }
                        }
                    break;
                }
            break;
            
            case 'BuscaAvancada':
                 switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        if(isset($_GET['Custo'])) {
                            // Prepara resultado de Custo Total
                            $row = $result->fetch_assoc();
                            $html = "R$&nbsp;".number_format($row['TotalCusto'], 2, ',', '.');
                        } else { // Prepara a Tabela a ser impressa na tela
                            $postURL = "'/igot/admin/ajax/updEventos.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                            $optStatusEvento = getOptions("eventosstatus"); // Prepara as opções de Status de Evento
                            $optStatusAprovacao = getOptions("statusaprovacao"); // Prepara as opções de Status de Aprovação
                            // Montagem do linhas da tabela Registro de Eventos
                            while($row = $result->fetch_assoc()){
                                // Verifica se o item é editável verificando as permissões de Administrador, General e Proprietário
                                if($EditableBy->admin('igot') || $EditableBy->general($row['idExercito']) || $EditableBy->proprietario($row['idProprietario'])){
                                    $Editable = 1; // True
                                } else {
                                    $Editable = 0; // False
                                }
                                // Monta a linha da tabela
                                $html .= "
                                    <tr name='{$row['idArquivo']}'>
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways Categoria'>{$row['Categoria']}</span>
                                        </td>
                                        <td class='datagrid' style='max-width:200px;'>
                                            <span class='viewAlways Tipo'>{$row['TipoEvento']}</span>
                                        </td>
                                        <td class='datagrid'>
                                            <span class='viewAlways Guerreiro'>{$row['Guerreiro']}</span>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways Alianca'>{$row['Alianca']}</span>
                                        </td>
                                        <td class='datagrid' style='max-width:100px;'>
                                            <span class='viewAlways Torre'>{$row['Torre']}</span>
                                        </td>
                                        <td class='datagrid' align='center' style='max-width:80px;'>
                                            <span class='viewOnly DataInicioEvento'>{$row['DataInicioEvento']}</span>
                                        </td>
                                        <td class='datagrid' align='center' style='max-width:80px;'>
                                            <span class='viewOnly DataConclusao'>{$row['DataConclusao']}</span>
                                        </td>
                                        <td class='datagrid' align='center'>
                                            <span class='viewAlways'>{$row['TipoArquivo']}</span>
                                        </td>
                                        <td class='datagrid actions' align='center'>
                                            <div class='tooltip btnDownload'><a href='/download.php?id={$row['idArquivo']}' target='_blank' class='btn btn-sm btn-default btnDownload'><span class='fa fa-file-pdf-o'></span></a><span class='tooltiptext'>Certificado</span></div>
                                        </td>
                                    </tr>"
                                ;
                            }
                        }
                    break;
                }
            break;

            case 'EventosTipos':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        $postURL = "'ajax/updEventosTipos.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                        // Montagem das linhas da tabela Registro de Eventos
                        while($row = $result->fetch_assoc()){
                            $html .= "
                                <tr id='{$row['id']}' name='{$row['id']}'>
                                    <td class='datagrid' align='center'>{$row['id']}</td>                    
                                    <td class='datagrid'>
                                        <span class='viewOnly Categoria'>{$row['Categoria']}</span>
                                        <select class='editInput form-control form-field select2 Categoria' name='idCategoria' style='display:none;'>
                                            <option value='{$row['idCategoria']}' selected='selected'>{$row['Categoria']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Alianca'>{$row['Alianca']}</span>
                                        <select class='editInput form-control form-field select2 Alianca' name='idAlianca' style='display:none;'>
                                            <option value='{$row['idAlianca']}' selected='selected'>{$row['Alianca']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Tipo'>{$row['Tipo']}</span>
                                        <input class='editInput form-control form-field input-sm' type='text' name='Tipo' value='{$row['Tipo']}' style='display:none;'>
                                    </td>
                                    <td class='datagrid' align='center' style='max-width:80px;'>
                                        <span class='viewOnly DataInicio'>{$row['DataInicio']}</span>
                                        <input class='editInput form-control form-field input-sm DataInicio' type='date' name='DataInicio' value='{$row['DataInicio']}' style='display:none;'>
                                    </td>
                                    <td class='datagrid' align='center' style='max-width:80px;'>
                                        <span class='viewOnly DataFim'>{$row['DataFim']}</span>
                                        <input class='editInput form-control form-field input-sm DataFim' type='date' name='DataFim' value='{$row['DataFim']}' style='display:none;'>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly LinkInscricao'>{$row['LinkInscricao']}</span>
                                        <input class='editInput form-control form-field input-sm LinkInscricao' type='text' name='LinkInscricao' value='{$row['LinkInscricao']}' style='display:none;'>
                                    </td>";
                                    
                                    
                            // Inclui coluna com botões se for um administrador
                            if($EditableBy->admin('igot')){ $html .= '
                                    <td class="datagrid" align="center">
                                        <button onClick="EditTipoEvento(this.id)" id="btnEdit'.$row['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                                        <button onClick="Update(this.id)" id="btnSave'.$row['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                                        <button onClick="Delete(this.id)" id="btnDelete'.$row['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                                        <button onClick="Remove(this.id, tblDataGrid, '.$postURL.')" id="btnRemove'.$row['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                                        <button onclick="Cancel(this.id)" id="btnCancel'.$row['id'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>
                                    </td>';
                            }
                            $html .= '
                                </tr>
                            ';
                        }
                    break;

                    case 'Table': // Prepara o HTML em formato de Tabela para visualização dos itens
                        // Prepara a Tabela e monta o cabeçalho dos Próximos Eventos
                        $html .= "
                            <table class='table table-bordered table-hover table-striped dataTable' role='grid'>
                                <thead>
                                    <tr role='row' class='tblTitleRow'>
                                        <th class='datagrid'>Aliança</th>
                                        <th class='datagrid'>Evento</th>
                                        <th class='datagrid'>Inicio</th>
                                        <th class='datagrid'>Fim</th>
                                        <th class='datagrid'>Link</th>
                                    </tr>
                                </thead>
                                <tbody class='row_position'>
                        ";

                        // Montagem das linhas da tabela Registro de Eventos
                        while($row = $result->fetch_assoc()){
                            $html .= "
                                    <tr>
                                        <td class='datagrid' align='center'>{$row['Alianca']}</td>
                                        <td class='datagrid'>{$row['Tipo']}</td>
                                        <td class='datagrid' align='center'>{$row['DataInicio']}</td>
                                        <td class='datagrid' align='center'>{$row['DataFim']}</td>
                                        <td class='datagrid' align='center'><a href='{$row['Link']}'>Inscrever</a></td>
                                    </tr>
                            ";
                        }

                        // Fecha a tabela
                        $html .= "
                                </tbody>
                            </table>
                        ";
                    break;
                }
            break;

            case 'Torres':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        $postURL = "'ajax/updTorres.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                        // Montagem das linhas da tabela Torres
                        while($row = $result->fetch_assoc()){
                            $html .= "
                                <tr id='{$row['id']}' name='{$row['id']}'>
                                    <td class='datagrid' align='center'>{$row['id']}</td>                    
                                    <td class='datagrid' align='center'>
                                        <span class='viewOnly Reino'>{$row['Reino']}</span>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Castelo'>{$row['Castelo']}</span>
                                        <select class='form-control form-field select2 input-sm editInput Castelo' name='idCastelo' style='display:none;'>
                                            <option value={$row['idCastelo']} selected='selected'>{$row['Castelo']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Alianca'>{$row['Alianca']}</span>
                                        <select class='form-control form-field select2 input-sm editInput Alianca' name='idAlianca' style='display:none;'>
                                            <option value={$row['idAlianca']} selected='selected'>{$row['Alianca']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Torre'>{$row['Torre']}</span>
                                        <input class='form-control form-field input-sm editInput Torre' name='Torre' value='{$row['Torre']}' style='display:none;'/>
                                    </td>
                                    <td class='datagrid actions' align='center'>
                            ";
                            // Inclui os botões se for um Administrador, General ou Proprietário
                            if($EditableBy->admin('igot') || $EditableBy->general($row['idExercitoGuerreiro']) || $EditableBy->proprietario($row['idProprietario'])){
                                $html .= '
                                        <button onClick="EditTorre(this.id)" id="btnEdit'.$row['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                                        <button onClick="Update(this.id)" id="btnSave'.$row['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                                        <button onClick="Delete(this.id)" id="btnDelete'.$row['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                                        <button onClick="Remove(this.id, tblDataGrid, '.$postURL.')" id="btnRemove'.$row['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                                        <button onclick="Cancel(this.id)" id="btnCancel'.$row['id'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>';
                            }
                            $html .= '
                                    </td>
                                </tr>
                            ';
                        }
                    break;
                }
            break;

            case 'Conformidades':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'DataGrid': // Prepara o HTML em formato de Tabela, DataGrid para visualização/edição dos itens
                        $postURL = "'ajax/updConformidades.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                        // Montagem das linhas da tabela Registro de Eventos
                        while($row = $result->fetch_assoc()){
                            $html .= "
                                <tr id='{$row['id']}' name='{$row['id']}'>
                                    <td class='datagrid' align='center'>{$row['id']}</td>                    
                                    <td class='datagrid'>
                                        <span class='viewOnly Conformidade'>{$row['Nome']}</span>
                                        <input class='editInput form-control form-field input-sm' type='text' name='Conformidade' value='{$row['Nome']}' style='display:none;'>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Tipo'>{$row['Tipo']}</span>
                                        <select class='editInput form-control form-field select2 Tipo' name='idTipoConformidade' style='display:none;'>
                                            <option value='{$row['idTipoConformidade']}' selected='selected'>{$row['Tipo']}</option>
                                        </select>
                                    </td>
                                    <td class='datagrid'>
                                        <span class='viewOnly Alianca'>{$row['Alianca']}</span>
                                        <select class='editInput form-control form-field select2 Alianca' name='idAlianca' style='display:none;'>
                                            <option value='{$row['idAlianca']}' selected='selected'>{$row['Alianca']}</option>
                                        </select>
                                    </td>";
                            // Inclui coluna com botões se for um administrador
                            if($EditableBy->admin('igot')){ $html .= '
                                    <td class="datagrid" align="center">
                                        <button onClick="EditConformidade(this.id)" id="btnEdit'.$row['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                                        <button onClick="Update(this.id)" id="btnSave'.$row['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                                        <button onClick="Delete(this.id)" id="btnDelete'.$row['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                                        <button onClick="Remove(this.id, tblDataGrid, '.$postURL.')" id="btnRemove'.$row['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                                        <button onclick="Cancel(this.id)" id="btnCancel'.$row['id'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>
                                    </td>';
                            }
                            $html .= '
                                </tr>
                            ';
                        }
                    break;

                    case 'List': // Prepara o HTML em formato de Lista para visualização dos itens
                        // Instancia os objetos referentes as bases de dados do IGOT e FileBox
                        $db_igot = new IGOT();
                        $db_filebox = new filebox();
                        // Montagem da Lista de Conformidades -> Regras da Conformidade -> Guerreiros relacionados -> Eventos Requeridos
                        while($conformidade = $result->fetch_assoc()){
                            // Lista as Conformidade
                            $html .= '
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="conformidade" data-toggle="collapse" data-target="#Conformidade'.$conformidade['id'].'" href="#Conformidade'.$conformidade['id'].'">'.$conformidade['Tipo'].' '.$conformidade['Alianca'].' | '.$conformidade['Nome'].'</a>
                                        </h4>
                                    </div>
                                    <div id="Conformidade'.$conformidade['id'].'" class="panel-collapse collapse">
                                        <ul class="list-group">
                            ';
                            // Obtém as Regras da Conformidade
                            $regras = $db_igot->getConformidadesRegras($conformidade['id']);
                            // Listagem das Regras da Conformidade
                            foreach($regras as $regra){
                                // Lista as Regras da Conformidade
                                $html .= '
                                            <li class="list-group-item list-group-item-n1">
                                                <a class="conformidade-nok'; if($regra['QtdeAtendidos']>=$regra['QtdeRequerida']){$html .= " conformidade-ok";} $html .= '" data-toggle="collapse" data-target="#Regra'.$regra['id'].'" href="#Regra'.$regra['id'].'">
                                                    <table stayle="width:100%;">
                                                        <tr>
                                                            <td style="padding-right:10px; width:60px;">'.$regra['QtdeAtendidos'].' de '.$regra['QtdeRequerida'].'</td>
                                                            <td style="padding-left:10px; padding-right:10px; border-left:1px solid #ddd; border-right:1px solid #ddd; width:250px;">'.$regra['Nome'].'</td>
                                                            <td style="padding-left:10px;">
                                ';
                                foreach($regra['Requisitos'] as $requisito) {
                                    $html .= '
                                                                <div class="row">
                                                                    <div class="col-md-12">'.$requisito['Ordem'].'º '.$requisito['TipoEvento'].'</div>
                                                                </div>
                                    ';
                                }
                                $html .= '
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </a>
                                            </li>
                                            <div id="Regra'.$regra['id'].'" class="panel-collapse collapse" style="margin:5px;">
                                                <table class="table table-bordered table-hover table-striped dataTable" role="grid">
                                                    <thead>
                                                        <tr role="row" class="tblTitleRow">
                                                            <th class="datagrid">Guerreiro</th>
                                                            <th class="datagrid">Exército</th>
                                                            <th class="datagrid">Status</th>
                                                            <th class="datagrid">Requisito'; if($regra['QtdeRequisitos']>1){$html .= 's';} $html .= '</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="row_position">
                                ';
                                // Obtém os Guerreiros relacionados à Regra da Conformidade
                                $guerreiros = $db_igot->getConformidadesGuerreiros($regra['id']);
                                // Lista os Guerreiros relacionados à Regra da Conformidade
                                foreach($guerreiros as $guerreiro){                    
                                    //Obtém Imagem do Perfil
                                    $img = $db_filebox->loadImagem(2, $guerreiro['idUsuario']);                                
                                    $html .= '
                                                        <tr>
                                                            <td class="datagrid" align="center"><img class="profile-user-img img-responsive img-circle" style="width:30px; height:30px; border:3px solid '; if($guerreiro['QtdeConcluidos']==$regra['QtdeRequisitos']){if($guerreiro['EventosExpirando']==null){$html.='#70ad47;';}else{$html.='#f39c12;';}}else{$html.='#999999;';} $html .= '" src="'.$img.'">'.$guerreiro['Nome'].'</td>
                                                            <td class="datagrid" align="center">'.$guerreiro['Exercito'].'</td>
                                                            <td class="datagrid" align="center"><span style="font-size:11px;" class="label '; if($guerreiro['QtdeConcluidos']==$regra['QtdeRequisitos']){if($guerreiro['EventosExpirando']==null){$html.='label-success">Atendidos';}else{$html.='label-warning">Expirando';}}else{$html.='label-default">Pendente';} $html .= '</span></td>
                                                            <td class="datagrid">
                                    ';
                                    // Obtém os Eventos do Guerreiro                                    
                                    $eventos = $db_igot->getConformidadesEventos($regra['id'], $guerreiro['id']);
                                    foreach($eventos as $evento){
                                        // Lista os Eventos do Guerreiro formatado-os com base no Status
                                        $html .= '
                                                                <div class="row'; if($evento['idStatus']==4){if($evento['Expirando']){$html .= ' conformidade-expirando';}else{$html .= ' conformidade-ok';}} $html .= '" style="margin-top:2px; margin-bottom:2px;">
                                                                    <div class="col-md-6">'.$evento['Nome'].'</div>
                                                                    <div class="col-md-3">'; if($evento['idStatus']==4){$html .= 'Concluído em: ';}else{$html .= 'Previsto para: ';} $html .= $evento['DataConclusao'].'</div>
                                                                    <div class="col-md-3">'; if($evento['idStatus']==4){$html .= 'Expira em: '.$evento['DataExpiracao'];} $html .= '</div>
                                                                </div>
                                        ';
                                    }
                                    // Fecha a lista de Guerreiros relacionados à Regra da Conformidade
                                    $html .= '
                                                            </td>
                                                        </tr>
                                    ';
                                }
                                // Fecha a lista de Regras da Conformidade
                                $html .= '
                                                    </tbody>
                                                </table>
                                            </div>
                                ';
                            }
                            // Fecha a lista de Conformidades
                            $html .= '                            
                                        </ul>
                                    </div>
                                </div>
                            ';
                        }
                        // Remove o objeto referente a base de dados do IGOT
                        unset($db_igot);
                    break;
                }
            break;

            case 'Reinos':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    case 'List': // Prepara o HTML em formato de Lista para visualização dos itens
                        // Montagem da Lista de Reinos
                        for($i=0; $i<mysqli_num_rows($result); $i++){
                            $row = $result->fetch_assoc();
                            // Abre uma nova linha formatando-a com base na posição do array
                            switch($i){
                                case 0: // Linha 1
                                    $html .=
                                    '   <div class="row" style="margin-top:14%;">';
                                break;
                                case 3: // Linha 2
                                    $html .=
                                    '   <div class="row" style="margin-top:2%;">';
                                break;
                                case 6: // Linha 3
                                    $html .=
                                    '   <div class="row" style="margin-top:2%;">';
                                break;
                            }
                            // Abre uma nova coluna formatando-a com base na posição do array
                            switch($i){
                                case 0: // Coluna 1 - Linha 1
                                    $html .=
                                    '       <div class="col-xs-4 col-sm-4 col-md-4" style="margin-top:6%;">';
                                break;
                                case 1: // Coluna 2 - Linha 1
                                    $html .=
                                    '       <div class="col-xs-4 col-sm-4 col-md-4" style="padding-top:2%; margin-left:-5%;">';
                                break;
                                case 2: // Coluna 3 - Linha 1
                                    $html .=
                                    '       <div class="col-xs-4 col-sm-4 col-md-4" style="margin-left:-3%;">';
                                break;
                                case 3: // Coluna 1 - Linha 2
                                    $html .=
                                    '       <div class="col-xs-4 col-sm-4 col-md-4" style="margin-top:8%; margin-left:4%;">';
                                break;
                                case 4: // Coluna 2 - Linha 2
                                    $html .=
                                    '       <div class="col-xs-4 col-sm-4 col-md-4" style="margin-top:4%; margin-left:-2%;">';
                                break;
                                case 5: // Coluna 3 - Linha 2
                                    $html .=
                                    '       <div class="col-xs-4 col-sm-4 col-md-4" style="margin-left:-4%">';
                                break;
                                case 6: // Coluna 1 - Linha 3
                                    $html .=
                                    '       <div class="col-xs-4 col-sm-4 col-md-4" style="margin-top:6%; margin-left:4%;">';
                                break;
                                case 7: // Coluna 2 - Linha 3
                                    $html .=
                                    '        <div class="col-xs-4 col-sm-4 col-md-4" style="margin-top:3%">';
                                break;
                                case 8: // Coluna 3 - Linha 3
                                    $html .=
                                    '        <div class="col-xs-4 col-sm-4 col-md-4" style="margin-left:-8%">';
                                break;
                            }
                            // Preenche a célula com o nome do Reino
                            $html .= '
                                                <span class="fa fa-map-marker"></span><br>
                                                '.$row['Nome'].'
                                            </div>
                            ';
                            // Fecha a Linha se for a Coluna 3 ou o último item do array
                            if($i==2 || $i==5 || $i==8 || $i==count($itens)-1){
                                $html .= 
                                '       </div>
                                ';    
                            }
                        }
                    break;
                    
                    case 'Form':
                        $row = $result->fetch_assoc();                         
                        // Monta o HTML do Formulário
                        $html .= "
                            <div class='row form'>
                                <div class='col-sm-6'>
                                    <div class='form-group'>
                                        <label for=''>Reino</label>
                                        <div class='input-group'>
                                            <input class='editInput form-control form-field Nome' name='Nome' value='{$row['Reino']}'></imput>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class='col-sm-6'>
                                    <div class='form-group'>
                                        <label for=''>Exército</label>
                                        <div class='input-group'>
                                            <select class='editInput form-control form-field select2 Exercito' name='idExercito'>
                                                <option value='{$row['idExercito']}' selected='selected'>{$row['Exercito']}</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class='row form'>
                                <div class='col-sm-12'>
                                    <div class='form-group'>
                                        <label>Descrição do Reino</label>
                                        <div class='input-group'>
                                                <textarea class='editInput form-control form-field' rows='5' id='editor1' name='Descricao'>{$row['Descricao']}</textarea>                                                                                   
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        ";
                    break;
                }
            break;

            case 'PortalDoReino':
                switch($_GET['frame']){ // Monta as partes do Portal
                    case "Cabecalho":
                        // Montagem do Cabeçalho do Portal
                        $row = $result->fetch_assoc();
                        $html .= "
                            <span style='font-size:45px; float:left; padding-top:20px;'><img width='80' height='80' align='center' class='menu-icon' src='/igot/img/reino-branco-1.png'></span>
                            <h3 name='NomeDoReino' style='padding-top:10px;' align='center'>{$row['Reino']}</h3>
                        ";
                    break;

                    case "Perfil":
                        // Montagem do Perfil Quantitativo do Reino
                        $row = $result->fetch_assoc();
                        $html .= "
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeMedalhas' data='{$row['Medalhas']}'>{$row['Medalhas']}</h5>
                                    <span class='description-text'>MEDALHAS</span>
                                </div>
                            </div>                                    
                            <div class='col-md-1 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeReis' data=''>0</h5>
                                    <span class='description-text'>REIS</span>
                                </div>
                            </div>
                            <div class='col-md-1 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeReis' data=''>0</h5>
                                    <span class='description-text'>LORDES</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeComandantes' data='{$row['Comandantes']}'>{$row['Comandantes']}</h5>
                                    <span class='description-text'>COMANDANTES</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeCavaleiros' data='{$row['Cavaleiros']}'>{$row['Cavaleiros']}</h5>
                                    <span class='description-text'>CAVALEIROS</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeSoldados' data='{$row['Soldados']}'>{$row['Soldados']}</h5>
                                    <span class='description-text'>SOLDADOS</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeRecrutas' data='{$row['Recrutas']}'>{$row['Recrutas']}</h5>
                                    <span class='description-text'>RECRUTAS</span>
                                </div>
                            </div>
                        ";
                    break;

                    case "Descricao":
                        // Montagem do texto Descritivo Sobre o Reino
                        $row = $result->fetch_assoc();
                        $html .= "
                            <div class='col-md-12'>
                                <div class='text-muted well well-sm no-shadow' style='margin-top:10px;'>
                                    {$row['Descricao']}
                                </div>
                            </div>                                    
                        ";
                    break;

                    case "Grafico":
                        // Popula o array esperado pelo JS do Gráfico
                        while($row = $result->fetch_assoc()){
                            $html .= "['{$row['Castelo']}',{$row['QtdeMedalhas']}],";
                        }
                        // Ajusta o array
                        $html = rtrim($html,',');
                    break;
                }
            break;

            case 'PortalDoCastelo':
                switch($_GET['frame']){ // Monta as partes do Portal
                    case "Cabecalho":
                        // Montagem do Cabeçalho do Portal
                        $row = $result->fetch_assoc();
                        $html .= "
                            <span style='font-size:45px; float:left; padding-top:20px;'><img width='80' height='80' align='center' class='menu-icon' src='/igot/img/castelo-branco-1.png'></span>
                            <h3 name='NomeDoCastelo' style='padding-top:10px;' align='center'>{$row['Castelo']}</h3>
                            <small name='BreadCrumb' style='float:right; padding-bottom:5px;'>{$row['Reino']}</small>
                        ";
                    break;
                    
                    case "Perfil":
                        // Montagem do Perfil Quantitativo da Torre
                        $row = $result->fetch_assoc();
                        $html .= "
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeMedalhas' data='{$row['Medalhas']}'>{$row['Medalhas']}</h5>
                                    <span class='description-text'>MEDALHAS</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeLordes' data=''>0</h5>
                                    <span class='description-text'>LORDES</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeComandantes' data='{$row['Comandantes']}'>{$row['Comandantes']}</h5>
                                    <span class='description-text'>COMANDANTES</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeCavaleiros' data='{$row['Cavaleiros']}'>{$row['Cavaleiros']}</h5>
                                    <span class='description-text'>CAVALEIROS</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeSoldados' data='{$row['Soldados']}'>{$row['Soldados']}</h5>
                                    <span class='description-text'>SOLDADOS</span>
                                </div>
                            </div>
                            <div class='col-md-2 border-right'>
                                <div class='description-block'>
                                    <h5 class='description-header' name='qtdeRecrutas' data='{$row['Recrutas']}'>{$row['Recrutas']}</h5>
                                    <span class='description-text'>RECRUTAS</span>
                                </div>
                            </div>
                        ";
                    break;

                    case "Descricao":
                        // Montagem do texto Descritivo Sobre o Castelo
                        $row = $result->fetch_assoc();
                        $html .= "
                            <div class='col-md-12'>
                                <div class='text-muted well well-sm no-shadow' style='margin-top:10px;'>
                                    {$row['Descricao']}
                                </div>
                            </div>                                    
                        ";
                    break;

                    case "Grafico":
                        // Popula o array esperado pelo JS do Gráfico
                        while($row = $result->fetch_assoc()){
                            $html .= "['{$row['Torre']}',{$row['QtdeMedalhas']}],";
                        }
                        // Ajusta o array
                        $html = rtrim($html,',');
                    break;
                }
            break;

            case 'PortalDaTorre':
                switch($_GET['type']){ // Identifica o tipo de HTML a ser retornado
                    default: // Prepara o HTML para visualização dos itens
                        switch($_GET['frame']){ // Monta as partes do Portal da Torre
                            case "Cabecalho":
                                // Montagem do Cabeçalho do Portal da Torre
                                $row = $result->fetch_assoc();
                                $html .= "
                                    <span style='font-size:45px; float:left; padding-top:20px;'><img width='80' height='80' align='center' class='menu-icon' src='/igot/img/torre-branca-1.png'></span>
                                    <h3 name='NomeDaTorre' style='padding-top:10px;' align='center'>{$row['Torre']}</h3>
                                    <h5 name='NomeDaAlianca' style='padding-bottom:5px; 'align='center'>{$row['Alianca']}</h5>
                                    <small name='BreadCrumb' style='float:right; padding-bottom:5px;'>{$row['Reino']} > {$row['Castelo']}</small>
                                ";
                            break;

                            case "Perfil":
                                // Montagem do Perfil Quantitativo da Torre
                                $row = $result->fetch_assoc();
                                $html .= "
                                    <div class='col-md-3 border-right'>
                                        <div class='description-block'>
                                            <h5 class='description-header' name='qtdeMedalhas' data='{$row['Medalhas']}'>{$row['Medalhas']}</h5>
                                            <span class='description-text'>MEDALHAS</span>
                                        </div>
                                    </div>
                                    <div class='col-md-3 border-right'>
                                        <div class='description-block'>
                                            <h5 class='description-header' name='qtdeComandantes' data='{$row['Comandantes']}'>{$row['Comandantes']}</h5>
                                            <span class='description-text'>COMANDANTES</span>
                                        </div>
                                    </div>
                                    <div class='col-md-2 border-right'>
                                        <div class='description-block'>
                                            <h5 class='description-header' name='qtdeCavaleiros' data='{$row['Cavaleiros']}'>{$row['Cavaleiros']}</h5>
                                            <span class='description-text'>CAVALEIROS</span>
                                        </div>
                                    </div>
                                    <div class='col-md-2 border-right'>
                                        <div class='description-block'>
                                            <h5 class='description-header' name='qtdeSoldados' data='{$row['Soldados']}'>{$row['Soldados']}</h5>
                                            <span class='description-text'>SOLDADOS</span>
                                        </div>
                                    </div>
                                    <div class='col-md-2 border-right'>
                                        <div class='description-block'>
                                            <h5 class='description-header' name='qtdeRecrutas' data='{$row['Recrutas']}'>{$row['Recrutas']}</h5>
                                            <span class='description-text'>RECRUTAS</span>
                                        </div>
                                    </div>
                                ";
                            break;

                            case "Forca-Membros":
                                // Estancia um objeto da base de dados do filebox
                                $db_filebox = new FileBox();  
                                   
                                // Montagem do Perfil da Torre - Membros da Torre
                                $html .= "
                                        <div class='col-md-12'>
                                ";
                                // Limpa a variável para armazenamento do último andar impresso
                                $posicaoAnterior = null;
                                while($row = $result->fetch_assoc()){
                                    if($posicaoAnterior != $row['idPosicao']) { // Se houver mudança de andar da torre durante a impressão
                                        if($posicaoAnterior != null){ // Se não for o último andar da Torre, primeiro a ser impresso
                                            // Fecha o andar
                                            $html .= "  
                                                    </ul>	
                                                </div> 
                                            ";
                                        }
                                        // Abre um novo andar
                                        $html .= "
                                            <div class='box-body' style='border-bottom:1px solid #f4f4f4;'>
                                                <h5 class='box-title'>{$row['idPosicao']}º Andar | {$row['Posicao']}s:</h5>
                                                <ul class='users-list clearfix'>                               
                                        ";
                                    }
                                    // Carrega a imagem do guerreiro
                                    $img = $db_filebox->loadImagem(2, $row['idUsuario']);
                                    // Imprime o Guerreiro no Andar
                                    $html .= "  
                                                    <li>
                                                        <img class='profile-user-img img-responsive img-circle' src='{$img}' style='width:50px; height:50px; border: 3px solid #f39c12;'>
                                                        <span class='users-list-name'>{$row['Guerreiro']}</span>
                                                        <span class='users-list-date'>{$row['ExercitoGuerreiro']}</span>													
                                                    </li>
                                    ";
                                    // Guarda o número do último andar impresso
                                    $posicaoAnterior = $row['idPosicao'];
                                }
                                // Fecha o último andar impresso
                                $html .= "  
                                                </ul>
                                            </div>
                                        </div>
                                ";

                               
                                //mysqli_data_seek($result, 0); // Retorna o ponteiro para o primeiro resultado
                                //$row = $result->fetch_assoc();
                            break;
                        }
                    break;
                }
                break;        
            break;

            case 'Dashboard':
                switch($_GET['frame']){
                    case 'AliancasBarChart':
                        // Popula o array esperado pelo JS do Gráfico
                        while($row = $result->fetch_assoc()){
                            $html .= "['{$row['Alianca']}',{$row['QtdeMedalhas']}],";
                        }
                        // Ajusta o array
                        $html = rtrim($html,',');
                    break;

                    case 'AliancasKnobChart':
                        while($row = $result->fetch_assoc()){
                            $number = number_format($row['pMedalhas'], 2, '.', ',');
                            $html .= "
                                <div class='col-xs-4 col-md-2 text-center'>
                                    <input type='text' class='knob' value='{$number}' data-skin='tron' data-thickness='0.2' data-width='100' data-height='100' data-fgColor='#605ca8' data-readonly='true'>
                                    <div class='knob-label'>{$row['Alianca']}</div>
                                </div>
                            ";
                        }
                    break;
                }
            break;

            case 'QuadrosDeMedalhas':
                switch($_GET['type']){ // Identifica o tipo de retorno solicitado
                    default: // Quadros de Medalhas
                        $lastExercito = null; // Identificador do Exercito do último Guerreiro impresso no Quadro de Medalha
                        while($row = $result->fetch_assoc()){
                            if($lastExercito != $row['idExercito']){ // Se o registro for de um novo exército
                                if($lastExercito != null){ // Encerra um Quadro de Medalhas se já houver algum aberto antes de iniciar um novo
                                    $html .= "
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>";
                                }
                                // Inicia um novo Quadro de Medalhas
                                $html .= "
                                    <div class='row'>
                                        <div class='col-sm-12'>
                                            <center><i><h2>{$row['Exercito']}</h2></i></center>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-sm-12'>
                                            <table class='table table-bordered table-striped dataTable'>
                                                <thead>
                                                    <tr class='tblTitleRow'>
                                                        <th class='datagrid'>Classificação</th>
                                                        <th class='datagrid'>Guerreiro</th>
                                                        <th class='datagrid'>Medalhas</th>
                                                    </tr>
                                                </thead>
                                                <tbody class='row_position'>
                                ";
                            }
                            // Linha do Quadro de Medalhas
                            $html .= "
                                                    <tr>
                                                        <td class='datagrid' align='center'>{$row['Rank']}</td>
                                                        <td class='datagrid'>{$row['Guerreiro']}</td>
                                                        <td class='datagrid' align='center'>{$row['TotalMedalhas']}</td>
                                                    </tr>
                            ";
                            // Atualiza o último exército registrado
                            $lastExercito = $row['idExercito'];
                        }
                        // Encerra o último Quadro de Medalhas impresso
                        $html .= "
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                        ";
                    break;
                }
            break;

            case 'LinhaDoTempo':
                switch($_GET['type']){ // Identifica o tipo de retorno solicitado
                    default: // Quadros de Medalhas
                        $lastDataConclusao = null;
                        // Motagem da Linha do Tempo
                        while($row = $result->fetch_assoc()){
                            // Data de Conclusão do Evento
                            if($lastDataConclusao <> $row['DataConclusao']){
                                $html .= '
                                    <!-- Data -->
                                    <li class="time-label">
                                        <span class="bg-red">
                                            '.$row['DataConclusao'].'
                                        </span>
                                    </li>
                                ';
                            }
                            // Imagem do Guerreiro
                            $db_filebox = new FileBox();
                            $img = $db_filebox->loadImagem(2, $row['idUsuario']);
                            $html .= '
                                <li>
                                    <!-- Timeline - Icone -->
                                    <img class="img-responsive img-circle" src="'.$img.'" alt="User Image" style="width:50px; height:50px; margin-left: 9px; border: 3px solid #ddd;">
                                    
                            ';
                            // Corpo do Evento
                            $html .= '
                                    <!-- Timeline - body -->                
                                    <div class="timeline-item"  style="margin-top: -45px;">
                                        <!-- Tipo de Evento -->                    
                                        <!--<h3 class="timeline-header"><b>'.$row['Guerreiro'].' - '.$row['Exercito'].'</b></h3> -->                    
                                        <h3 class="timeline-header"><i class="'.$row['Icone'].'"></i> <b>'.$row['Categoria'].'</b></h3>
                                        <!-- Descrição do Evento -->
                                        <div class="timeline-body">
                                            <b>'.$row['Guerreiro'].'</b> '.strtolower($row['Mensagem']).' '.$row['Tipo'].'<br>
                                            <b>Aliança :</b> '.$row['NomeAlianca'].'. <b>| Torre :</b> '.$row['Torre'].'   <br> <b> Custo : </b> R$ '.number_format($row['Custo'], 2, ',', '.').' <b> | Moedas : </b>'.$row['Moedas'].' 
                                        </div>
                                    </div>
                            ';
                            // Atualiza a última Data de Conclusão com a do Evento Impresso
                            $lastDataConclusao = $row['DataConclusao'];
                        }
                        // Botão para visualizar mais Eventos na Linha do Tempo
                        if(isset($_GET['pag'])){ $nPag = $_GET['pag']+10; } else { $nPag = 10; } // Próxima Página                        
                        $html .= '
                                    <li class="time-label button">
                                        <span onclick=tabLinhaDoTempo('.$nPag.') class="btn btn-sm bg-red" type="button"><span class="fa fa-plus"></span> Ver mais</span>
                                    </li>
                        ';
                    break;
                }
            break;
        }

        // Impressão das Linhas da Tabela
        echo $html;
    }
?>