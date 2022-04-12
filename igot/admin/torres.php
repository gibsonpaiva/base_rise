<!DOCTYPE html>
<?php
    // Verifica se possui permissão necessária        
    include_once "../../config/permissions.php"; // Inclue o objeto referente a verificação de permissão
    $MemberOf = new Permission(); // Instancia o objeto para verificação de permissão
    // Verifica se é membro do grupo de Administradores do IGOT
    if(!$MemberOf->admin('igot')) { // Se não for membro do Grupo Admin
        header("Location:{$_SERVER['HTTP_REFERER']}");
        exit;
    }
    
    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
    $postURL = "'ajax/updTorres.php'"; // Página com as funções de manipulação da tabela Torres
    // Listagem dos Reinos
    $itens = $db_igot->getReinos();
    // Motagem das opções do combobox para seleção do Reino
    $optReinos = "";
    for ($i=0; $i<count($itens); $i++){
        $optReinos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Listagem dos Castelos
    $itens = $db_igot->getCastelos();
    // Motagem das opções do combobox para seleção do Castelo
    $optCastelos = "";
    for ($i=0; $i<count($itens); $i++){
        $optCastelos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Listagem das Alianças
    $itens = $db_igot->getAliancas();
    // Motagem das opções do combobox para seleção do Evento
    $optAliancas = "";
    for ($i=0; $i<count($itens); $i++){
        $optAliancas .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
?>

<html>
	<head>
        <?php include_once "../../frames/head.php"; ?>
        <!-- Select2 Script -->        
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script>
            $(document).ready(function() {
                // Select2
                $('#Filtro').find('.select2').select2();
            });
        </script>
	</head>

    <body class="hold-transition skin-red sidebar-mini fixed">
        <div class="wrapper">
            <!-- Cabeçalho -->
            <?php include_once "../../frames/header.php"; ?>

            <!-- Barra Lateral -->
            <?php include_once "../../frames/sidebar.php"; ?>

            <!-- Área de conteúdo da Página -->
            <div class="content-wrapper">
                <!-- Título da Página -->
                <section class="content-header">
                    <h1>Administração<small>IGOT</small></h1>
                    <!-- Bredcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
                        <li><a href="/igot">IGOT</a></li>
                        <li class="active">Administração</li>
                    </ol>
                </section>

                <!-- Conteúdo da Página -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <!-- Título da Caixa -->
                                <div class="box-header">
                                    <h3 class="box-title">Torres</h3>
                                </div>
                                <!-- Conteúdo da Caixa -->
                                <div class="box-body">
                                    <!-- Filtro -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="box-filters">
                                                <div class="box-header">
                                                    <a href="#Filtro" data-toggle="collapse" class="titulo">
                                                        <span> <i class="fa fa-filter"></i> Filtro</span>
                                                    </a>
                                                </div>
                                                <div class="panel-collapse collapse in" id="Filtro">
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Reinos</label>
                                                                    <select class="form-control select2" multiple="multiple" id="reinos" style="width: 100%;">
                                                                        <?php echo $optReinos; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Castelos</label>
                                                                    <select class="form-control select2" multiple="multiple" id="castelos" style="width: 100%;">
                                                                        <?php echo $optCastelos ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Alianças</label>
                                                                    <select class="form-control select2" multiple="multiple" id="aliancas" style="width: 100%;">
                                                                        <?php echo $optAliancas ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <!-- Botões -->
                                                                <div class="pull-left" style="margin-top:22px;">
                                                                    <button onclick="show()" type="button" class="btn btn-sm btn-default"><span class="fa fa-filter"></span> Exibir</button>
                                                                </div>
                                                                <div class="pull-right" style="margin-top:22px;">
                                                                    <button onclick="New()" type="button" class="btn btn-sm btn-default btnNew" id="btnNew"><span class="glyphicon glyphicon-plus"></span> Novo</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tabela -->
                                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-bordered table-hover table-striped dataTable" role="grid" id="tblDataGrid">
                                                    <thead id="thDataGrid">
                                                        <tr role="row" class="tblTitleRow">
                                                            <th class="sorting datagrid" onclick="Sort(this, 'thDataGrid', 'show')" col="idTorre">ID</th>
                                                            <th class="sorting datagrid" onclick="Sort(this, 'thDataGrid', 'show')" col="Reino">Reino</th>
                                                            <th class="sorting datagrid" onclick="Sort(this, 'thDataGrid', 'show')" col="Castelo">Castelo</th>
                                                            <th class="sorting datagrid" onclick="Sort(this, 'thDataGrid', 'show')" col="Alianca">Alianca</th>
                                                            <th class="sorting datagrid" onclick="Sort(this, 'thDataGrid', 'show')" col="Torre">Torre</th>
                                                            <th class="datagrid actions">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="row_position" id="body_DataGrid">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Rodapé -->
            <?php include_once "../../frames/footer.php"; ?>

            <!-- Painel de Controle -->
            <?php include_once "../frames/controlpanel.php"; ?>
        </div>

        <!-- Script para aplicação de Filtro -->
        <script>
            $(document).ready(function() {
                // Cascading Dropbox (Reinos -> Castelos)
                $('#reinos').change(function(){
                    if($('#reinos').val() != ""){
                        $('#castelos').load('../config/options.php?return=castelos&reino='+$('#reinos').val());
                    } else {
                        $('#castelos').load('../config/options.php?return=castelos');
                    }
                });
            });

            // Carrega os Eventos Registrados com base nos filtros selecionados
            function show(sort="Reino,Castelo,Torre"){
                var filtro = ""; // Variável parâmetros, filtros a serem aplicados na Query
                // Verifica os filtros selecionados
                if($('#reinos').val() != ""){
                    filtro += '&reino='+$('#reinos').val();
                }
                if($('#castelos').val() != ""){
                    filtro += '&castelo='+$('#castelos').val();
                }
                if($('#aliancas').val() != ""){
                    filtro += '&alianca='+$('#aliancas').val();
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
                        document.getElementById("body_DataGrid").innerHTML = this.responseText;
                    }
                };
                table.open("GET", '../config/results.php?return=Torres&type=DataGrid&sort='+sort+filtro, true);
                table.send();

                // Exibe o botão Novo se oculto
                $('#Filtro').find($(".btnNew")).show();
            }
        </script>

        <!-- Script para edição dos itens no Datagrid -->
        <script src="/stylesheet/others/datagrid.js"></script>
        <script>
            // Função do botão Novo - Adiciona nova linha à tabela
            function New(){
                var postURL = "<?php echo $postURL; ?>"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                $("#btnNew").hide(); // Oculta o botão Novo
                // HTML da Nova Linha
                var newRow = 
                '<tr class="ui-sortable-handle" id="NewRecord">'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewAlways id"></span>'
                    +'</td>'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewOnly Reino"></span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Castelo"></span>'
                        +'<select class="editInput form-control select2 Castelo" name="idCastelo"><?php echo $optCastelos ?></select>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Alianca"></span>'
                        +'<select class="editInput form-control select2 Alianca" name="idAlianca"><?php echo $optAliancas ?></select>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Torre"></span>'
                        +'<input class="editInput form-control input-sm Torre" type="text" name="Torre">'
                    +'</td>'
                    +'<td class="datagrid" align="center" style="width:115px;">'
                        +'<button onClick="EditTorre(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button>'
                        +'<button onClick="Update(this.id)" id="btnSave" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>'
                        +'<button onClick="Delete(this.id)" id="btnDelete" type="button" class="btn btn-sm btn-default btnDelete" style="float:none; display:none;"><span class="glyphicon glyphicon-trash"></span></button>'
                        +'<button onClick="Remove(this.id, tblDataGrid, '+postURL+')" id="btnRemove" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>'
                        +'<button onclick="SaveNew()" id="btnSaveNew" type="button" class="btn btn-sm btn-success btnSaveNew" style="float:none;"><span class="fa fa-save"></span></button>'
                        +'<button onclick="Cancel(this.id)" id="btnCancelNewRecord" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px;"><span class="fa fa-undo"></span></button>'
                    +'</td>'
                +'</tr>';
                // Adiciona a nova Linha no topo da Tabela
                $(newRow).prependTo("table > tbody");
                // Formata os Combobox - Select2
                $('tr#NewRecord').find('.select2').select2();
            }
            // Função do botão Salvar - Insere um novo registro
            function SaveNew(){
                var trObj = $('tr#NewRecord'); // Variável para resumo na identificação da linha corrente
                var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
                $.ajax({
                    url: <?php echo $postURL; ?>,
                    type:'POST',
                    dataType: "json",
                    data:'action=insert&'+inputData,
                    success:function(response){
                        // Atualiza o texto dos campos de visualização
                        trObj.find(".viewAlways.id").text(response.data.id);
                        trObj.find(".viewOnly.Reino").text(response.data.Reino);
                        trObj.find(".viewOnly.Castelo").text(response.data.Castelo);
                        trObj.find(".viewOnly.Alianca").text(response.data.Alianca);
                        trObj.find(".viewOnly.Torre").text(response.data.Torre);
                        // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                        trObj.find(".editInput").hide(); // Oculta os campos de Edição
                        trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                        trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                        document.getElementById("btnSaveNew").remove(); // Remove o botão Salvar (novo registro)
                        trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                        trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                        trObj.find(".btnEdit").show(); // Exibe o botão Editar
                        trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                        $("#btnNew").show(); // Exibe o botão Novo
                        // Atualiza o ID da Linha e sesus objetos
                        document.getElementById("NewRecord").setAttribute("name", response.data.id); // Atualiza o Nome da TR
                        document.getElementById("NewRecord").id = response.data.id; // Atualiza o ID da TR
                        document.getElementById("btnEdit").id = "btnEdit" + response.data.id; // Atualiza o ID do botão Editar
                        document.getElementById("btnSave").id = "btnSave" + response.data.id; // Atualiza o ID do botão Savar
                        document.getElementById("btnDelete").id = "btnDelete" + response.data.id; // Atualiza o ID do botão Deletar
                        document.getElementById("btnRemove").id = "btnRemove" + response.data.id; // Atualiza o ID do botão Remover
                    }
                });
            }

            // Função do botão Editar
            function EditTorre(btnID){
                var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
                var trObj = $('tr#'+ID); // Variável para resumo na identificação da linha da tabela. Localiza a TR em toda à página
                // Adiciona opções às combobox
                trObj.find(".editInput.Castelo").append('<?php echo $optCastelos; ?>'); //trObj.find(".editInput.Castelo").load('../config/options.php?return=castelos&reino='+trObj.find(".editInput.Reino").val());
                trObj.find(".editInput.Alianca").append('<?php echo $optAliancas; ?>');                
                // Inverte a exibição dos campos de Visualização pelos de Edição e do botão Editar pelo Salvar
                Edit(btnID);
            }
            // Função do botão Salvar - Atualiza registro existente
            function Update(btnID){
                var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
                var trObj = $('tr#'+ID); // Variável para resumo na identificação da linha da tabela
                var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
                $.ajax({
                    url: <?php echo $postURL; ?>,
                    type:'POST',
                    dataType: "json",
                    data:'action=update&id='+ID+'&'+inputData,
                    success:function(response){
                        // Atualiza o texto dos campos de visualização
                        trObj.find(".viewOnly.Reino").text(response.data.Reino);
                        trObj.find(".viewOnly.Castelo").text(response.data.Castelo);
                        trObj.find(".viewOnly.Torre").text(response.data.Torre);
                        // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                        trObj.find(".editInput").hide(); // Oculta os campos para Edição
                        trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                        trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                        trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                        trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                        trObj.find(".btnEdit").show(); // Exibe o botão Editar
                        trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                    }
                });
            }
        </script>
    </body>
</html>