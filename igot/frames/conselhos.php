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
    // Conselheiros
    $itens = $db_igot->getGuerreiros(); // Obtenção dos Guerreiros para montagem das opções do ComboBox 
    $optConselheiros = "";
    for ($i=0; $i<count($itens); $i++){
        $optConselheiros .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Reinos
    $itens = $db_igot->getReinos(); // Obtenção dos Reinos para montagem das opções do ComboBox 
    $optReinos = "";
    for ($i=0; $i<count($itens); $i++){
        $optReinos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
    // Patentes
    $itens = $db_igot->getPatentes(); // Obtenção das Patentes para montagem das opções do ComboBox
    $optPatentes = "";
    for ($i=0; $i<count($itens); $i++){
        $optPatentes .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
?>

<div class="tab-pane" id="Conselhos">
    
    <div class="row">
        <div class="col-md-1" align="center">

            <img width="100px" height="auto" alt="Escudo" src="/igot/img/conselheiro-rei-1.png">
                   
        </div>
        <div class="col-md-4" align="justify">

            <br><h4><p class="titulo">OS CONSELHOS E AS HIERARQUIAS NOS REINOS</p></h4>

            Os CONSELHOS são organizações criadas dentro dos REINOS para promover a distribuição de guerreiros.
            Cada REINO tem seu próprio conselho, formado por guerreiros, suas respsonsabilidades e patentes. 
            Fora do GAME, um REINO equivale a um SQUAD. 
                    
        </div>
        <div class="col-md-7" align="justify">
        
            <br><h4><p class="titulo">AS PATENTES DE REINOS E SUAS EQUIVALÊNCIAS</p></h4>
            
            <b>CAVALEIRO</b> : Coordenador de divisão. Responde para o Guardião (Gerente), que responde para o General (diretor).<br>
            <b>ESCUDEIRO</b> : Líder Técnico de divisão. Serve ao Cavaleiro e apoia os Conselheiros em atividades e em tomadas de decisão. <br>
            <b>MEMBRO(S)</b> : Integrante de divisão. Membro do CONSELHO, sob comando do Cavaleiro e escolhido por suas habilidades.<br>
            <b>RECURSO(S)</b> : Em determinados REINOS, ele não é membro de divisão, mas é RECURSO envolvido nele por suas habilidades.<br>
 
        </div> 
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
                                <a href="#FiltroConselheirosReinos" data-toggle="collapse" class="titulo">
                                    <span><i class="fa fa-filter"></i> Filtro (clique para recolher)</span>
                                </a>
                            </div>
                            <div class="panel-collapse collapse in" id="FiltroConselheirosReinos">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                        </div>
                                        <div class="col-md-3">
                                            <!-- Botões -->
                                            <div align="center" class="form-group" style="width:100%; margin-top:-40px;">
                                                <div class="col-md-6">
                                                    <button onclick="showConselheiros()" type="button" class="btn btn-sm btn-default"><span class="fa fa-filter"></span> Exibir</button>
                                                </div>
                                                <?php
                                                    // Verifica se é membro do grupo de Administradores do IGOT para incluir o botão Novo
                                                    if($MemberOf->admin('igot')) { echo '
                                                        <div class="col-md-6">
                                                            <button onClick="NewConselheiro()" type="button" class="btn btn-sm btn-default btnNew" id="btnNew"><span class="glyphicon glyphicon-plus"></span> Novo</button>
                                                        </div>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Patente de Reino | Função no SQUAD</label>
                                                <select class="form-control select2" multiple="multiple" id="conselheiros_patente" style="width:100%;">
                                                    <?php echo $optPatentes; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Divisão | Área</label>
                                                <select class="form-control select2" multiple="multiple" id="conselheiros_exercito" name="conselheiros_exercito" style="width:100%;">
                                                    <option value="<?php echo $_SESSION['igot']['Guerreiro']['idExercito'] ?>"> </option>
                                                    <?php echo $optExercitos; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Guerreiro</label>
                                                <select class="form-control select2" multiple="multiple" id="conselheiros_conselheiro" name="conselheiros_conselheiro" style="width:100%;">
                                                    <?php echo $optConselheiros; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="width: 100%;">
                                                <label>Reino | SQUAD</label>
                                                <select class="form-control select2" multiple="multiple" id="conselheiros_reino" style="width:100%;">
                                                    <?php echo $optReinos; ?>
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
                        <table class="table table-bordered table-striped dataTable" id="tblConselheiros">
                            <thead id="thConselheiros">
                                <tr class="tblTitleRow">
                                    <th class="sorting datagrid" onclick="Sort(this, 'thConselheiros', 'showConselheiros')" col="Patente">Patente no Reino | Função no SQUAD</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thConselheiros', 'showConselheiros')" col="Exercito">Divisão | Área</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thConselheiros', 'showConselheiros')" col="Conselheiro">Guerreiro</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thConselheiros', 'showConselheiros')" col="Reino">Reino | SQUAD</th>
                                    <th class="sorting datagrid" onclick="Sort(this, 'thConselheiros', 'showConselheiros')" col="Descricao">Descrição da Patente no Reino</th>
                                    <th class="datagrid">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="row_position" id="tbodyConselheiros">
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
            // Cascading Dropbox (Exercitos -> Guerreiros)
            $('#conselheiros_exercito').change(function(){
                if($('#conselheiros_exercito').val() != ""){
                    $('#conselheiros_conselheiro').load('config/options.php?return=guerreiros&exercito='+$('#conselheiros_exercito').val());
                } else {
                    $('#conselheiros_conselheiro').load('config/options.php?return=guerreiros');
                }
            });
        });

        // Carrega os Conselheiros dos Reinos com base nos filtros selecionados
        function showConselheiros(sort="Patente, Conselheiro, Reino"){
            //var rows = document.getElementById("tbodyConselheiros").getElementsByTagName("tr").length;
            var filtro = ""; // Variável parâmetros, filtros a serem aplicados na Query
            // Verifica os filtros selecionados
            if($('#conselheiros_exercito').val() != ""){
                filtro += '&exercito='+$('#conselheiros_exercito').val();
            }
            if($('#conselheiros_conselheiro').val() != ""){
                filtro += '&guerreiro='+$('#conselheiros_conselheiro').val();
            }
            if($('#conselheiros_reino').val() != ""){
                filtro += '&reino='+$('#conselheiros_reino').val();
            }
            if($('#conselheiros_patente').val() != ""){
                filtro += '&patente='+$('#conselheiros_patente').val();
            }
            
            // Carrega os resultados com base no Filtro
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tbodyConselheiros").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", 'config/results.php?return=ConselheirosDosReinos&type=DataGrid&sort='+sort+filtro, true);
            xmlhttp.send();

            // Exibe o botão Novo se oculto
            $('#Conselhos').find($(".btnNew")).show();
        }
    </script>
    <!-- Script para edição dos itens no Datagrid -->
    <script>
        // Função do botão Novo - Adiciona nova linha à tabela
        function NewConselheiro(){
            var postURL = "'admin/ajax/updConselheiros.php'"; // Endereço da página com as funções SQL desta Base de Dados / Tabela
            var pagPart = $('#Conselhos'); // Restringe os objetos em uma parte específica da página
            pagPart.find($("#btnNew")).hide(); // Oculta o botão Novo
            // HTML da Nova Linha
            var newRow = 
                '<tr class="ui-sortable-handle" name="NewRecord">'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Patente"></span>'
                        +'<select class="form-control select2 editInput Patente" name="idPatente"><?php echo $optPatentes ?></select>'
                    +'</td>'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewAlways Exercito"></span>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Conselheiro"></span>'
                        +'<select class="form-control select2 editInput Guerreiro" name="idConselheiro"><?php echo $optConselheiros ?></select>'
                    +'</td>'
                    +'<td class="datagrid">'
                        +'<span class="viewOnly Reino"></span>'
                        +'<select class="form-control select2 editInput Reino" name="idReino"><?php echo $optReinos ?></select>'
                    +'</td>'
                    +'<td class="datagrid" align="center">'
                        +'<span class="viewAlways Descricao"></span>'
                    +'</td>'
                    +'<td class="datagrid actions" align="center">'
                        +'<button onClick="EditConselheiro(this.id)" id="btnEdit" type="button" class="btn btn-sm btn-default btnEdit" style="float:none; display:none;"><span class="glyphicon glyphicon-pencil"></span></button>'
                        +'<button onClick="UpdateConselheiro(this.id)" id="btnSave" type="button" class="btn btn-sm btn-success btnSave" style="float:none; display:none;"><span class="fa fa-save"></span></button>'
                        +'<button onClick="Delete(this.id, Conselhos)" id="btnDelete" type="button" class="btn btn-sm btn-default btnDelete" style="float:none; display:none;"><span class="glyphicon glyphicon-trash"></span></button>'
                        +'<button onClick="Remove(this.id, Conselhos, '+postURL+')" id="btnRemove" type="button" class="btn btn-sm btn-danger btnRemove" style="float:none; display:none;"><span class="fa fa-remove"></span></button>'
                        +'<button onclick="SaveConselheiro()" id="btnSaveNew" type="button" class="btn btn-sm btn-success btnSaveNew" style="float:none;"><span class="fa fa-save"></span></button>'
                        +'<button onclick="Cancel(this.id, Conselhos)" id="btnCancelNewRecord" type="button" class="btn btn-sm btn-default btnCancel" style="float:none; margin-left:3px;"><span class="fa fa-undo"></span></button>'
                    +'</td>'
                +'</tr>'
            ;
            // Adiciona a nova Linha no topo da Tabela
            $(newRow).prependTo($("#tbodyConselheiros"));
            // Identificação da nova linha na tabela
            var trObj = pagPart.find($("[name='NewRecord']")); // Variável para resumo na identificação da linha corrente
            // Formata os Combobox - Select2
            trObj.find('.select2').select2();
        }
        // Função do botão Salvar - Insere um novo registro
        function SaveConselheiro(){
            var pagPart = $('#Conselhos'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='NewRecord']")); // Variável para resumo na identificação da linha corrente
            var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
            $.ajax({
                url:'admin/ajax/updConselheiros.php',
                type:'POST',
                dataType: "json",
                data:'action=insert&'+inputData,
                success:function(response){
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewAlways.Descricao").text(response.data.Exercito);
                    trObj.find(".viewAlways.Exercito").text(response.data.Exercito);
                    trObj.find(".viewOnly.Conselheiro").text(response.data.Conselheiro);
                    trObj.find(".viewOnly.Reino").text(response.data.Reino);
                    trObj.find(".viewOnly.Patente").text(response.data.Patente);
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
        function EditConselheiro(btnID){
            var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            var pagPart = $('#Conselhos'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='"+ID+"']")); // Variável para resumo na identificação da linha corrente
            // Inverte a exibição dos campos de Visualização pelos de Edição e do botão Editar pelo Salvar
            trObj.find(".viewOnly").hide(); // Oculta os campos de visualização
            trObj.find(".editInput").show(); // Exibe os campos para Edição
            trObj.find(".btnEdit").hide(); // Oculta o botão Editar
            trObj.find(".btnDelete").hide(); // Oculta o botão Deletar
            trObj.find(".btnSave").show(); // Exibe o botão Salvar
            trObj.find(".btnCancel").show(); // Exibe o botão Cancelar
            // Adiciona as opções à combobox
            trObj.find(".editInput.Guerreiro").append('<?php echo $optConselheiros; ?>');
            trObj.find(".editInput.Reino").append('<?php echo $optReinos; ?>');
            trObj.find(".editInput.Patente").append('<?php echo $optPatentes; ?>');
            // Formata os Combobox - Select2
            trObj.find('.select2').select2();

        }
        // Função do botão Salvar - Atualiza registro existente
        function UpdateConselheiro(btnID){
            var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
            var pagPart = $('#Conselhos'); // Restringe os objetos em uma parte específica da página
            var trObj = pagPart.find($("[name='"+ID+"']")); // Variável para resumo na identificação da linha corrente
            var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados
            $.ajax({
                url:'admin/ajax/updConselheiros.php',
                type:'POST',
                dataType: "json",
                data:'action=update&id='+ID+'&'+inputData,
                success:function(response){
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewOnly.Conselheiro").text(response.data.Conselheiro);
                    trObj.find(".viewOnly.Reino").text(response.data.Reino);
                    trObj.find(".viewOnly.Patente").text(response.data.Patente);
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