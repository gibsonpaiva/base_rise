<?php
    // Inclue objetos para verificação de permissão necessária        
    include_once "../config/permissions.php"; // Inclue o objeto referente a verificação de permissão
    $MemberOf = new Permission(); // Instancia o objeto para verificação de permissão

    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();

    // Montagem das opções dos combobox
    // Exércitos
    $itens = $db_igot->getExercitos(); // Obtenção dos Exércitos para montagem das opções do ComboBox 
    $optExercitos = "";
    for ($i=0; $i<count($itens); $i++){
        $optExercitos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Guerreiros
    $itens = $db_igot->getGuerreiros(); // Obtenção dos Guerreiros para montagem das opções do ComboBox 
    $optGuerreiros = "";
    for ($i=0; $i<count($itens); $i++){
        $optGuerreiros .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Reinos
    $itens = $db_igot->getReinos(); // Obtenção dos Reinos para montagem das opções do ComboBox 
    $optReinos = "";
    for ($i=0; $i<count($itens); $i++){
        $optReinos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Castelos
    $itens = $db_igot->getCastelos(); // Obtenção dos Castelos para montagem das opções do ComboBox 
    $optCastelos = "";
    for ($i=0; $i<count($itens); $i++){
        $optCastelos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Alianças
    $itens = $db_igot->getAliancas(); // Obtenção das Alianças para montagem das opções do ComboBox 
    $optAliancas = "";
    for ($i=0; $i<count($itens); $i++){
        $optAliancas .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Torres
    $itens = $db_igot->getTorres(); // Obtenção das Torres para montagem das opções do ComboBox 
    $optTorres = "";
    for ($i=0; $i<count($itens); $i++){
        $optTorres .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Andares
    $itens = $db_igot->getAndares(); // Obtenção dos Andares para montagem das opções do ComboBox 
    $optAndares = "";
    for ($i=0; $i<count($itens); $i++){
        $optAndares .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Status de Aprovação
    $itens = $db_igot->getAprovacaoStatus(); // Obtenção dos Status de Aprovação para montagem das opções do ComboBox
    $optStatusAprovacao = "";
    for ($i=0; $i<count($itens); $i++){
        $optStatusAprovacao .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
?>

<div class="tab-pane" id="Habilidades">
    <div>
        <img width="100px" align="left" height="auto" alt="Torre" src="/igot/img/torre-botao-1.png">
        <br><h4><p class="titulo">AS HABILIDADES DOS GUERREIROS NAS TORRES</p></h4>
        <p class="conteudo"> 
            Os Guerreiros são medidos por suas habilidades e recompensados com medalhas, de acordo com a posição que ele ocupa nos andares de suas torres de competência.
        </p>
    </div>
    
    <hr class='featurette-divider'>

    <div class="row">
        <div class="box-body">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <!-- Filtro -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box-filters">
                            <div class="box-header">
                                <a href="#FiltroGuerreirosNasTorres" data-toggle="collapse" class="titulo">
                                    <span> <i class="fa fa-filter"></i> Filtro </span>
                                </a>
                            </div>
                            <div class="panel-collapse collapse in" id="FiltroGuerreirosNasTorres">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                        </div>
                                        <div class="col-md-3">
                                            <!-- Botões -->
                                            <div align="center" class="form-group" style="width:100%; margin-top:-40px;">
                                                <div class="col-md-6">
                                                    <button onclick="showGuerreirosNasTorres()" type="button" class="btn btn-sm btn-default"><span class="fa fa-filter"></span> Exibir</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button onclick="NewGuerreirosNasTorres()" type="button" class="btn btn-sm btn-default btnNew" id="btnNew"><span class="glyphicon glyphicon-plus"></span> Novo</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Exército</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_exercito" name="guerreirosnastorres_exercito" style="width: 100%;">
                                                    <option value="<?php echo $_SESSION['igot']['Guerreiro']['idExercito'] ?>"></option>
                                                    <?php echo $optExercitos; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Guerreiro</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_guerreiro" name="guerreirosnastorres_guerreiro" style="width: 100%;">
                                                    <option value="<?php echo $_SESSION['igot']['Guerreiro']['id']; ?>"></option>
                                                    <?php echo $optGuerreiros; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Reino</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_reino" style="width: 100%;">
                                                    <?php echo $optReinos; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Castelo</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_castelo" style="width: 100%;">
                                                    <?php echo $optCastelos; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Aliança</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_alianca" style="width: 100%;">
                                                    <?php echo $optAliancas; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Torre</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_torre" style="width: 100%;">
                                                    <?php echo $optTorres; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Posição</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_posicao" style="width: 100%;">
                                                    <?php echo $optAndares; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Status de Aprovação</label>
                                                <select class="form-control select2" multiple="multiple" id="guerreirosnastorres_StatusAprovacao" style="width: 100%;">
                                                    <?php echo $optStatusAprovacao; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabela -->
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped dataTable" id="tblGuerreirosNasTorres">
                            <thead id="thGuerreirosNasTorres">
                                <tr class="tblTitleRow">
                                    <th class="sorting datagrid">#</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thGuerreirosNasTorres', 'showGuerreirosNasTorres')" col="Guerreiro">Guerreiro</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thGuerreirosNasTorres', 'showGuerreirosNasTorres')" col="Reino">Reino</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thGuerreirosNasTorres', 'showGuerreirosNasTorres')" col="Castelo">Castelo</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thGuerreirosNasTorres', 'showGuerreirosNasTorres')" col="Alianca">Aliança</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thGuerreirosNasTorres', 'showGuerreirosNasTorres')" col="Torre">Torre</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thGuerreirosNasTorres', 'showGuerreirosNasTorres')" col="Posicao">Posição</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thGuerreirosNasTorres', 'showGuerreirosNasTorres')" col="Medalhas"><span id="Medalhas" class="detail"></span> Medalhas</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="StatusAprovacao">Status<br>da Aprovação</th>
                                    <th class="datagrid">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="row_position" id="tbodyGuerreirosNasTorres">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Custom script -->
    <script>
        $(document).ready(function() {
            // Cascading Dropbox (Exercitos -> Guerreiros/Reinos)
            $('#guerreirosnastorres_exercito').change(function(){
                if($('#guerreirosnastorres_exercito').val() != ""){
                    $('#guerreirosnastorres_guerreiro').load('config/options.php?return=guerreiros&exercito='+$('#guerreirosnastorres_exercito').val());
                    $('#guerreirosnastorres_reino').load('config/options.php?return=reinos&exercito='+$('#guerreirosnastorres_exercito').val());
                } else {
                    $('#guerreirosnastorres_guerreiro').load('config/options.php?return=guerreiros');
                    $('#guerreirosnastorres_reino').load('config/options.php?return=reinos');
                }
            });
            
            // Cascading Dropbox (Reinos -> Castelos)
            $('#guerreirosnastorres_reino').change(function(){
                if($('#guerreirosnastorres_reino').val() != ""){
                    $('#guerreirosnastorres_castelo').load('config/options.php?return=castelos&reino='+$('#guerreirosnastorres_reino').val());
                } else {
                    $('#guerreirosnastorres_castelo').load('config/options.php?return=castelos');
                }
            });
            
            // Cascading Dropbox (Castelos/Alianças -> Torres)
            function loadTorres(){
                if(($('#guerreirosnastorres_castelo').val() != "") && ($('#guerreirosnastorres_alianca').val() != "")) { // Opções de Torres baseadas no(s) Castelo(s) e Aliança(s)
                    $('#guerreirosnastorres_torre').load('config/options.php?return=torres&castelo='+$('#guerreirosnastorres_castelo').val()+'&alianca='+$('#guerreirosnastorres_alianca').val());
                }else if(($('#guerreirosnastorres_castelo').val() != "") && ($('#guerreirosnastorres_alianca').val() == "")){ // Opções de Torres baseadas no(s) Castelo(s)
                    $('#guerreirosnastorres_torre').load('config/options.php?return=torres&castelo='+$('#guerreirosnastorres_castelo').val());
                }else if(($('#guerreirosnastorres_castelo').val() == "") && ($('#guerreirosnastorres_alianca').val() != "")){ // Opções de Torres baseadas na(s) Aliança(s)
                    $('#guerreirosnastorres_torre').load('config/options.php?return=torres&alianca='+$('#guerreirosnastorres_alianca').val());
                }else{
                    //$('#guerreirosnastorres_torre').find('option').remove(); // Limpa as opções de Torres
                    $('#guerreirosnastorres_torre').load('config/options.php?return=torres');
                }
            }
            $('#guerreirosnastorres_castelo').change(function(){
                loadTorres(); // Atualiza as opções de Torres
            });
            $('#guerreirosnastorres_alianca').change(function(){
                loadTorres(); // Atualiza as oções de Torres
            });
        });

        // Carrega os Guerreiro nas Torres com base nos filtros selecionados
        function showGuerreirosNasTorres(sort="Guerreiro, Reino, Castelo, Torre"){
            var filtro = ""; // Variável parâmetros, filtros a serem aplicados na Query
            // Verifica os filtros selecionados
            if($('#guerreirosnastorres_exercito').val() != ""){
                filtro += '&exercito='+$('#guerreirosnastorres_exercito').val();
            }
            if($('#guerreirosnastorres_guerreiro').val() != ""){
                filtro += '&guerreiro='+$('#guerreirosnastorres_guerreiro').val();
            }
            if($('#guerreirosnastorres_reino').val() != ""){
                filtro += '&reino='+$('#guerreirosnastorres_reino').val();
            }
            if($('#guerreirosnastorres_castelo').val() != ""){
                filtro += '&castelo='+$('#guerreirosnastorres_castelo').val();
            }
            if($('#guerreirosnastorres_alianca').val() != ""){
                filtro += '&alianca='+$('#guerreirosnastorres_alianca').val();
            }
            if($('#guerreirosnastorres_torre').val() != ""){
                filtro += '&torre='+$('#guerreirosnastorres_torre').val();
            }
            if($('#guerreirosnastorres_posicao').val() != ""){
                filtro += '&posicao='+$('#guerreirosnastorres_posicao').val();
            }
            if($('#guerreirosnastorres_StatusAprovacao').val() != ""){
                filtro += '&StatusAprovacao='+$('#guerreirosnastorres_StatusAprovacao').val();
            }
            
            // Carrega os resultados com base no Filtro
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                table = new XMLHttpRequest();
                medalhas = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                table = new ActiveXObject("Microsoft.XMLHTTP");
                medalhas = new ActiveXObject("Microsoft.XMLHTTP");
            }
            table.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tbodyGuerreirosNasTorres").innerHTML = this.responseText;
                }
            };
            table.open("GET", 'config/results.php?return=GuerreirosNasTorres&type=DataGrid&sort='+sort+filtro, true);
            table.send();

            // Carrega o Total de Medalhas com base na Query de resultados
            medalhas.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("Medalhas").innerHTML = this.responseText;
                }
            };
            medalhas.open("GET", 'config/results.php?return=GuerreirosNasTorres&type=DataGrid&Medalhas=Total&sort='+sort+filtro, true);
            medalhas.send();
            // Exibe o botão Novo se oculto
            $('#Habilidades').find($(".btnNew")).show();
        }
    </script>
    <!-- Script para edição dos itens no Datagrid -->
    <script>
        // Função do botão Novo - Adiciona nova linha à tabela
        function NewGuerreirosNasTorres(){
            var postURL = "'admin/ajax/updGuerreirosNasTorres.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
            var pagPart = $('#Habilidades'); // Restringe os objetos em uma parte específica da página
            pagPart.find($(".btnNew")).hide(); // Oculta o botão Novo localizado em uma parte específica da página
            // HTML da Nova Linha
            var newRow = 
                '<tr class="ui-sortable-handle" name="NewRecord">'
                    +'<td class="datagrid">'
                        +'<span>NOVO</span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Guerreiro"></span>'
                        +'<select class="form-control select2 editInput Guerreiro" name="idGuerreiro"></select>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewAlways Reino"></span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewAlways Castelo"></span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewAlways Alianca"></span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Torre"></span>'
                        +'<select class="form-control select2 editInput Torre" name="idTorre"><?php echo $optTorres ?></select>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Posicao"></span>'
                        +'<select class="form-control select2 editInput Posicao" name="idPosicao"><?php echo $optAndares ?></select>'
                    +'</td>'
                    +'<td align="center" class="datagrid">'
                        +'<span class="viewAlways Medalhas"></span>'
                    +'</td>'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewOnly StatusAprovacao"></span>'
                        +'<select class="editInput form-control select2 StatusAprovacao" name="idStatusAprovacao"><?php echo $optStatusAprovacao ?></select>'
                    +'</td>'
                    +'<td align="center" class="datagrid actions">'
                        +'<button onclick="EditGuerreirosNasTorres(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button>'
                        +'<button onclick="UpdateGuerreirosNasTorres(this.id)" id="btnSave" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>'
                        +'<button onclick="Delete(this.id, Habilidades)" id="btnDelete" type="button" class="btn btn-sm btn-default btnDelete" style="float:none; display:none;"><span class="glyphicon glyphicon-trash"></span></button>'
                        +'<button onclick="Remove(this.id, Habilidades, '+postURL+')" id="btnRemove" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>'
                        +'<button onclick="SaveGuerreirosNasTorres()" id="btnSaveNew" type="button" class="btn btn-sm btn-success btnSaveNew" style="float:none;"><span class="fa fa-save"></span></button>'
                        +'<button onclick="Cancel(this.id, Habilidades)" id="btnCancelNewRecord" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px;"><span class="fa fa-undo"></span></button>'
                    +'</td>'
                +'</tr>'
            ;
            // Adiciona a nova Linha no topo da Tabela
            $(newRow).prependTo($("#tbodyGuerreirosNasTorres"));
            // Identificação da nova linha na tabela
            var trObj = pagPart.find($("[name='NewRecord']")); // Variável para resumo na identificação da linha corrente
            // Adiciona opções ao(s) Combobox
            trObj.find('.editInput.Guerreiro').load('config/options.php?return=guerreiros&groups=true');
            // Formata os Combobox - Select2
            trObj.find('.select2').select2();
        }
        // Função do botão Salvar - Insere um novo registro
        function SaveGuerreirosNasTorres(){
            var pagPart = $('#Habilidades'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='NewRecord']")); // Variável para resumo na identificação da linha corrente
            var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
            $.ajax({
                url:'admin/ajax/updGuerreirosNasTorres.php',
                type:'POST',
                dataType: "json",
                data:'action=insert&'+inputData,
                success:function(response){
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewOnly.Guerreiro").text(response.data.Guerreiro);
                    trObj.find(".viewAlways.Reino").text(response.data.Reino);
                    trObj.find(".viewAlways.Castelo").text(response.data.Castelo);
                    trObj.find(".viewAlways.Alianca").text(response.data.Alianca);
                    trObj.find(".viewOnly.Torre").text(response.data.Torre);
                    trObj.find(".viewOnly.Posicao").text(response.data.Posicao);
                    trObj.find(".viewAlways.Medalhas").text(response.data.Medalhas);
                    trObj.find(".viewOnly.StatusAprovacao").text(response.data.StatusAprovacao);
                    trObj.find(".viewAlways.StatusAprovacao").text(response.data.StatusAprovacao);
                    // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                    trObj.find(".editInput").hide(); // Oculta os campos de Edição
                    trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                    trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                    trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                    trObj.find(".btnSaveNew").remove(); // Remove o botão Salvar (novo registro)
                    trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                    trObj.find(".btnEdit").show(); // Exibe o botão Editar
                    trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                    pagPart.find($(".btnNew")).show(); // Exibe o botão Novo
                    // Atualiza o ID da Linha e sesus objetos
                    trObj.attr("name", response.data.id); // Atualiza o Nome da TR
                    trObj.find(".btnEdit").attr("id", "btnEdit" + response.data.id); // Atualiza o ID do botão Editar
                    trObj.find(".btnSave").attr("id", "btnSave" + response.data.id); // Atualiza o ID do botão Savar
                    trObj.find(".btnCancel").attr("id", "btnCancel" + response.data.id); // Atualiza o ID do botão Cancelar
                    trObj.find(".btnDelete").attr("id", "btnDelete" + response.data.id); // Atualiza o ID do botão Deletar
                    trObj.find(".btnRemove").attr("id", "btnRemove" + response.data.id); // Atualiza o ID do botão Remover
                }
            });
        }

        // Função do botão Editar
        function EditGuerreirosNasTorres(btnID){
            var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            var pagPart = $('#Habilidades'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='"+ID+"']")); // Variável para resumo na identificação da linha corrente
            // Inverte a exibição dos campos de Visualização pelos de Edição e do botão Editar pelo Salvar
            trObj.find(".viewOnly").hide(); // Oculta os campos de visualização
            trObj.find(".editInput").show(); // Exibe os campos para Edição
            trObj.find(".btnEdit").hide(); // Oculta o botão Editar
            trObj.find(".btnDelete").hide(); // Oculta o botão Deletar
            trObj.find(".btnSave").show(); // Exibe o botão Salvar
            trObj.find(".btnCancel").show(); // Exibe o botão Cancelar
            // Adiciona opções às combobox
            trObj.find(".editInput.Guerreiro").append('<?php echo $optConselheiros; ?>');
            trObj.find(".editInput.Torre").append('<?php echo $optTorres; ?>');
            trObj.find(".editInput.Posicao").append('<?php echo $optAndares; ?>');
            // Formata os Combobox - Select2
            trObj.find('.select2').select2();
        }
        // Função do botão Salvar - Atualiza registro existente
        function UpdateGuerreirosNasTorres(btnID){
            var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            var pagPart = $('#Habilidades'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='"+ID+"']")); // Variável para resumo na identificação da linha corrente
            var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
            $.ajax({
                url:'admin/ajax/updGuerreirosNasTorres.php',
                type:'POST',
                dataType: "json",
                data:'action=update&id='+ID+'&'+inputData,
                success:function(response){
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewOnly.Guerreiro").text(response.data.Guerreiro);
                    trObj.find(".viewAlways.Reino").text(response.data.Reino);
                    trObj.find(".viewAlways.Castelo").text(response.data.Castelo);
                    trObj.find(".viewAlways.Alianca").text(response.data.Alianca);
                    trObj.find(".viewOnly.Torre").text(response.data.Torre);
                    trObj.find(".viewOnly.Posicao").text(response.data.Posicao);
                    trObj.find(".viewAlways.Medalhas").text(response.data.Medalhas);
                    trObj.find(".viewOnly.StatusAprovacao").text(response.data.StatusAprovacao);
                    trObj.find(".viewAlways.StatusAprovacao").text(response.data.StatusAprovacao);
                    // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                    trObj.find(".editInput").hide(); // Oculta os campos para Edição
                    trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                    trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                    trObj.find(".btnSave").hide(); // Oculta o botão  Salvar
                    trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                    trObj.find(".btnEdit").show(); // Exibe o botão Editar
                    trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                }
            });
        }
    </script>
</div>
