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

    // Listagem dos Usuários
    $itens = $db_rise->getUsers();
    // Montagem das linhas da tabela
    $postURL = "'ajax/updUser.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
    $tr_table = "";
    for ($i=0; $i<count($itens); $i++){
        $tr_table .= '
            <tr id="'.$itens[$i]['idUsuario'].'" name="'.$itens[$i]['idUsuario'].'">
                <td class="datagrid" align="center">'.$itens[$i]['idUsuario'].'</td>
                <td class="datagrid">
                    <span class="viewOnly username">'.$itens[$i]['NomeUsuario'].'</span>
                    <input class="editInput form-control input-sm" type="text" name="username" value="'.$itens[$i]['NomeUsuario'].'" style="display:none;">
                </td>
                <td class="datagrid">
                    <span class="viewOnly displayname">'.$itens[$i]['NomeExibicao'].'</span>
                    <input class="editInput form-control input-sm" type="text" name="displayname" value="'.$itens[$i]['NomeExibicao'].'" style="display:none;">
                </td>
                <td class="datagrid" align="center">
                    <span class="viewOnly department">'.$itens[$i]['Departamento'].'</span>
                    <input class="editInput form-control input-sm" type="text" name="department" value="'.$itens[$i]['Departamento'].'" style="display:none;">
                </td>
                <td class="datagrid actions" align="center">
                    <button onClick="Edit(this.id)" id="btnEdit'.$itens[$i]['idUsuario'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button onClick="Update(this.id)" id="btnSave'.$itens[$i]['idUsuario'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                    <button onClick="Delete(this.id)" id="btnDelete'.$itens[$i]['idUsuario'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                    <button onClick="Remove(this.id, tblDataGrid, '.$postURL.')" id="btnRemove'.$itens[$i]['idUsuario'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                    <button onclick="Cancel(this.id)" id="btnCancel'.$itens[$i]['idUsuario'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>
                </td>
            </tr>
        ';
    }
?>

<html>
	<head>
        <?php include_once "../frames/head.php"; ?>
        <!-- DataTable Script -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                // DataTable
                $('#tblDataGrid').DataTable({
                    "searching": true,
                    "order": [[2, "asc"]],
                    "iDisplayLength": 10,
                    // Tradução
                    "language": {
                        "sEmptyTable": "Nenhum registro encontrado",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "Processando...",
                        "sZeroRecords": "Nenhum registro encontrado",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        }                
                    }
                });
            });
        </script>
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
                                    <h3 class="box-title">Usuários do RISE</h3>
                                </div>
                                <!-- Conteúdo da Caixa -->
                                <div class="box-body">
                                    <!-- Botão Novo -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button onClick="New()" type="button" id="btnNew" class="btn btn-sm btn-default pull-right" style="float:none; margin-bottom:10px;"><span class="glyphicon glyphicon-plus"> Novo</span></button>
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
                                                            <th class="datagrid">Username</th>
                                                            <th class="datagrid">Nome</th>
                                                            <th class="datagrid">Departamento</th>
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
                                            <div class="dataTable_info" role="status"></div>
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

        <!-- Script para edição dos itens no Datagrid -->
        <script src="/stylesheet/others/datagrid.js"></script>
        <script>
            // Função do botão Novo - Adiciona nova linha à tabela
            function New(){
                var postURL = "<?php echo $postURL; ?>"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                $("#btnNew").hide(); // Oculta o botão Novo
                // HTML da Nova Linha
                var newRow = 
                '<tr class="ui-sortable-handle" id="NewRecord" name="NewRecord">'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewAlways id"></span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly username"></span>'
                        +'<input class="editInput form-control input-sm" type="text" name="username">'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly displayname"></span>'
                        +'<input class="editInput form-control input-sm" type="text" name="displayname">'
                    +'</td>'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewOnly department"></span>'
                        +'<input class="editInput form-control input-sm" type="text" name="department">'
                    +'</td>'
                    +'<td class="datagrid actions" align="center">'
                        +'<button onClick="Edit(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button>'
                        +'<button onClick="Update(this.id)" id="btnSave" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>'
                        +'<button onClick="Delete(this.id)" id="btnDelete" type="button" class="btn btn-sm btn-default btnDelete" style="float:none; display:none;"><span class="glyphicon glyphicon-trash"></span></button>'
                        +'<button onClick="Remove(this.id, tblDataGrid, '+postURL+')" id="btnRemove" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>'
                        +'<button onclick="SaveNew()" id="btnSaveNew" type="button" class="btn btn-sm btn-success btnSaveNew" style="float:none;"><span class="fa fa-save"></span></button>'
                        +'<button onclick="Cancel(this.id)" id="btnCancelNewRecord" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px;"><span class="fa fa-undo"></span></button>'
                    +'</td>'
                +'</tr>';
                // Adiciona a nova Linha no topo da Tabela
                $(newRow).prependTo("table > tbody");
            }
            // Função do botão Salvar - Insere um novo registro
            function SaveNew(){
                var postURL = <?php echo $postURL; ?>; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                var trObj = $('tr#NewRecord'); // Variável para resumo na identificação da linha corrente
                var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
                $.ajax({
                    url:postURL,
                    type:'POST',
                    dataType: "json",
                    data:'action=insert&'+inputData,
                    success:function(response){
                        // Atualiza o texto dos campos de visualização
                        trObj.find(".viewAlways.id").text(response.data.id);
                        trObj.find(".viewOnly.username").text(response.data.username);
                        trObj.find(".viewOnly.displayname").text(response.data.displayname);
                        trObj.find(".viewOnly.department").text(response.data.department);
                        // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                        trObj.find(".editInput").hide(); // Oculta os campos de Edição
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

            // Função do botão Salvar - Atualiza registro existente
            function Update(btnID){
                var postURL = <?php echo $postURL; ?>; // Endereço da página com as funções SQL desta Base de Dados / Tabela
                var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
                var trObj = $('tr#'+ID); // Variável para resumo na identificação da linha da tabela
                var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
                $.ajax({
                    url:postURL,
                    type:'POST',
                    dataType: "json",
                    data:'action=update&id='+ID+'&'+inputData,
                    success:function(response){
                        // Atualiza o texto dos campos de visualização
                        trObj.find(".viewOnly.username").text(response.data.username);
                        trObj.find(".viewOnly.displayname").text(response.data.displayname);
                        trObj.find(".viewOnly.department").text(response.data.department);
                        // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                        trObj.find(".editInput").hide(); // Oculta os campos para Edição
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