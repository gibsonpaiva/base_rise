<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    
    // Inclue objetos referentes a bases de dados
    include_once "../config/db.php";
    // Instancia os objetos referente a base de dados    
    $db_rise = new RISE();
    //Obtem option de categorias do topico    
    $itens = $db_rise->getOptCategoriaTopicos();
    $optCategoria = "";
    for ($i=0; $i<count($itens); $i++){        
        $optCategoria .=         
        '
            <option class="select2" value="'.$itens[$i]['idTipoTopico'].'">'.$itens[$i]['TipoTopico'].'</option>
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
         <!-- Card perfil atributos-->
        <?php include_once "../frames/head.php"; ?>
        <!-- CKEditor -->
        <script src="../stylesheet/AdminLTE/2.4.5/bower_components/ckeditor/ckeditor.js"></script>
        <!-- Select2 Script -->
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
                                    <h3 class="box-title">Criar novo Tópico</h3>
                                </div>
                                <form action="../admin/ajax/updForum.php" method="post">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Assunto</label>
                                            <input type="text" class="form-control"  name="assunto" velue="" id="newtopico" placeholder="Assunto do Tópico">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Categoria</label>
                                            <select class="editInput form-control select2" name="NewCategoria" id="idNewCategoria">                                                                
                                                <?php echo $optCategoria; ?>                
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Postagem</label>
                                            <div class="form-group">
                                                <textarea class="editInput form-control form-field" rows="5" id="postagem" velue="" name="postagem" placeholder="Digite sua Postagem"></textarea>
                                            </div>
                                        </div>                                                                              
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                </form>
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
          
    </body>
    <script>
        // Formata os componentes da página
        $(document).ready(function(){
            // Formata o CKeditor
            CKEDITOR.replace('postagem');
        });           
    </script>        
</html>