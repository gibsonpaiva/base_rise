<?php
    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
    $db_filebox = new FileBox();

    // Montagem das opções dos combobox
    $postURL = "'admin/ajax/updEventos.php'";
    // Categoria de Eventos
    $itens = $db_igot->getEventosCategorias(); // Obtenção dos Tipos de Eventos para montagem das opções do ComboBox
    $optEventosCategorias = "";
    for ($i=0; $i<count($itens); $i++){
        $optEventosCategorias .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Status de Eventos
    $itens = $db_igot->getEventosStatus(); // Obtenção dos Status de Eventos para montagem das opções do ComboBox
    $optStatusEvento = "";
    for ($i=0; $i<count($itens); $i++){
        $optStatusEvento .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
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
    // Status de Aprovação
    $itens = $db_igot->getAprovacaoStatus(); // Obtenção dos Status de Aprovação para montagem das opções do ComboBox
    $optStatusAprovacao = "";
    for ($i=0; $i<count($itens); $i++){
        $optStatusAprovacao .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Tipos de Arquivos
    $itens = $db_filebox->getTipos(); // Obtenção dos Tipos de Arquivos para montagem das opções do ComboBox
    $optArquivosTipos = "";
    for ($i=0; $i<count($itens); $i++){
        $optArquivosTipos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>';  // Incremento nas opções do ComboBox
    }
    // Exercito
    $itens = $db_igot->getExercitos(); // Obtenção dos Tipos de Arquivos para montagem das opções do ComboBox
    $optExercitos = "";
    for ($i=0; $i<count($itens); $i++){
        $optExercitos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>';  // Incremento nas opções do ComboBox
    }
?>

<div class="tab-pane" id="Eventos">

    <img width="100px" align="left" height="auto" alt="Guerreiro" src="/igot/img/eventos-2.png">
    <br><h4><p class="titulo">REGISTRO DE EVENTOS</p></h4>
    <p class="conteudo"> 
        Evento é um fato, um acontecimento registrado para o guerreiro em sua linha do tempo. 
    </P>
    <hr class='featurette-divider'>

                <!-- Filtro -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box-filters">
                            <div class="box-header">
                                <a href="#FiltroEventos" data-toggle="collapse" class="titulo">
                                    <span><i class="fa fa-filter"></i> Filtro</span>
                                </a>
                            </div>
                            <div class="panel-collapse collapse in" id="FiltroEventos">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" style="margin-top:-40px;" style="width: 100%;">
                                                <label>Evento com Certificado</label>
                                                <div class="row" id="eventos_certificado">
                                                    <div class="col-sm-4" align="center">
                                                        <input type="radio" name="Certificado" value="1"><br>Sim
                                                    </div>
                                                    <div class="col-sm-4" align="center">
                                                        <input type="radio" name="Certificado" value="0"><br>Não
                                                    </div>
                                                    <div class="col-sm-4" align="center">
                                                        <input type="radio" name="Certificado" value="" checked="checked"><br>Todos
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <!-- Botões -->
                                            <div class="pull-left" style="margin-top:-25px;">
                                                <button onclick="showEventos()" type="button" class="btn btn-sm btn-default"><span class="fa fa-filter"></span> Exibir</button>
                                            </div>
                                            <div class="pull-right" style="margin-top:-25px;">
                                                <button onclick="NewEventos()" type="button" class="btn btn-sm btn-default btnNew" id="btnNew" style="margin-left: 15px;"><span class="glyphicon glyphicon-plus"></span> Novo</button>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Categoria do Evento</label>
                                                <select class="form-control select2" multiple="multiple" id="eventos_categoria" name="eventos_categoria" style="width: 100%;">
                                                    <?php echo $optEventosCategorias; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Status do Evento</label>
                                                <select class="form-control select2" multiple="multiple" id="eventos_status" style="width: 100%;">
                                                    <?php echo $optStatusEvento; ?>
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
                                                <label>Status de Aprovação</label>
                                                <select class="form-control select2" multiple="multiple" id="eventos_StatusAprovacao" style="width: 100%;">
                                                    <?php echo $optStatusAprovacao; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Exército</label>
                                                <div class="tooltip edit">  
                                                    <span class="fa fa-question-circle" data-toggle="modal" style="cursor: pointer;"></span>
                                                    <span class="tooltipInfo">Selecione o campo <b>Exército</b> para mostrar a lista de seus <b>Guerreiros.</b></span>
                                                </div> 
                                                <select class="form-control select2" multiple="multiple" id="eventos_exercitos"  style="width: 100%;">
                                                    <option value="<?php echo $_SESSION['igot']['Guerreiro']['idExercito'] ?>" ><?php echo $_SESSION['igot']['Guerreiro']['Exercito'] ?></option>
                                                    <?php echo $optExercitos; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Guerreiro</label>                                                
                                                <select class="form-control select2" multiple="multiple" id="eventos_guerreiro" name="eventos_guerreiro" style="width: 100%;">
                                                    <option value="<?php echo $_SESSION['igot']['Guerreiro']['id'] ?>" ><?php echo $_SESSION['user']['name'] ?></option>
                                                    <?php echo $optGuerreiros; ?>
                                                </select>
                                             </div>                                            
                                        </div>
                                        
                                        <div class="col-md-2">                                            
                                            <div class="form-group" style="width: 100%;">
                                                <label>Aliança</label>
                                                <div class="tooltip edit">  
                                                    <span class="fa fa-question-circle" data-toggle="modal" style="cursor: pointer;"></span>
                                                    <span class="tooltipInfo">Selecione o campo <b>Aliança</b> para mostrar a lista de suas <b>Torres.</b></span>
                                                </div> 
                                                <select class="form-control select2" multiple="multiple" id="eventos_alianca" style="width: 100%;">
                                                    <?php echo $optAliancas; ?>
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
                                                <label>Registrado a partir de</label>
                                                <input class="editInput form-control input-sm" type="date" id="eventos_RegistradoEmI" name="eventos_RegistradoEmI" max="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Registrado antes de</label>
                                                <input class="editInput form-control input-sm" type="date" id="eventos_RegistradoEmF" name="eventos_RegistradoEmF" max="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Conluído a partir de</label>
                                                <input class="editInput form-control input-sm" type="date" id="eventos_ConcluidoEmI" name="eventos_ConcluidoEmI" max="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <label>Concluído antes de</label>
                                                <input class="editInput form-control input-sm" type="date" id="eventos_ConcluidoEmF" name="eventos_ConcluidoEmF" value="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="panel-primary">
                    <div style="width: 100%;">
                        <div class="table-responsive">  
                            <table id="example" class="table table-bordered table-hover table-striped dataTable" role="grid" cellspacing="0" id="tblEventos"> 
                                <thead id="thEventos">
                                <tr role="row" class="tblTitleRow">
                                    <th class="sorting datagrid">#</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')">ID</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Categoria">Categoria<br>do Evento</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Tipo">Tipo<br>do Evento</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Guerreiro">Guerreiro</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="NomeAlianca">Aliança</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Torre">Torre</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Custo">Custo<br><span id="Custo" class="detail"></span></th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Status">Status<br>do Evento</th>
                                    <th class="sorting_asc datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataInicioEvento_YMD">Início</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataConclusao_YMD">Conclusão<br>Prevista ou Final</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataExpiracaoEvento_YMD">Expiração<br>do Evento</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="StatusAprovacao">Status<br>da Aprovação</th>
                                    <th class="datagrid actions">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="row_position" id="tbodyEventos">
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- Tabela 
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover table-striped dataTable" role="grid" id="tblEventos">
                                <thead id="thEventos">
                                    <tr role="row" class="tblTitleRow">
                                        <th class="sorting datagrid">#</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')">ID</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Categoria">Categoria<br>do Evento</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Tipo">Tipo<br>do Evento</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Guerreiro">Guerreiro</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="NomeAlianca">Aliança</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Torre">Torre</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Custo">Custo<br><span id="Custo" class="detail"></span></th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="Status">Status<br>do Evento</th>
                                        <th class="sorting_asc datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataInicioEvento_YMD">Início</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataConclusao_YMD">Conclusão<br>Prevista ou Final</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="DataExpiracaoEvento_YMD">Expiração<br>do Evento</th>
                                        <th class="sorting datagrid" onclick="Sort(this, 'thEventos', 'showEventos')" col="StatusAprovacao">Status<br>da Aprovação</th>
                                        <th class="datagrid actions">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="row_position" id="tbodyEventos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div-->
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
    

    <!-- Page Custom script -->
    <script>

        $(document).ready(function() {
            // Cascading Dropbox (Categoria -> Tipo)
            $('#eventos_categoria').change(function(){
                if($('#eventos_categoria').val() != ""){
                    $('#eventos_tipo').load('config/options.php?return=eventostipos&categoria='+$('#eventos_categoria').val());
                } else {
                    $('#eventos_tipo').load('config/options.php?return=eventostipos');
                }
            });
            // Cascading Dropbox (Aliança -> Torres)
            $('#eventos_alianca').change(function(){
                if($('#eventos_alianca').val() != ""){
                    $('#eventos_torre').load('config/options.php?return=torres&alianca='+$('#eventos_alianca').val());
                } else {
                    $('#eventos_torre').load('config/options.php?return=torres');
                }
            });

            // Cascading Dropbox (Exercitos -> Guerreiros)
            $('#eventos_exercitos').change(function(){
                if($('#eventos_exercitos').val() != ""){
                    $('#eventos_guerreiro').load('config/options.php?return=guerreiros&exercito='+$('#eventos_exercitos').val());
                } else {
                    $('#eventos_guerreiro').load('config/options.php?return=guerreiro');
                }
            });

        });

        // DataGrid - Carrega os Eventos Registrados com base nos filtros selecionados
        function showEventos(sort="DataInicioEvento_YMD"){
            var certificado = $('#eventos_certificado').find(".checked"); // Varivável para localização do grupo de opções de Certificado
            var filtro = ""; // Variável parâmetros, filtros a serem aplicados na Query
            // Verifica os filtros selecionados
            if($('#eventos_categoria').val() != ""){
                filtro += '&categoria='+$('#eventos_categoria').val();
            }
            if($('#eventos_exercitos').val() != ""){
                filtro += '&exercitos='+$('#eventos_exercitos').val();
            }
            if($('#eventos_status').val() != ""){
                filtro += '&status='+$('#eventos_status').val();
            }
            if($('#eventos_tipo').val() != ""){
                filtro += '&tipo='+$('#eventos_tipo').val();
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
            if(certificado.find('input').val() != ""){
                filtro += '&Certificado='+certificado.find('input').val();
            }
            if($('#eventos_StatusAprovacao').val() != ""){
                filtro += '&StatusAprovacao='+$('#eventos_StatusAprovacao').val();
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
                custo = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                table = new ActiveXObject("Microsoft.XMLHTTP");
                custo = new ActiveXObject("Microsoft.XMLHTTP");
            }
            table.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tbodyEventos").innerHTML = this.responseText;
                }
            };
            table.open("GET", 'config/results.php?return=Eventos&type=DataGrid&sort='+sort+filtro, true);
            table.send();

            // Carrega o Total do Custo com base na Query de resultados
            custo.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("Custo").innerHTML = this.responseText;
                }
            };
            custo.open("GET", 'config/results.php?return=Eventos&type=DataGrid&Custo=Total'+filtro, true);
            custo.send();

            // Exibe o botão Novo se oculto
            $('#FiltroEventos').find($(".btnNew")).show();
        }

        // Modal - Parametriza e exibe o modal do item selecionado
        function showModalEvento(btnID, Editable=0){
            var pagPart = $('#eventos_modalBody'); // Restringe os objetos em uma parte específica da página
            var ID = btnID.substring(8, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            // Carrega o Evento selecionado
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                content = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                content = new ActiveXObject("Microsoft.XMLHTTP");
            }
            // Popula o Body do Modal
            content.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("eventos_modalBody").innerHTML = this.responseText;
                }
            };
            content.open("GET", 'config/results.php?return=Eventos&type=Form&id='+ID, true);
            content.send();

            // Popula a barra de Título
            document.getElementById("eventos_modalHeader").innerHTML =
                '<button class="close" aria-label="Close" type="button" data-dismiss="modal"><span class="fa fa-times" aria-hidden="true"></span></button>'
                +'<h4 class="modal-title">Evento '+ID+'</h4>';
            
            // Aguarda para formatar o Modal
            setTimeout(formatModalEvento, 2000);
                        
            // Popula o rodapé do Modal com botões
            document.getElementById("eventos_modalFooter").innerHTML = '<button data-dismiss="modal" type="button" class="btn btn-default btnCancel"><span class="fa fa-undo"></span> Cancelar</button>';
            if(Editable){
                $("#eventos_modalFooter").append(''
                    +'<button type="button" data-toggle="modal" data-target="#eventos_upload_modal" class="btn btn-default" style="margin-left:5px;"><span class="fa fa-cloud-upload"></span> Upload</button>'
                    +'<button onclick="UpdateEventos(this.id)" id="btnUpdt'+ID+'" type="button" class="btn btn-success btnSave"><span class="fa fa-save"></span> Salvar</button>'
                );
            }
            // Inclui botão de download da Evidência, se disponível
            //var hyperlink = '/igot/filebox/eventos/registrados/'+ID+'.pdf'; // Hyperlink da Evidência
            //$.ajax({
            //    url: hyperlink,
            //    type:'HEAD',
            //    error: function(){ // Evidência Indisponível                
            //    },
            //    success: function(){ // Evidência disponível
            //        var btnDownload = '<a href="'+hyperlink+'" target="_blank" class="btn btn-default btnDownload"><span class="fa fa-file-pdf-o"></span> Certificado</button>';
            //        $(btnDownload).prependTo($("#eventos_modalFooter")); // Inclui o botão de download no rodapé do Modal
            //    }
            //});

            // Exibe o Modal
            $('#eventos_modal').modal('show');
        }
        // Modal - Formata os componentes
        function formatModalEvento(){
            var pagPart = $('#eventos_modalBody'); // Restringe os objetos em uma parte específica da página
            // Adiciona opções às combobox
            pagPart.find(".form-control.CategoriaEvento").append('<?php echo $optEventosCategorias; ?>')
            pagPart.find(".form-control.StatusEvento").append('<?php echo $optStatusEvento; ?>');
            pagPart.find('.form-control.StatusAprovacao').append('<?php echo $optStatusAprovacao; ?>');
            pagPart.find(".form-control.Alianca").append('<?php echo $optAliancas; ?>');
            pagPart.find(".form-control.Exercito").append('<?php echo $optExercitos; ?>');
            pagPart.find(".form-control.TipoArquivo").append('<?php echo $optArquivosTipos; ?>');
            // Formata os Combobox - Select2
            pagPart.find('.select2').select2();
            // Formata o Campo Numérico
            pagPart.find(".form-control.Custo").autoNumeric('init');

            // Cascading Dropbox (Categoria de Evento -> Tipo de Evento)
            $('#modal_eventos_categoria').change(function(){
                $('#modal_eventos_tipo').load('config/options.php?return=eventostipos&categoria='+$('#modal_eventos_categoria').val());
            });
            // Cascading Dropbox (Aliança -> Torres)
            $('#modal_eventos_alianca').change(function(){
                $('#modal_eventos_torre').load('config/options.php?return=torres&alianca='+$('#modal_eventos_alianca').val());
            });
            // Cascading Dropbox (Exercitos -> Guerreiros)
            $('#modal_eventos_exercito').change(function(){
                $('#modal_eventos_guerreiro').load('config/options.php?return=guerreiros&exercito='+$('#modal_eventos_exercito').val());
            });
            // Cascading Dropbox (Guerreiro -> Proprietario)
            $('#modal_eventos_guerreiro').change(function(){
                $('#modal_eventos_proprietario').load('config/options.php?return=proprietarios&guerreiro='+$('#modal_eventos_guerreiro').val());
            });
        }
        // Modal - Upload de Arquivo(s)
        function closeModalEventoUpload(){
            $('#eventos_upload_modal').modal('hide');
        }
    </script>

    <!-- Script para edição dos itens -->
    <script>
        // Função do botão Novo - Adiciona nova linha à tabela
        function NewEventos(){
            var postURL = "<?php echo $postURL; ?>"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
            var pagPart = $('#Eventos'); // Restringe os objetos em uma parte específica da página
            pagPart.find($("#btnNew")).hide(); // Oculta o botão Novo
            // HTML da Nova Linha
            var newRow = 
            '<tr class="ui-sortable-handle" name="NewRecord">'
                +'<td class="datagrid" align="center">'
                   
                +'</td>'
                +'<td class="datagrid" align="center">'
                   
                +'</td>'
                +'<td class="datagrid" align="center">'
                    +'<span class="viewAlways Categoria"></span>'
                +'</td>'
                 +'<td class="datagrid" style="max-width:200px;">'
                    +'<span class="viewAlways Tipo"></span>'
                    +'<div id="eventos_editTipo"><select class="editInput form-control select2 Tipo" name="idTipo"><?php echo $optEventosTipos ?></select></div>'
                +'</td>'
                 +'<td class="datagrid">'
                    +'<span class="viewAlways Guerreiro"></span>'
                    +'<div id="eventos_editGuerreiro"><select class="editInput form-control select2 Guerreiro" name="idGuerreiro"></select></div>'
                +'</td>'
                 +'<td class="datagrid" align="center">'
                    +'<span class="viewAlways Alianca"></span>'
                +'</td>'
                 +'<td class="datagrid" style="max-width:100px;">'
                    +'<span class="viewAlways Torre"></span>'
                    +'<div id="eventos_editTorre"><select class="editInput form-control select2 Torre" name="idTorre"><?php echo $optTorres ?></select></div>'
                +'</td>'
                 +'<td class="datagrid" align="right">'
                    +'<span class="viewOnly Custo"></span>'
                    +'<input class="editInput form-control Custo" name="Custo" data-a-sep="." data-a-dec="," style="text-align:right;"/>'
                +'</td>'
                 +'<td class="datagrid" align="center">'
                    +'<span class="viewOnly StatusEvento"></span>'
                    +'<select class="editInput form-control select2" name="idStatus"><?php echo $optStatusEvento ?></select>'
                +'</td>'
                +'<td class="datagrid" align="center" style="max-width:90px;">'
                    +'<span class="viewOnly DataInicioEvento"></span>'
                    +'<input class="editInput form-control input-sm" type="date" name="DataInicioEvento">'
                +'</td>'
                +'<td class="datagrid" align="center" style="max-width:90px;">'
                    +'<span class="viewOnly DataConclusao"></span>'
                    +'<input class="editInput form-control input-sm" type="date" name="DataConclusao">'
                +'</td>'
                +'<td class="datagrid" align="center" style="max-width:90px;">'
                    +'<span class="viewOnly DataExpiracaoEvento"></span>'
                    +'<input class="editInput form-control input-sm" type="date" name="DataExpiracaoEvento">'
                +'</td>'
                +'<td class="datagrid" align="center">'
                    +'<span class="viewOnly StatusAprovacao"></span>'
                    +'<select class="editInput form-control select2 StatusAprovacao" name="idStatusAprovacao"><?php echo $optStatusAprovacao ?></select>'
                +'</td>'
                +'<td class="datagrid actions" align="center">'
                    +'<div class="tooltip"><button onClick="showModalEvento(this.id, 1)" id="btnModal" type="button" class="btn btn-sm btn-default btnModal" style="float:none; display:none;"><span class="fa fa-list-alt"></span></button><span class="tooltiptext">Detalhes</span></div>'
                    +'<div class="tooltip"><button onClick="EditEventos(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button><span class="tooltiptext">Editar</span></div>'
                    +'<div class="tooltip"><button onClick="UpdateEventos(this.id)" id="btnSave" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button><span class="tooltiptext">Salvar</span></div>'
                    +'<div class="tooltip"><button onClick="Delete(this.id, tblEventos)" id="btnDelete" type="button" class="btn btn-sm btn-default btnDelete" style="float:none; display:none;"><span class="glyphicon glyphicon-trash"></span></button><span class="tooltiptext">Excluir</span></div>'
                    +'<div class="tooltip"><button onClick="Remove(this.id, tblEventos, '+postURL+')" id="btnRemove" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button><span class="tooltiptext">Confirmar Exclusão</span></div>'
                    +'<div class="tooltip"><button onclick="SaveEventos()" id="btnSaveNew" type="button" class="btn btn-sm btn-success btnSaveNew" style="float:none;"><span class="fa fa-save"></span></button><span class="tooltiptext">Salvar</span></div>'
                    +'<div class="tooltip"><button onclick="Cancel(this.id, tblEventos)" id="btnCancelNewRecord" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px;"><span class="fa fa-undo"></span></button><span class="tooltiptext">Cancelar</span></div>'
                +'</td>'
            +'</tr>';
            // Adiciona a nova Linha no topo da Tabela
            $(newRow).prependTo($("#tbodyEventos"));
            // Identificação da nova linha na tabela
            var trObj = pagPart.find($("[name='NewRecord']")); // Variável para resumo na identificação da linha corrente
            // Adiciona opções ao(s) Combobox
            trObj.find('.editInput.Guerreiro').load('config/options.php?return=guerreiros&groups=true');
            // Formata o(s) Combobox do tipo Select2 e Campo(s) Numéricos 
            trObj.find('.select2').select2();
            trObj.find(".editInput.Custo").autoNumeric('init');
        }
        // Função do botão Salvar - Insere um novo registro
        function SaveEventos(){
            var pagPart = $('#Eventos'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='NewRecord']")); // Variável para resumo na identificação da linha corrente
            var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
            $.ajax({
                url: <?php echo $postURL; ?>,
                type:'POST',
                dataType: "json",
                data:'action=insert&'+inputData,
                success:function(response){
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewAlways.Categoria").text(response.data.Categoria);
                    trObj.find(".viewAlways.Tipo").text(response.data.Tipo);
                    trObj.find(".viewAlways.Guerreiro").text(response.data.Guerreiro);
                    trObj.find(".viewAlways.Alianca").text(response.data.Alianca);
                    trObj.find(".viewAlways.Torre").text(response.data.Torre);
                    trObj.find(".viewOnly.Custo").text(response.data.Custo);
                    trObj.find(".viewOnly.StatusEvento").text(response.data.Status);
                    trObj.find(".viewOnly.DataInicioEvento").text(response.data.DataInicioEvento);
                    trObj.find(".viewOnly.DataConclusao").text(response.data.DataConclusao);
                    trObj.find(".viewOnly.DataExpiracaoEvento").text(response.data.DataExpiracaoEvento);
                    trObj.find(".viewOnly.StatusAprovacao").text(response.data.StatusAprovacao);
                    trObj.find(".viewAlways.StatusAprovacao").text(response.data.StatusAprovacao);
                    // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                    trObj.find(".editInput").hide(); // Oculta os campos de Edição
                    trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                    trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                    trObj.find(".btnSaveNew").remove(); // Remove o botão Salvar (novo registro)
                    document.getElementById("eventos_editTipo").remove(); // Remove o campo de edição do Tipo de Evento
                    document.getElementById("eventos_editGuerreiro").remove(); // Remove o campo de edição do Guerreiro
                    document.getElementById("eventos_editTorre").remove(); // Remove o campo de edição da Torre
                    trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                    trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                    trObj.find(".btnModal").show(); // Exibe o botão Modal
                    trObj.find(".btnEdit").show(); // Exibe o botão Editar
                    trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                    pagPart.find($(".btnNew")).show(); // Exibe o botão Novo
                    // Atualiza o Nome da Linha e de seus objetos
                    trObj.attr("name", response.data.id); // Atualiza o Nome da TR
                    trObj.find(".btnModal").attr("id", "btnModal" + response.data.id); // Atualiza o ID do botão Modal
                    trObj.find(".btnEdit").attr("id", "btnEdit" + response.data.id); // Atualiza o ID do botão Editar
                    trObj.find(".btnSave").attr("id", "btnSave" + response.data.id); // Atualiza o ID do botão Savar
                    trObj.find(".btnCancel").attr("id", "btnCancel" + response.data.id); // Atualiza o ID do botão Cancelar
                    trObj.find(".btnDelete").attr("id", "btnDelete" + response.data.id); // Atualiza o ID do botão Deletar
                    trObj.find(".btnRemove").attr("id", "btnRemove" + response.data.id); // Atualiza o ID do botão Remover
                }
            });
        }

        // Função do botão Editar
        function EditEventos(btnID){
            var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            var pagPart = $('#Eventos'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='"+ID+"']")); // Variável para resumo na identificação da linha corrente
            // Inverte a exibição dos campos de Visualização pelos de Edição e do botão Editar pelo Salvar
            trObj.find(".viewOnly").hide(); // Oculta os campos de visualização
            trObj.find(".editInput").show(); // Exibe os campos para Edição
            trObj.find(".hided").hide(); // Garante que os campos ocultos permaneçam oculto  
            trObj.find(".btnDownload").hide(); // Oculta o botão de Download do Certificado
            trObj.find(".btnModal").hide(); // Oculta o botão Modal
            trObj.find(".btnEdit").hide(); // Oculta o botão Editar
            trObj.find(".btnDelete").hide(); // Oculta o botão Deletar
            trObj.find(".btnSave").show(); // Exibe o botão Salvar
            trObj.find(".btnCancel").show(); // Exibe o botão Cancelar
            // Formata o(s) Combobox do tipo Select2 e Campo(s) Numéricos 
            trObj.find('.select2').select2();
            trObj.find(".editInput.Custo").autoNumeric('init');
        }
        // Função do botão Salvar - Atualiza registro existente
        function UpdateEventos(btnID){
            var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            var btn = btnID.substring(0, 7); // Variável para Identificação do botão
            var pagPart = $('#Eventos'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='"+ID+"']")); // Variável para resumo na identificação da linha corrente           
            // Identificação do Botão (Botão Salvar do DataGrid ou Modal?)
            if (btn === 'btnSave'){ // Botão Salvar do DataGrid
                var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
            } else if (btn === 'btnUpdt'){ // Botão Salvar do Modal
                var frObj = $('#eventos_modalBody'); // Variável para resumo na identificação do Corpo do Modal
                var inputData = frObj.find('.editInput').serialize(); // Variável para os valores informados no Formulário do Modal                
                // Fecha o Modal
                $('#eventos_modal').modal('hide');
            }
            $.ajax({
                url: <?php echo $postURL; ?>,
                type:'POST',
                dataType: "json",
                data: 'action=update&id='+ID+'&'+inputData,
                success:function(response){
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewAlways.Categoria").text(response.data.Categoria);
                    trObj.find(".viewAlways.Tipo").text(response.data.Tipo);
                    trObj.find(".viewAlways.Evento").text(response.data.Evento);
                    trObj.find(".viewAlways.Guerreiro").text(response.data.Guerreiro);
                    trObj.find(".viewAlways.Alianca").text(response.data.Alianca);
                    trObj.find(".viewAlways.Torre").text(response.data.Torre);
                    trObj.find(".viewOnly.Custo").text(response.data.Custo);
                    trObj.find(".viewOnly.StatusEvento").text(response.data.Status);
                    trObj.find(".viewOnly.DataInicioEvento").text(response.data.DataInicioEvento);
                    trObj.find(".viewOnly.DataConclusao").text(response.data.DataConclusao);
                    trObj.find(".viewOnly.DataExpiracaoEvento").text(response.data.DataExpiracaoEvento);
                    trObj.find(".viewOnly.StatusAprovacao").text(response.data.StatusAprovacao);
                    trObj.find(".viewAlways.StatusAprovacao").text(response.data.StatusAprovacao);
                    // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                    trObj.find(".editInput").hide(); // Oculta os campos para Edição
                    trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                    trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                    trObj.find(".btnDownload").show(); // Exibe o botão de Download do Certificado
                    trObj.find(".btnModal").show(); // Exibe o botão Modal
                    trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                    trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                    trObj.find(".btnEdit").show(); // Exibe o botão Editar
                    trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                }
            });
        }

        // Função do botão Enviar - Realiza Upload de Arquivo(s)
        function UploadFile2Evento(btnID){
            var postURL = "/admin/ajax/upload.php";
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
</div>
