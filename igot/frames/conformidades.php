<?php
    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();

    // Listagem das Alianças
    $itens = $db_igot->getAliancas();
    // Motagem das opções do combobox para seleção da Aliança
    $optAliancas = "";
    for ($i=0; $i<count($itens); $i++){
        $optAliancas .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }

    // Listagem dos Tipos de Conformidades
    $itens = $db_igot->getConformidadesTipos();
    // Motagem das opções do combobox para seleção do Tipo de Conformidade
    $optConformidadesTipos = "";
    for ($i=0; $i<count($itens); $i++){
        $optConformidadesTipos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
?>

<div class="tab-pane" id="Conformidades">

    <img width="100px" align="left" height="auto" alt="Guerreiro" src="/igot/img/conformidades-1.png">
    <br><h4><p class="titulo">CONFORMIDADES COM AS ALIANÇAS</p></h4>
    <p class="conteudo"> 
        Conformidades são acordos fechados com as alianças que nos garantem a continuidade da parceria. 
    </P>
    <hr class='featurette-divider'>

    <div class="box-body">
        <div class="row">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <!-- Filtro -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box-filters">
                            <div class="box-header">
                                <a href="#FiltroConformidades" data-toggle="collapse" class="titulo">
                                    <span> <i class="fa fa-filter"></i> Filtro </span>
                                </a>
                            </div>
                            <div class="panel-collapse collapse in" id="FiltroConformidades">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Aliança</label>
                                                <select class="form-control select2" multiple="multiple" id="conformidades_aliancas" style="width: 100%;">
                                                    <?php echo $optAliancas; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Tipo de Conformidade</label>
                                                <select class="form-control select2" multiple="multiple" id="conformidades_tipo" name="conformidades_tipo" style="width: 100%;">
                                                    <?php echo $optConformidadesTipos; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- Botões -->
                                            <div align="center" class="form-group" style="width:100%; padding-top:23px;">                                                
                                                <div class="col-md-6">
                                                    <button onclick="showConformidades()" type="button" class="btn btn-sm btn-default"><span class="fa fa-filter"></span> Exibir</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Lista Conformidades -->
        <div class="row">
            <div class="col-sm-12" id="ListaConformidades"></div>
        </div>
    </div>
    
    <!-- Page Custom script -->
    <script>
        // Carrega as Conformidades com base nos filtros selecionados
        function showConformidades(sort="Alianca, Conformidade"){
            var filtro = ""; // Variável parâmetros, filtros a serem aplicados na Query
            // Verifica os filtros selecionados
            if($('#conformidades_aliancas').val() != ""){
                filtro += '&alianca='+$('#conformidades_aliancas').val();
            }
            if($('#conformidades_tipo').val() != ""){
                filtro += '&tipo='+$('#conformidades_tipo').val();
            }
            
            // Carrega os resultados com base no Filtro
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                list = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                list = new ActiveXObject("Microsoft.XMLHTTP");
            }
            list.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("ListaConformidades").innerHTML = this.responseText;
                }
            };
            list.open("GET", 'config/results.php?return=Conformidades&type=List&sort='+sort+filtro, true);
            list.send();
        }
    </script>
</div>