<!DOCTYPE html>
<?php
    // Verifica se possui permissão administrativa
    include_once "../config/permissions.php"; // Inclue o objeto referente a verificação de permissão
    $MemberOf = new Permission(); // Instancia o objeto para verificação de permissão
    // Verifica se é membro do grupo de Administradores do RISE
    if(!$MemberOf->admin('rise')) { // Se não for membro do Grupo Admin
        header("Location:{$_SERVER['HTTP_REFERER']}");
        exit;
    }

    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "../config/db.php";
    // Instancia os objetos referente a base de dados
    $db_rise = new RISE();
    // Listagem dos itens de Menu Principal
    $itens = $db_rise->getMenu(null);

    // Motagem das opções do combobox para seleção do Menu Pai
    $options = '<option value="0"></option>';
    for ($i=0; $i<count($itens); $i++){
        $options .= '<option value="'.$itens[$i]['idMenu'].'">'.$itens[$i]['Titulo'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Montagem das linhas da tabela
    $postURL = "'ajax/updMenu.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
    $tr_table = "";
    for ($i=0; $i<count($itens); $i++){
        $tr_table .= '
            <tr id="'.$itens[$i]['idMenu'].'" name="'.$itens[$i]['idMenu'].'">
                <td class="datagrid" align="center">'.$itens[$i]['idMenu'].'</td>
                <td class="datagrid">
                    <span class="viewOnly icone">'.$itens[$i]['Icone'].'</span>
                    <input class="editInput form-control input-sm" type="text" name="icone" value="'.$itens[$i]['Icone'].'" style="display:none;">
                </td>
                <td class="datagrid">
                    <span class="viewOnly titulo">'.$itens[$i]['Titulo'].'</span>
                    <input class="editInput form-control input-sm" type="text" name="titulo" value="'.$itens[$i]['Titulo'].'" style="display:none;">
                </td>
                <td class="datagrid">
                    <span class="viewOnly link">'.$itens[$i]['Link'].'</span>
                    <input class="editInput form-control input-sm" type="text" name="link" value="'.$itens[$i]['Link'].'" style="display:none;">
                </td>
                <td class="datagrid">
                    <span class="viewOnly menupai">'.$itens[$i]['MenuPai'].'</span>
                    <select class="editInput form-control select2" name="menupai" style="display:none;">
                        <option value='.$itens[$i]['idMenuPai'].' selected="selected">'.$itens[$i]['MenuPai'].'</option>
                        '.$options.'
                    </select>
                </td>
                <td class="datagrid actions" align="center">
                    <button onClick="Edit(this.id)" id="btnEdit'.$itens[$i]['idMenu'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button onClick="Update(this.id)" id="btnSave'.$itens[$i]['idMenu'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                    <button onClick="Delete(this.id)" id="btnDelete'.$itens[$i]['idMenu'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                    <button onClick="Remove(this.id, tblDataGrid, '.$postURL.')" id="btnRemove'.$itens[$i]['idMenu'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                    <button onclick="Cancel(this.id)" id="btnCancel'.$itens[$i]['idMenu'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>
                </td>
            </tr>
        ';
    }
?>

<html>
	<head>
        <?php include_once "../frames/head.php"; ?>
        <!-- jQuery 1.12 Script -->
        <script src="../stylesheet/jquery/jquery-ui-1.12.1.min.js"></script>
        <!-- Select2 Script -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
	</head>

    <body class="hold-transition skin-red sidebar-mini fixed">
        <div class="wrapper">
            <!-- Cabeçalho -->
            <?php include_once "../frames/header.php"; ?>

            <!-- Barra Lateral -->
            <?php include_once "../frames/sidebar.php"; ?>

            <!-- Área de conteúdo da Página -->
            <div class="content-wrapper">
                <!-- Título da Página -->
                <section class="content-header">
                    <h1>Administração<small>RISE</small></h1>
                    <!-- Bredcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
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
                                    <h3 class="box-title">Menu de Navegação</h3>
                                </div>
                                <!-- Conteúdo da Caixa -->
                                <div class="box-body">
                                    <!-- Botão Novo -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button onClick="New()" type="button" id="btnNew" class="btn btn-sm btn-default pull-right" style="float:none;"><span class="glyphicon glyphicon-plus"> Novo</span></button>
                                        </div>
                                    </div>
                                    <!-- Tabela -->
                                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-bordered table-hover table-striped dataTable" role="grid" id="tblDataGrid">
                                                    <thead>
                                                        <tr role="row" class="tblTitleRow">
                                                            <th class="datagrid id">ID</th>
                                                            <th class="datagrid">Icone</th>
                                                            <th class="datagrid">Título</th>
                                                            <th class="datagrid">Link</th>
                                                            <th class="datagrid">Submenu de...</th>
                                                            <th class="datagrid actions">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="row_position" id="body_DataGrid">
                                                        <?php echo $tr_table; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Rodapé da Tabela -->
                                    <div class="row">
                                        <!-- Status -->
                                        <div class="col-sm-5">
                                            <div class="dataTable_info" role="status">Arraste os itens do menu para reordernar o mesmo</div>
                                        </div>
                                        <!-- Paginação -->
                                        <div class="col-sm-7">
                                            <div class="dataTable_paginate paging_simple_numbers"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Rodapé -->
            <?php include_once "../frames/footer.php"; ?>

            <!-- Painel de Controle -->
            <?php include_once "../frames/controlpanel.php"; ?>
        </div>

        <!-- Script para reordenação através do Drag and Drop -->
        <script type="text/javascript">
            // Reordenação através do Drag and Drop
            $(".row_position").sortable({
                delay: 150,
                stop: function() {
                    var selectedData = new Array();
                    $('.row_position>tr').each(function() {
                        selectedData.push($(this).attr("id"));
                    });
                    updateOrder(selectedData);
                }
            });
            // Função para atualização da tabela (DB)
            function updateOrder(data) {
                $.ajax({
                    url:'ajax/updMenu.php',
                    type:'POST',
                    data:{position:data},
                    success:function(){
                        alert("Menu reordenado com sucesso");
                    }                
                })
            }
        </script>

        <!-- Script para edição dos itens no Datagrid -->
        <script src="/stylesheet/others/datagrid.js"></script>
        <script>
            // Função do botão Novo - Adiciona nova linha à tabela
            function New(){
                var postURL = "<?php echo $postURL; ?>"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                $("#btnNew").hide(); // Oculta o botão Novo
                // Adiciona uma nova Linha à Tabela
                $('#tblDataGrid').append(
                    '<tr class="ui-sortable-handle" id="NewRecord" name="NewRecord">'
                    +'<td class="datagrid" align="center">'
                            +'<span class="viewOnly idmenu" style="display:none;"></span>'
                        +'</td>'
                        +'<td class="datagrid">'
                            +'<span class="viewOnly icone"></span>'
                            +'<input class="editInput form-control input-sm" type="text" name="icone" placeholder="fa-circle-o">'
                        +'</td>'
                        +'<td class="datagrid">'
                            +'<span class="viewOnly titulo"></span>'
                            +'<input class="editInput form-control input-sm" type="text" name="titulo">'
                        +'</td>'
                        +'<td class="datagrid">'
                            +'<span class="viewOnly link"></span>'
                            +'<input class="editInput form-control input-sm" type="text" name="link" placeholder="/site/pagina.php">'
                        +'</td>'
                        +'<td class="datagrid">'
                            +'<span class="viewOnly menupai"></span>'
                            +'<select class="editInput form-control select2" name="menupai"><?php echo $options ?></select>'
                        +'</td>'
                        +'<td class="datagrid actions" align="center">'
                            +'<button onClick="Edit(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button>'
                            +'<button onClick="Update(this.id)" id="btnSave" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>'
                            +'<button onClick="Delete(this.id)" id="btnDelete" type="button" class="btn btn-sm btn-default btnDelete" style="float:none; display:none;"><span class="glyphicon glyphicon-trash"></span></button>'
                            +'<button onClick="Remove(this.id, tblDataGrid, '+postURL+')" id="btnRemove" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>'
                            +'<button onclick="SaveNew()" id="btnSaveNew" type="button" class="btn btn-sm btn-success btnSaveNew" style="float:none;"><span class="fa fa-save"></span></button>'
                            +'<button onclick="Cancel(this.id)" id="btnCancelNewRecord" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px;"><span class="fa fa-undo"></span></button>'
                        +'</td>'
                    +'</tr>'
                );
                // Formata o(s) Combobox do tipo Select2 
                $('tr#NewRecord').find('.select2').select2();
            }
            // Função do botão Salvar - Insere um novo registro
            function SaveNew(){
                var trObj = $('tr#NewRecord'); // Variável para resumo na identificação da linha corrente
                var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
                $.ajax({
                    url:'ajax/updMenu.php',
                    type:'POST',
                    dataType: "json",
                    data:'action=insert&'+inputData,
                    success:function(response){
                        // Atualiza o texto dos campos de visualização
                        trObj.find(".viewOnly.idmenu").text(response.data.idmenu);
                        trObj.find(".viewOnly.icone").text(response.data.icone);
                        trObj.find(".viewOnly.titulo").text(response.data.titulo);
                        trObj.find(".viewOnly.link").text(response.data.link);
                        trObj.find(".viewOnly.menupai").text(response.data.menupai);
                        // Atualiza o texto dos campos de Edição
                        trObj.find(".editInput.icone").text(response.data.icone);
                        trObj.find(".editInput.titulo").text(response.data.titulo);
                        trObj.find(".editInput.link").text(response.data.link);
                        trObj.find(".editInput.menupai").text(response.data.menupai);
                        // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                        trObj.find(".editInput").hide(); // Oculta os campos de Edição
                        trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                        document.getElementById("btnSaveNew").remove(); // Remove o botão Salvar (novo registro)
                        trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                        trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                        trObj.find(".btnEdit").show(); // Exibe o botão Editar
                        trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                        $("#btnNew").show(); // Exibe o botão Novo
                        // Atualiza o ID da Linha e sesus objetos
                        document.getElementById("NewRecord").setAttribute("name", response.data.idmenu); // Atualiza o Nome da TR
                        document.getElementById("NewRecord").id = response.data.idmenu; // Atualiza o ID da TR
                        document.getElementById("btnEdit").id = "btnEdit" + response.data.idmenu; // Atualiza o ID do botão Editar
                        document.getElementById("btnSave").id = "btnSave" + response.data.idmenu; // Atualiza o ID do botão Savar
                        document.getElementById("btnCancelNewRecord").id = "btnCancel" + response.data.idmenu; // Atualiza o ID do botão Cancelar
                        document.getElementById("btnDelete").id = "btnDelete" + response.data.idmenu; // Atualiza o ID do botão Deletar
                        document.getElementById("btnRemove").id = "btnRemove" + response.data.idmenu; // Atualiza o ID do botão Remover
                    }
                });
            }

            // Função do botão Salvar - Atualiza registro existente
            function Update(btnID){
                var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
                var trObj = $('tr#'+ID); // Variável para resumo na identificação da linha da tabela
                var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
                $.ajax({
                    url:'ajax/updMenu.php',
                    type:'POST',
                    dataType: "json",
                    data:'action=update&id='+ID+'&'+inputData,
                    success:function(response){
                        // Atualiza o texto dos campos de visualização
                        trObj.find(".viewOnly.icone").text(response.data.icone);
                        trObj.find(".viewOnly.titulo").text(response.data.titulo);
                        trObj.find(".viewOnly.link").text(response.data.link);
                        trObj.find(".viewOnly.menupai").text(response.data.menupai);
                        // Atualiza o texto dos campos de Edição
                        trObj.find(".editInput.icone").text(response.data.icone);
                        trObj.find(".editInput.titulo").text(response.data.titulo);
                        trObj.find(".editInput.link").text(response.data.link);
                        trObj.find(".editInput.menupai").text(response.data.menupai);
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