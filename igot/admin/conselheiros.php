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

    // Listagem dos Guerreiros
    $itens = $db_igot->getGuerreiros();
    // Motagem das opções do combobox para seleção do Conselheiro
    $optConselheiros = '';
    for ($i=0; $i<count($itens); $i++){
        $optConselheiros .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Listagem dos Reinos
    $itens = $db_igot->getReinos();
    // Motagem das opções do combobox para seleção do Reino
    $optReinos = '';
    for ($i=0; $i<count($itens); $i++){
        $optReinos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Listagem dos Conselheiros
    $itens = $db_igot->getConselheirosReinos();
    // Montagem das linhas da tabela
    $postURL = "'ajax/updConselheiros.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
    $tr_table = "";
    for ($i=0; $i<count($itens); $i++){
        $tr_table .= '
            <tr id="'.$itens[$i]['id'].'" name="'.$itens[$i]['id'].'">
                <td class="datagrid" align="center">'.$itens[$i]['id'].'</td>
                <td class="datagrid">
                    <span class="viewOnly Conselheiro">'.$itens[$i]['Conselheiro'].'</span>
                    <select class="editInput form-control select2 Conselheiro" name="idConselheiro" style="display: none;">
                        <option value='.$itens[$i]['idConselheiro'].' selected="selected">'.$itens[$i]['Conselheiro'].'</option>
                    </select>
                </td>
                <td class="datagrid">
                    <span class="viewOnly Reino">'.$itens[$i]['Reino'].'</span>
                    <select class="editInput form-control select2 Reino" name="idReino" style="display: none;">
                        <option value='.$itens[$i]['idReino'].' selected="selected">'.$itens[$i]['Reino'].'</option>
                    </select>
                </td>
                <td class="datagrid actions" align="center">
                    <button onClick="EditConselheiro(this.id)" id="btnEdit'.$itens[$i]['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button onClick="Update(this.id)" id="btnSave'.$itens[$i]['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
                    <button onClick="Delete(this.id)" id="btnDelete'.$itens[$i]['id'].'" type="button" class="btn btn-sm btn-default btnDelete" style="float:none;"><span class="glyphicon glyphicon-trash"></span></button>
                    <button onClick="Remove(this.id, tblDataGrid, '.$postURL.')" id="btnRemove'.$itens[$i]['id'].'" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>
                    <button onclick="Cancel(this.id)" id="btnCancel'.$itens[$i]['id'].'" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px; display:none;"><span class="fa fa-undo"></span></button>
                </td>
            </tr>
        ';
    }
?>

<html>
	<head>
        <?php include_once "../../frames/head.php"; ?>
        <!-- Select2 Script -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
        <!-- DataTable Script -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                // DataTable
                $('#tblDataGrid').DataTable({
                    "searching": true,
                    "order": [[1, "asc"]],
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
                                    <h3 class="box-title">Conselheiros</h3>
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
                                                            <th class="datagrid">Conselheiro</th>
                                                            <th class="datagrid">Reino</th>
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
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Conselheiro"></span>'
                        +'<select class="editInput form-control select2 Conselheiro" name="idConselheiro"><?php echo $optConselheiros ?></select>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Reino"></span>'
                        +'<select class="editInput form-control select2 Reino" name="idReino"><?php echo $optReinos ?></select>'
                    +'</td>'
                    +'<td class="datagrid actions" align="center">'
                        +'<button onClick="EditReino(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button>'
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
                        trObj.find(".viewOnly.Conselheiro").text(response.data.Conselheiro);
                        trObj.find(".viewOnly.Reino").text(response.data.Reino);
                        // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                        trObj.find(".editInput").hide(); // Oculta os campos de Edição
                        trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                        document.getElementById("btnSaveNew").remove(); // Remove o botão Salvar (novo registro)
                        //trObj.find(".btnSaveNew").hide(); // Oculta o botão Salvar (novo registro)
                        trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                        trObj.find(".btnEdit").show(); // Exibe o botão Editar
                        trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                        $("#btnNew").show(); // Exibe o botão Novo
                        // Atualiza o ID da Linha e sesus objetos
                        document.getElementById("NewRecord").setAttribute("name", response.data.id); // Atualiza o Nome da TR
                        document.getElementById("NewRecord").id = response.data.id; // Atualiza o ID da TR
                        document.getElementById("btnEdit").id = "btnEdit" + response.data.id; // Atualiza o ID do botão Editar
                        document.getElementById("btnSave").id = "btnSave" + response.data.id; // Atualiza o ID do botão Savar
                        document.getElementById("btnCancelNewRecord").id = "btnCancel" + response.data.idmenu; // Atualiza o ID do botão Cancelar
                        document.getElementById("btnDelete").id = "btnDelete" + response.data.id; // Atualiza o ID do botão Deletar
                        document.getElementById("btnRemove").id = "btnRemove" + response.data.id; // Atualiza o ID do botão Remover
                    }
                });
            }

            // Função do botão Editar
            function EditConselheiro(btnID){
                var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
                var trObj = $('tr#'+ID); // Variável para resumo na identificação da linha da tabela. Localiza a TR em toda à página
                // Adiciona opções às combobox
                trObj.find(".editInput.Conselheiro").append('<?php echo $optConselheiros; ?>');
                trObj.find(".editInput.Reino").append('<?php echo $optReinos; ?>');
                // Inverte a exibição dos campos de Visualização pelos de Edição e do botão Editar pelo Salvar
                Edit(btnID);
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
                        trObj.find(".viewOnly.Conselheiro").text(response.data.Conselheiro);
                        trObj.find(".viewOnly.Reino").text(response.data.Reino);
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