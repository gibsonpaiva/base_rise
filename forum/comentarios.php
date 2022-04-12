<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    
    // Inclue objetos referentes a bases de dados
    include_once "../config/db.php";
    // Instancia os objetos referente a base de dados
    $db_rise = new RISE();
    $db_filebox = new FILEBOX();
    
    $topico = $db_rise->getTopicos($_GET['id']);
    
    $repostas_html="";
    $itens = $db_rise->getRespostas($_GET['id']);
    for ($i=0; $i<count($itens); $i++){
        $repostas_html .= '
            <div class="box box-widget" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);" id="Resposta'.$itens[$i]['id'].'">
                <div class="box-header with-border" style="background-color: #f7f7f7;">
                    <div class="user-block" style="margin-top:10px;">
                        <img class="img-circle" src="'.$db_filebox->loadImagem(2, $itens[$i]["idRegistradoPor"]).'" alt="User Image">
                        <span class="username">'.$itens[$i]["RegistradoPor"].'</span>
                        <span class="description">Data: '.$itens[$i]["RegistradoEm"].'</span>
                    </div>
                    <div class="box-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                            </button>
                            ';
                            if($_SESSION['igot']['Guerreiro']['idUsuario'] == $itens[$i]['idRegistradoPor']){
                            $repostas_html .= '    
                                <ul class="dropdown-menu">
                                    <li><a onclick="EditResposta('.$itens[$i]['id'].')" href="#">Editar</a></li>
                                    <li><a onclick="ExcluirResposta('.$itens[$i]['id'].')" href="#">Excluir</a></li>
                                </ul>
                            ';
                            }
                            $repostas_html .= '  
                        </div>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--<a href="#" type="button" class="fa fa-thumbs-o-up dark-green btn-sm px-2 pull-right"
                            style="border: 2px solid #388e3c!important; background-color: transparent!important; color: #388e3c !important; display: inline-block;">
                           <span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0</font></font></span>
                        </a>

                       <a href="#" type="button" class=" fa fa-thumbs-o-down btn-sm px-2 pull-right" style="border: 2px solid #ff3547!important; background-color: transparent!important; color: #ff3547!important;">
                            <span class="value"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0</font></font></span>
                       </a>-->
                    </div>
                </div>
                <div class="box-body" id="textoResposta'.$itens[$i]['id'].'">'.$itens[$i]["Resposta"].'</div>
            </div>
        ';
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
         <!-- CKEditor -->
         <script src="../stylesheet/AdminLTE/2.4.5/bower_components/ckeditor/ckeditor.js"></script>
	</head> 
    
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            <!-- Cabeçalho -->
            <?php include_once "../frames/header.php"; ?>

            <!-- Barra Lateral -->
            <?php include_once "../frames/sidebar.php"; ?>

            <div class="content-wrapper">
                <section class="content-header">                    
                    <img src="../igot/img/game2.png" style="margin-top:-13px; margin-bottom:-10px; max-width:165px;">
                    <!-- Bredcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> HOME</a></li>
                        <li class="active">FÓRUM</li>
                    </ol>
                </section>

                <section class="content">
                    <div class="row" >
                        <div class="col-md-8">
                            <!-- Tópico -->
                            <div class="box box-default" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div class="box-header with-border" style="background-color:#f7f7f7; padding-bottom: 0px; !important;">
                                    <i class="fa fa-globe"></i>
                                    <h3 class="box-title"><?php echo $topico[0]['Assunto']; ?></h3>
                                    <h5 class="pull-right">Data: <?php echo $topico[0]['RegistradoEm']; ?> <h5>                                                                
                                </div>                                    
                                <div class="box-body">                                    
                                    <?php echo $topico[0]['Postagem']; ?>
                                </div>                            
                                <div class="box-footer" style="background-color:#f7f7f7; padding-bottom: 5px;  !important; padding-top: 5px; ">
                                    <button id="buttonResposta" onclick="Responder()" type="button" class="btn btn-default btn-sm pull-right"><i class="fa fa-reply"></i> Responder</button>
                                    <a href="topicos.php" class="btn btn-default btn-sm pull-left"><i class="glyphicon glyphicon-menu-left"></i> Voltar</a>
                                </div>        
                            </div>
                            <!-- Respostas -->
                            <section id="Respostas">
                                <?php echo $repostas_html ?>
                            </section>

                             <!-- Modal de editar comentario-->
                             <div class="modal fade" id="editar" style="display:none;">
                                <div class="modal-dialog modal-megamenu" align="center" style="height:700px;">
                                    <div class="modal-content" style="width:800px; !important;">                                    
                                        <div class="modal-header" align="center" style="height:60px;" >
                                            <button class="close" aria-label="Close" type="button" data-dismiss="modal"><span class="fa fa-times" aria-hidden="true"></span></button>  
                                            <h4> EDITAR COMENTÁRIO </h4>
                                        </div>                                            
                                        <div class="modal-body" align="left">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <textarea class="editInput form-control form-field" rows="5"  name="FormEditResposta"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" align="center">
                                            <button data-dismiss="modal" type="button" class="btn btn-default btnCancel"><span class="fa fa-undo"></span> Cancelar</button>
                                            <button onclick="UpdateResposta()" id="btnUpdate" type="button" class="btn btn-success btnUpdate"><span class="fa fa-save"></span> Salvar</button>
                                        </div>                                                                                                                       
                                    </div>
                                </div>
                            </div>

                            <!-- Form Respostas -->
                            <div class="box" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="box-header with-border" style="background-color: #f7f7f7;" >
                                            <h3 class="box-title">Responder</h3>
                                        </div>
                                        <form action="../admin/ajax/updForumResposta.php" method="post">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <textarea class="editInput form-control form-field" rows="5" id="FormResposta" name="Resposta" value="" placeholder="Digite sua resposta ou comentário"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <button onclick="SalvarResposta(<?php echo $_GET['id']; ?>)" type="button" class="btn btn-danger" style="background-color: #c9302c;">Enviar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card do Autor -->
                        <div class="col-md-4">
                            <div class="box box-widget widget-user" style="position: fixed; width: 25%; box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div class="widget-user-header" style="background-color: #BE3219;">
                                    <h3 class="widget-user-username" style="color: #fff"> <?php echo $topico[0]['RegistradoPor']; ?> </h3>
                                    <h5 class="widget-user-desc" style="color: #fff"><?php echo $topico[0]['Departamento']; ?></h5>
                                </div>
                                <div class="widget-user-image">
                                    <img class="img-circle" src="<?php echo $db_filebox->loadImagem(2, $topico[0]['idRegistradoPor']);?>" alt="User Avatar">
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header"> - </h5>
                                                <span class="description-text">RESPOSTAS</span>
                                            </div>                                        
                                        </div>                                        
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header"> - </h5>
                                                <span class="description-text">LIKES</span>
                                            </div>                                        
                                        </div>                                        
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header"> - </h5>
                                                <span class="description-text">TÓPICOS</span>
                                            </div>                                        
                                        </div>                                        
                                    </div>                               
                                </div>
                                <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                                    SOBRE O AUTOR
                                </h4>
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

        <script>
            // Formata os componentes da página
            $(document).ready(function(){
                // Formata o CKeditor de Nova Resposta
                CKEDITOR.replace('FormResposta');
                // Formata o CKeditor de Edição de Resposta
                CKEDITOR.replace('FormEditResposta');
            });
           
            
            // Exibe um formulário para Reposta
            function Responder() {
                // Rola a página até o final
                window.scrollTo(0, document.body.scrollHeight);
            }

            // Salva a Resposta do Usuário
            function SalvarResposta(idTopico) {
                var inputData = new FormData(); // Variável para armazenamento dos dados do Formulário
                inputData.append('action', 'insert'); // Concatena a ação a ser realizada
                inputData.append('idTopico', idTopico); // Concatena o ID do Tópico                
                inputData.append('Resposta', CKEDITOR.instances.FormResposta.getData()); // Concatena a Resposta
                $.ajax({
                    url: '../admin/ajax/updForumResposta.php',
                    type:'POST',
                    dataType: 'json',
                    data: inputData,
                    cache: false,
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function(response){                        
                        /*$("#Respostas").append(''                            
                            +'<div class="box box-widget" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">'
                                +'<div class="box-header with-border" style="background-color: #f7f7f7;">'
                                    +'<div class="user-block" style="margin-top:10px;">'
                                        +'<img class="img-circle" src="<?php echo $db_filebox->loadImagem(2, $_SESSION['user']['id']);?>" alt="User Image">'
                                        +'<span class="username"><?php echo $_SESSION['igot']['Guerreiro']['Nome']; ?></span>'
                                        +'<span class="description">Data: '+response.data.RegistradoEm+'</span>'
                                    +'</div>'
                                    +'<div class="box-tools">'                                    
                                        +'<div class="btn-group">'
                                            +'<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                                +'<span class="caret"></span>'
                                            +'</button>'
                                            +'<ul class="dropdown-menu">'
                                                +'<li><a href="#" data-id="#editar" data-toggle="modal" data-target="#editar">Editar</a></li>'
                                                +'<li><a onclick="" href="#">Excluir</a></li>'
                                            +'</ul>'                                           
                                        +'</div>'
                                        +'<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>'
                                        +'<!--<a href="#" type="button" class="fa fa-thumbs-o-up dark-green btn-sm px-2 pull-right"'
                                            +'style="border: 2px solid #388e3c!important; background-color: transparent!important; color: #388e3c !important; display: inline-block;">'
                                        +'<span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0</font></font></span>'
                                        +'</a>'
                                        +'<a href="#" type="button" class=" fa fa-thumbs-o-down btn-sm px-2 pull-right" style="border: 2px solid #ff3547!important; background-color: transparent!important; color: #ff3547!important;">'
                                            +'<span class="value"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0</font></font></span>'
                                        +'</a>-->'
                                    +'</div>'                                  
                                +'</div>'
                                +'<div class="box-body">'+response.data.Resposta+'</div>'                                            
                            +'</div>'                            
                        );*/

                        //o botao collapse nao pegar no javascript. 
                        //window.location.reload()
                        // Limpa o formulário da Respostas
                        //CKEDITOR.instances.FormResposta.setData('');
                        alert('Resposta inserida com sucesso!!');
                        location.reload();
                        
                    }
                });
            }

            // Excluir a Resposta Selecionada
            function ExcluirResposta(idResposta) {
                var inputData = new FormData(); // Variável para armazenamento dos dados do Formulário
                inputData.append('action', 'delete'); // Concatena a ação a ser realizada
                inputData.append('idResposta', idResposta); // Concatena o ID da Resposta
                if (confirm('Deseja excluir seu comentario?')) { 
                $.ajax({
                    url: '../admin/ajax/updForumResposta.php',
                    type:'POST',
                    dataType: 'json',
                    data: inputData,
                    cache: false,
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success:function(response){
                    location.reload();
                    alert('comentário excluido com sucesso!');
                          
                        // Remove a DIV da Resposta excluída
                        //$('#Resposta' + idResposta).remove();
                        //alert('Comentário excluido com sucesso!');
                    }
                });
                } 
                else{

                }               
            }
            
            // Exibe o formulário para edição da Resposta
            function EditResposta(idResposta){
                CKEDITOR.instances.FormEditResposta.setData(document.getElementById("textoResposta"+idResposta).innerHTML); // Copiando a resposta para edicação no CKeditor
                //CKEDITOR.instances.FormEditResposta.setData($("#textoResposta"+idResposta).text()); // Copiando a resposta para edicação no CKeditor
                $("#btnUpdate").attr("idResposta", idResposta); // guardando o idResposta no botao btnUpdate para uso futuro
                $("#editar").modal('show'); // carrega modal para editar a resposta
            }

            // Função do botão Salvar - Insere um novo registro
            function UpdateResposta(idResposta){
                var idResposta = $("#btnUpdate").attr("idResposta");
                var inputData = new FormData(); // Variável para armazenamento dos dados do Formulário   
                var frObj = $('#editar'); // Variável para resumo na identificação do Corpo do Modal                
                inputData.append('action', 'update'); // Concatena a ação a ser realizada                 
                inputData.append('FormEditResposta', CKEDITOR.instances.FormEditResposta.getData());
                inputData.append('idResposta', idResposta); // Concatena o ID da Resposta
                $.ajax({
                    url:'../admin/ajax/updForumResposta.php',
                    type:'POST',
                    dataType: "json",
                    data: inputData,
                    cache: false,
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success:function(response){                                                
                        $("#editar").modal('hide');
                        alert('Comentario alterado com sucesso!!');
                        location.reload();
                    }
                });
            }           
        </script>
    </body>
</html>