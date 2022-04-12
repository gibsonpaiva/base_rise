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

    // Listagem dos Exércitos
    $itens = $db_igot->getConformidadesTipos();
    // Montagem das linhas da tabela
    $postURL = "'ajax/updConformidadesTipos.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
    $tr_table = "";
    for ($i=0; $i<count($itens); $i++){
        $tr_table .= '
            <tr id="'.$itens[$i]['id'].'" name="'.$itens[$i]['id'].'">
                <td class="datagrid" align="center">'.$itens[$i]['id'].'</td>
                <td class="datagrid">
                    <span class="viewOnly Nome">'.$itens[$i]['Nome'].'</span>
                    <input class="editInput form-control input-sm" type="text" name="Nome" value="'.$itens[$i]['Nome'].'" style="display:none;">
                </td>
                <td class="datagrid actions" align="center">
                    <button onClick="Edit(this.id)" id="btnEdit'.$itens[$i]['id'].'" type="button" class="btn btn-sm btn-default btnEdit" style="float:none;"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button onClick="Save(this.id, tblDataGrid, '.$postURL.')" id="btnSave'.$itens[$i]['id'].'" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>
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
                                    <h3 class="box-title">Tipos de Conformidade</h3>
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
                                                            <th class="datagrid">Nome</th>
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
                '<tr class="ui-sortable-handle" id="NewRecord" name="NewRecord">'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewAlways id"></span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Nome"></span>'
                        +'<input class="editInput form-control input-sm" type="text" name="Nome">'
                    +'</td>'
                    +'<td class="datagrid actions" align="center" style="width:115px;">'
                        +'<button onClick="Edit(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button>'
                        +'<button onClick="Save(this.id, tblDataGrid, '+postURL+')" id="btnSave" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>'
                        +'<button onClick="Delete(this.id)" id="btnDelete" type="button" class="btn btn-sm btn-default btnDelete" style="float:none; display:none;"><span class="glyphicon glyphicon-trash"></span></button>'
                        +'<button onClick="Remove(this.id, tblDataGrid, '+postURL+')" id="btnRemove" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>'
                        +'<button onclick="Save(this.id, tblDataGrid, '+postURL+')" id="btnSaveNewRecord" type="button" class="btn btn-sm btn-success btnSaveNew" style="float:none;"><span class="fa fa-save"></span></button>'
                        +'<button onclick="Cancel(this.id)" id="btnCancelNewRecord" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px;"><span class="fa fa-undo"></span></button>'
                    +'</td>'
                +'</tr>';
                // Adiciona a nova Linha no topo da Tabela
                $(newRow).prependTo("table > tbody");
            }
        </script>
    </body>
</html>