<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    
    // Inclue objetos referentes a bases de dados
    include_once "../config/db.php";
    // Instancia os objetos referente a base de dados
   
    $db_rise = new RISE();
    
    $itens = $db_rise->getTopicos();

    $topicos = "";
    for ($i=0; $i<count($itens); $i++){        
        $topicos .= 
        
        '<tr>
            <td class="mailbox-name"> '.$itens[$i]['RegistradoPor'].'  </td>
            <td id="edit'.$itens[$i]['id'].'" class="mailbox-subject"><b><a style="color: #000000" href="comentarios.php?id='.$itens[$i]['id'].'"> '.$itens[$i]['Assunto'].' </a></b> </td>
            <td id="editOpt'.$itens[$i]['id'].'" '.$itens[$i]['idTipoTopico'].' class="mailbox-date"> '.$itens[$i]['TipoTopico'].'</td>
            <td id="id'.$itens[$i]['id'].'" class="mailbox-date" style= "display:none;">'.$itens[$i]['Postagem'].'</td>           
            <td class="mailbox-date">'.$itens[$i]['RegistradoEm'].'</td>                                                    
            <td class="mailbox-date" align="center"> '.$itens[$i]['qtdeRespostas'].'</td>
            <!--<td class="mailbox-date">
                <a href="#" type="button" class="fa fa-thumbs-o-up dark-green btn-sm px-2"
                    style="border: 2px solid #388e3c!important; background-color: transparent!important; color: #388e3c!important; display: inline-block;">
                    <span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0</font></font></span>                                            
                </a>
                <a href="#" type="button" class="" fa fa-thumbs-o-down danger btn-sm px-2" style="border: 2px solid #ff3547!important; background-color: transparent!important; color: #ff3547!important;">
                    <span class="value"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0</font></font></span>                               
                </a>  
            </td>-->
            <td class="mailbox-name" align="center">
        ';
            
             if($_SESSION['igot']['Guerreiro']['idUsuario'] == $itens[$i]['idRegistradoPor']){
                $topicos .= '
                <button onclick="excluirTopico('.$itens[$i]['id'].')" type="button" id="topicos"  class="btn btn-default btn-sm"><i class="fa fa-remove"></i></button>
                <button onclick="EditTopicos('.$itens[$i]['id'].')" type="button" id="editTopicos" class="btn btn-warning btn-sm edit"><i class="glyphicon glyphicon-edit"></i></button>
                ';
             }
            
             $topicos .= '
            </td>
            
        </tr>';                 
    }
    
    
    //Obtem option de categorias do topico 
    $itens = $db_rise->getOptCategoriaTopicos();
    $optCategoria = "";
    for ($i=0; $i<count($itens); $i++){        
        $optCategoria .=         
        '
            <option value="'.$itens[$i]['idTipoTopico'].'">'.$itens[$i]['TipoTopico'].'</option>
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
        <!-- Rotating Card -->
        <link href="../stylesheet/others/rotating-card.css" rel="stylesheet" />

        <!-- Card perfil atributos-->
        <?php include_once "../frames/head.php"; ?>

         <!-- CKEditor -->
         <script src="../stylesheet/AdminLTE/2.4.5/bower_components/ckeditor/ckeditor.js"></script>

         <script src="../stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>

         <script>
            $(document).ready(function() {
                // Select2
                $('.select2').select2();
            });
        </script>
	</head>
    
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            <!-- Cabeçalho -->
            <?php include_once "../frames/header.php"; ?>

            <!-- Barra Lateral -->
            <?php include_once "../frames/sidebar.php"; ?>

            <div class="content-wrapper">
                <!-- Conteúdo da Página -->
                <section class="content">
                    <div class="row">                        
                        <div class="col-md-12">
                            <div class="box box" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div class="box-header with-border" style="background-color: #f7f7f7;">
                                        <br>                                    
                                        <img width="100px" align="left" height="auto" alt="Guerreiro" src="../igot/img/eventos-2.png">
                                        <br><h4><p class="titulo">FÓRUM</p></h4>
                                        <p class="conteudo">
                                            Nesse espaço, os guerreiros podem se ajudar e opinar sobre os tópicos específicos.
                                        </P>
                                        <hr class='featurette-divider'>
                                    <!--<div class="box-tools pull-right">
                                        <div class="has-feedback">
                                            <input type="text" class="form-control input-sm" placeholder="Search Mail">
                                            <span class="glyphicon glyphicon-search form-control-feedback"></span
                                        </div>
                                    </div>-->                            
                                </div>                           
                                <div class="box-body">
                                    <div class="mailbox-controls">                               
                                        <div class="btn-group">
                                        <a href="criartopico.php"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></button></a>                                            
                                        </div> 
                                        <a href="topicos.php"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button></a>                               
                                        
                                        <!--<div class="pull-right"> 1-50/200
                                            <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                                            </div>                                
                                        </div>-->                              
                                    </div>
                                    <div class="table-responsive mailbox-messages">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Autor</th>
                                                    <th scope="col">Titulo</th>
                                                    <th scope="col">Categoria</th>                                                    
                                                    <!--<th scope="col">Anexo</th>-->
                                                    <th scope="col">Data</th>
                                                    <th scope="col"style=" text-align: center";>Respostas</th>
                                                    <!--<th scope="col">Qualificação</th>-->
                                                    <th scope="col" style="text-align: center;" >Açoês</th>                                                    
                                                </tr>
                                            </thead>                                        
                                            <tbody>
                                                <?php echo $topicos; ?>                                                                                             
                                            </tbody>
                                        </table>                            
                                    </div>                         
                                </div>
                            </div>                        
                        </div>                      
                    </div>                    
                </section>
            </div>
            <!-- Modal de editar Topico-->
            <div class="modal fade" id="editarTopico" style="display:none;">
                <div class="modal-dialog modal-megamenu" align="center" style="height:700px;">
                    <div class="modal-content" style="width:800px; !important;">                                    
                        <div class="modal-header" align="center" style="height:60px;" >
                            <button class="close" aria-label="Close" type="button" data-dismiss="modal"><span class="fa fa-times" aria-hidden="true"></span></button>  
                            <h4> EDITAR TÓPICO </h4>
                        </div>                                            
                        <div class="modal-body" align="left">
                            <div class="row">                        
                                <div class="col-md-12">
                                    <form action="admin/ajax/updForum.php" method="post">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Assunto</label>
                                                <input type="text" class="form-control editInput"  name="EditAssunto"  id="EditNewTopico" placeholder="Assunto do Tópico">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Categoria</label>
                                                <input type="text" class="form-control editInput" name="EditNewOptCategoria" disabled   id="idEditNewOptCategoria">
                                                <select class="editInput form-control select2" name="NewCategoria" id="idNewCategoria">                                                                
                                                    <?php echo $optCategoria; ?>                
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Postagem</label>
                                                <div class="form-group">
                                                    <textarea class="editInput form-control form-field" rows="5"  name="FormEditTopico" placeholder="Digite sua Postagem"></textarea>
                                                </div>                                                    
                                            </div>                                                                              
                                        </div>                                            
                                    </form>
                                </div>        
                            </div>                    
                        </div>
                        <div class="modal-footer" align="center">
                            <button data-dismiss="modal" type="button" class="btn btn-default btnCancel"><span class="fa fa-undo"></span> Cancelar</button>
                            <button onclick="UpdateTopico()" id="btnUpdate" type="button" class="btn btn-success btnUpdate2"><span class="fa fa-save"></span> Salvar</button>
                        </div>                                                                                                                       
                    </div>
                </div>
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
                // Formata o CKeditor de Edição de Resposta
                CKEDITOR.replace('FormEditTopico');
            });

            // Excluir Topico Selecionado
            function excluirTopico(id) {
                var inputData = new FormData(); // Variável para armazenamento dos dados do Formulário
                inputData.append('action', 'delete'); // Concatena a ação a ser realizada
                inputData.append('id', id); // Concatena o ID da Resposta
                if (confirm('Deseja excluir seu topico?')) {                 
                $.ajax({
                    url: '../admin/ajax/updForumTopico.php',
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


            //pega valores da tabela e joga no modal de edicao
            $('body').on("click", ".edit", function() {
                $('#EditNewTopico').val($(this).parents('tr').find('td').eq(1).text());
                $('#idEditNewOptCategoria').val($(this).parents('tr').find('td').eq(2).text());
            });
          
            function EditTopicos(idTopico){
                CKEDITOR.instances.FormEditTopico.setData(document.getElementById("id"+idTopico).innerHTML); // Copiando a resposta para edicação no CKeditor
                $("#btnUpdate").attr('idTopico', idTopico); // guardando o idTopico no botao btnUpdate para uso futuro                 
                $("#editarTopico").modal('show'); // carrega modal para editar a resposta
                
            }

             // Função do botão Salvar - Insere um novo registro
            function UpdateTopico(){
                var id = $("#btnUpdate").attr("idTopico");                              
                var inputData = new FormData(); // Variável para armazenamento dos dados do Formulário   
                var frObj = $('#editarTopico'); // Variável para resumo na identificação do Corpo do Modal                
                inputData.append('action', 'update'); // Concatena a ação a ser realizada                
                inputData.append('Assunto', $("[name='EditAssunto']").val()); // Concatena
                inputData.append('idTipoTopico', $("[name='NewCategoria']").val()); // Concatena   
                                       
                inputData.append('FormEditTopico', CKEDITOR.instances.FormEditTopico.getData());
                inputData.append('idTopico', id); // Concatena o ID do Topico                
                $.ajax({
                    url:'../admin/ajax/updForumTopico.php',
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