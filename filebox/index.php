<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }

     
  
    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
    $db_filebox = new FileBox();

    // Montagem das opções dos combobox
    $postURL = "'../igot/admin/ajax/updEventos.php'";
    // Categoria de Eventos
    $itens = $db_igot->getEventosCategorias(); // Obtenção dos Tipos de Eventos para montagem das opções do ComboBox
    $optEventosCategorias = "";
    for ($i=0; $i<count($itens); $i++){
        $optEventosCategorias .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
   
    // Tipos de Eventos
    $itens = $db_igot->getEventosTipos(); // Obtenção dos Eventos para montagem das opções do ComboBox
    $optEventosTipos = "";
    for ($i=0; $i<count($itens); $i++){
        $optEventosTipos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Alianças
    $itens = $db_igot->getAliancas(); // Obtenção das Alianças para montagem das opções do ComboBox
    $optAliancas = "";
    for ($i=0; $i<count($itens); $i++){
        $optAliancas .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Guerreiros
    $itens = $db_igot->getGuerreiros(); // Obtenção dos Guerreiros para montagem das opções do ComboBox
    $optGuerreiros = "";
    for ($i=0; $i<count($itens); $i++){
        $optGuerreiros .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Torres
    $itens = $db_igot->getTorres(); // Obtenção das Torres para montagem das opções do ComboBox
    $optTorres = "";
    for ($i=0; $i<count($itens); $i++){
        $optTorres .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
   
    // Tipos de Arquivos
    $itens = $db_filebox->getTipos(); // Obtenção dos Tipos de Arquivos para montagem das opções do ComboBox
    $optArquivosTipos = "";
    for ($i=0; $i<count($itens); $i++){
        $optArquivosTipos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>';  // Incremento nas opções do ComboBox
    }

 
?>

<!DOCTYPE html>
<html>	
    <head>  
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Favicon -->
        <link rel="icon" href="../igot/img/torre1.ico" />
        <!-- Titulo -->
        <title>RISE</title>
        <?php include_once "../frames/head.php"; ?>
        <!-- Select2 Script -->
        <script src="../stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
        <!-- DataTable Script -->
        <script src="../stylesheet/AdminLTE/2.4.5/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../stylesheet/AdminLTE/2.4.5/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- Carrega os componentes Select2 -->
        <script>
            $(document).ready(function() {
                // Select2
                $('.select2').select2();
            });
        </script>
        <!-- DataGrid Custom Script -->
        <script src="../stylesheet/others/datagrid.js"></script>
	</head>
    
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            <!-- Cabeçalho -->
            <?php include_once "../frames/header.php"; ?>

            <!-- Barra Lateral -->
            <?php include_once "../frames/sidebar.php"; ?>
            
            <!-- Área de conteúdo da Página -->
            <div class="content-wrapper">
                <!-- Conteúdo da Página -->
                <section class="content">
                    <!-- Primeira linha de conteúdo -->
                    <div class="row">
                        <!-- Coluna -->
                        <div class="col-md-12">
                            <div class="box box" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div class="box-header with-border" style="background-color: #f7f7f7;">
                                    <br>                              
                                    <img width="100px" align="left" height="auto" alt="Guerreiro" src="../igot/img/arquivos-1.png">
                                    <br><h4><p class="titulo">FILEBOX</p></h4>
                                    <h4 id="arq'.$id.'" style="display:none;">1</h4>                                
                                    <p class="conteudo"> 
                                        Download de todos os arquivos, análise mais completa podendo fazer mais de um filtro de uma vez.                                    </P>
                                    <hr class="featurette-divider">                                                     
                                </div>                    
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box-filters">
                                                <div class="box-header">
                                                    <a href="#FiltroEventos" data-toggle="collapse" class="titulo">
                                                        <span><i class="fa fa-filter"></i> Filtro</span>
                                                    </a>
                                                </div>
                                                <div class="panel-collapse collapse in" id="FiltroEventos">
                                                    <div class="box-body">                                                       
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Categoria do Evento</label>
                                                                    <select class="form-control select2" multiple="multiple" id="eventos_categoria" name="eventos_categoria" style="width: 100%;">
                                                                        <?php echo $optEventosCategorias; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Aliança</label>
                                                                    <select class="form-control select2" multiple="multiple" id="eventos_alianca" style="width: 100%;">
                                                                        <?php echo $optAliancas; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Tipo de Evento</label>
                                                                    <select class="form-control select2" multiple="multiple" id="eventos_tipo" name="eventos_tipo" style="width: 100%;">
                                                                        <?php echo $optEventosTipos; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Torre</label>
                                                                    <select class="form-control select2" multiple="multiple" id="eventos_torre" style="width: 100%;">
                                                                        <?php echo $optTorres; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Guerreiro</label>
                                                                    <select class="form-control select2" multiple="multiple" id="eventos_guerreiro" name="eventos_guerreiro" style="width: 100%;">
                                                                        <option value="<?php echo $_SESSION['igot']['Guerreiro']['id'] ?>" ><?php echo $_SESSION['user']['name'] ?></option>
                                                                        <?php echo $optGuerreiros; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Registrado a partir de</label>
                                                                    <input class="editInput form-control input-sm" type="date" id="eventos_RegistradoEmI" name="eventos_RegistradoEmI" max="<?php echo date("Y-m-d"); ?>">
                                                                </div>
                                                            </div>
                                                           
                                                            <div class="col-md-2">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Registrado antes de</label>
                                                                    <input class="editInput form-control input-sm" type="date" id="eventos_RegistradoEmF" name="eventos_RegistradoEmF" max="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>">
                                                                </div>
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Concluído antes de</label>
                                                                    <input class="editInput form-control input-sm" type="date" id="eventos_ConcluidoEmF" name="eventos_ConcluidoEmF" value="<?php echo date("Y-m-d"); ?>">
                                                                </div>
                                                               
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group" style="width: 100%;">
                                                                    <label>Conluído a partir de</label>
                                                                    <input class="editInput form-control input-sm" type="date" id="eventos_ConcluidoEmI" name="eventos_ConcluidoEmI" max="<?php echo date("Y-m-d"); ?>">
                                                                </div>                                                                
                                                            </div>
                                                            <br>
                                                            <button onclick="showEventos()" type="button" class="btn btn-sm btn-default"><span class="fa fa-filter"></span> Exibir</button>
                                                            <button onclick="()" type="button" class="btn btn-sm btn-default"><span class="fa fa-download"></span> Baixar Todos</button>
                                                        </div>        
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dataTables_wrapper form-inline dt-bootstrap">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table class="table table-bordered table-hover table-striped dataTable" role="grid" id="tblEventos">
                                                            <thead id="thEventos">
                                                                <tr role="row" class="tblTitleRow">
                                                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Categoria">Categoria<br>do Evento</th>
                                                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Tipo">Tipo<br>do Evento</th>
                                                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Guerreiro">Guerreiro</th>
                                                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="NomeAlianca">Aliança</th>
                                                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Torre">Torre</th>
                                                                    <th class="sorting_asc datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataInicioEvento_YMD">Início</th>
                                                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataConclusao_YMD">Conclusão<br>Prevista ou Final</th>
                                                                    <th class="sorting_asc datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="tipoArquivo">Tipo</th>
                                                                    <th class="datagrid actions">Ações</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="row_position" id="tbodyEventos">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="eventos_modal" style="display:none;">
                                                <div class="modal-dialog modal-megamenu">
                                                    <div class="modal-content">
                                                        <form role="form">
                                                            <div class="modal-header" id="eventos_modalHeader"></div>
                                                            <div class="modal-body" id="eventos_modalBody"></div>
                                                            <div class="modal-footer" id="eventos_modalFooter"></div>
                                                        </form>
                                                    </div>
                                                </div>
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
            <?php include_once "../frames/footer.php"; ?>

            <!-- Painel de Controle -->
            <?php include_once "../frames/controlpanel.php"; ?>

            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>

            <!-- Page Custom script -->
    <script>
        $(document).ready(function() {
            // Cascading Dropbox (Categoria -> Tipo)
            $('#eventos_categoria').change(function(){
                if($('#eventos_categoria').val() != ""){
                    $('#eventos_tipo').load('../igot/config/options.php?return=eventostipos&categoria='+$('#eventos_categoria').val());
                } else {
                    $('#eventos_tipo').load('../igot/config/options.php?return=eventostipos');
                }
            });
            // Cascading Dropbox (Aliança -> Torres)
            $('#eventos_alianca').change(function(){
                if($('#eventos_alianca').val() != ""){
                    $('#eventos_torre').load('../igot/config/options.php?return=torres&alianca='+$('#eventos_alianca').val());
                } else {
                    $('#eventos_torre').load('../igot/config/options.php?return=torres');
                }
            });

        });

        // DataGrid - Carrega os Eventos Registrados com base nos filtros selecionados
        function showEventos(sort="DataInicioEvento_YMD", $id){
            
            //var certificado = $('#eventos_certificado').find(".checked"); // Varivável para localização do grupo de opções de Certificado
            var filtro = ""; // Variável parâmetros, filtros a serem aplicados na Query
           
            // Verifica os filtros selecionados
            if($('#eventos_categoria').val() != ""){
                filtro += '&categoria='+$('#eventos_categoria').val();
            }          
            if($('#eventos_tipo').val() != ""){
                filtro += '&tipoEvento='+$('#eventos_tipo').val();
            }
            if($('#eventos_alianca').val() != ""){
                filtro += '&alianca='+$('#eventos_alianca').val();
            }
            if($('#eventos_guerreiro').val() != ""){
                filtro += '&guerreiro='+$('#eventos_guerreiro').val();
            }
            if($('#eventos_torre').val() != ""){
                filtro += '&torre='+$('#eventos_torre').val();
            }
           
            if($('#eventos_RegistradoEmI').val() != ""){
                filtro += "&RegistradoEntre='"+$('#eventos_RegistradoEmI').val()+"' AND '"+$('#eventos_RegistradoEmF').val()+"'";
            }
            if($('#eventos_ConcluidoEmI').val() != ""){
                filtro += "&ConcluidoEntre='"+$('#eventos_ConcluidoEmI').val()+"' AND '"+$('#eventos_ConcluidoEmF').val()+"'";
            }
            
            // Carrega os resultados com base no Filtro
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                table = new XMLHttpRequest();
               
            } else {
                // code for IE6, IE5
                table = new ActiveXObject("Microsoft.XMLHTTP");
               
            }
            table.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tbodyEventos").innerHTML = this.responseText;
                }
            };
            table.open("GET", '../igot/config/results.php?return=BuscaAvancada&type=DataGrid&sort='+sort+filtro, true);
            table.send();

            // Carrega o Total do Custo com base na Query de resultados
            //custo.onreadystatechange = function() {
                //if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("Custo").innerHTML = this.responseText;
                //}
            //};
            //custo.open("GET", 'igot/config/results.php?return=Eventos&type=DataGrid&Custo=Total'+filtro, true);
            //custo.send();

            // Exibe o botão Novo se oculto
            $('#FiltroEventos').find($(".btnNew")).show();
        }      
        
        // Modal - Upload de Arquivo(s)
        function closeModalEventoUpload(){
            $('#eventos_upload_modal').modal('hide');
        }
    </script>

    <!-- Script para edição dos itens -->
    <script>
        // Função do botão Enviar - Realiza Upload de Arquivo(s)
        function UploadFile2Evento(btnID){
            var postURL = "igot/admin/ajax/upload.php";
            var pagPart = $('#eventos_modalBody'); // Restringe os objetos em uma parte específica da página
            var ID = btnID.substring(9, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            var inputData = new FormData(); // Variável para armazenamento dos dados do Formulário
            inputData.append('action', 'insert'); // Concatena a ação a ser realizada
            inputData.append('idItem', ID); // Concatena o ID do Item (Evento)
            inputData.append('idArea', <?php echo IGOT_EVENTOS; ?>); // Concatena o ID para Identificação da Área
            inputData.append('fileToUpload', $('#modal_eventos_Arquivo')[0].files[0]); // Concatena o arquivo selecionado
            inputData.append('idTipo', $("#modal_eventos_TipoArquivo").val()); // Concatena o Tipo de Arquivo
            inputData.append('Descricao', $('#modal_eventos_DescricaoArquivo').val()); // Concatena a Descricao
            $.ajax({
                url: postURL,
                type:'POST',
                dataType: 'json',
                data: inputData,
                cache: false,
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                beforeSend: function(){
                    pagPart.find(".Uploading").html('Uploading...');
                    pagPart.find(".Uploading").show();
                },
                success:function(response){                    
                    // Se for um Certificado - Marca o Evento com a disponibilidade do mesmo
                    /*if(response.data.Tipo === "Certificado"){
                        $.ajax({
                            url: <?php echo $postURL; ?>,
                            type: 'POST',
                            dataType: 'JSON',
                            data: 'action=update&id='+ID+'&idCertificado='+response.data.id
                        });
                    }*/
                    // Atualiza a lista de Arquivos do Evento
                    idArea = <?php echo IGOT_EVENTOS; ?>;
                    postURL = '"'+postURL+'"';
                    pagPart.find(".Uploading").hide();
                    pagPart.find(".form-control.Arquivos").append(
                       "<div class='row' name="+response.data.id+" style='padding-top:1px;padding-bottom:1px;'>"
                            +"<div class='col-sm-9' style='margin-top:5px;'>"
                               +"<input name='"+response.data.Tipo+"' value='"+response.data.id+"' class='editInput hided' style='display:none;'/>"
                               +"<a href='/download.php?id="+response.data.id+"' target='_blank' style='color:#555;'><span class='fa fa-download'></span> "+response.data.Tipo+"</a>"
                            +"</div>"
                            +"<div class='col-sm-3'>"
                                +"<button onclick='Delete(this.id, eventos_arquivos)' id='btnDelete"+response.data.id+"' type='button' class='btn btn-sm btn-default btnDelete' style='float:none;'><span class='fa fa-trash'></span></button>"
                                +"<button onclick='Remove(this.id, eventos_arquivos, "+postURL+", "+idArea+")' id='btnRemove"+response.data.id+"' type='button' class='btn btn-sm btn-danger btnRemove' style='float:none; display:none;'><span class='fa fa-remove'></span></button>"
                            +"</div>"
                        +"</div>"
                    );
                    $('#eventos_upload_modal').modal('hide'); // Fecha o Modal - Upload de Arquivo(s)
                }
            });
        }
    </script>

    </body>
</html>
 
