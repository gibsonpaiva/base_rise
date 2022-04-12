    <?php
        // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
        if(session_id() == '') { session_start(); }
        
    ?>

<!DOCTYPE html>
<html>	
    <head>  
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Favicon -->
        <link rel="icon" href="/igot/img/torre1.ico" />
        <!-- Titulo -->
        <title>RISE</title>
        <?php include_once "../frames/head.php"; ?>
        <!-- Select2 Script -->
        <script src="/../stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
        <!-- DataTable Script -->
        <script src="/../stylesheet/AdminLTE/2.4.5/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/../stylesheet/AdminLTE/2.4.5/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- Carrega os componentes Select2 -->
        <script>
            $(document).ready(function() {
                // Select2
                $('.select2').select2();
            });
        </script>
        <!-- DataGrid Custom Script -->
        <script src="/stylesheet/others/datagrid.js"></script>
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
                                    <p class="conteudo"> 
                                        Aqui você pode encontrar e baixar arquivos de todo o Rise. 
                                    </P>
                                    <hr class='featurette-divider'>                                                     
                                </div>                           
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-xs-6">                                       
                                            <div class="small-box bg-aqua">
                                                <div class="inner">
                                                    <h4 style="font-size: 25px; !important;"><b> Certificados</b></h4>
                                                    <p>Download de Certificados, filtro por Guerreiro, Tipo de Evento, Aliança, Categoria, Data e etc.</p>
                                                </div>
                                                
                                                    <a href="filtroCertificado.php?id=1" class="small-box-footer">
                                                    clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                    </a>
                                                </div>
                                            </div>                                       
                                            <div class="col-lg-6 col-xs-6">                                        
                                                <div class="small-box bg-red">
                                                    <div class="inner">
                                                        <h4 style="font-size: 25px; !important;"><b> Busca Avançada</b></h4>
                                                        <p>Download de todos os arquivos, análise mais completa podendo fazer mais de um filtro de uma vez. </p>
                                                    </div>
                                                   
                                                <a href="buscaavancada.php" class="small-box-footer">
                                                clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-xs-6">                                       
                                            <div class="small-box bg-maroon">
                                                <div class="inner">
                                                    <h4 style="font-size: 25px; !important;"><b> Modelos de Documentos(Em Breve)</b></h4>
                                                    <p>Download de Certificados, filtro por Guerreiro, Tipo de Evento, Aliança, Categoria, Data e etc.</p>
                                                </div>
                                               
                                                    <a href="#" class="small-box-footer">
                                                    clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                    </a>
                                                </div>
                                            </div>                                       
                                            <div class="col-lg-6 col-xs-6">                                        
                                                <div class="small-box bg-purple">
                                                    <div class="inner">
                                                        <h4 style="font-size: 25px; !important;"><b> Notas Fiscais</b></h4>
                                                        <p>Download de Notas Fiscais, filtro por Guerreiro, Tipo de Evento, Aliança, Categoria, Data e etc.</p>
                                                    </div>
                                                  
                                                <a href="filtroCertificado.php?id=5" class="small-box-footer">
                                                clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-xs-6">                                       
                                            <div class="small-box bg-black">
                                                <div class="inner">
                                                    <h4 style="font-size: 25px; !important;"><b> Recibos</b></h4>
                                                    <p>Download de Recibos, filtro por Guerreiro, Tipo de Evento, Aliança, Categoria, Data e etc.</p>
                                                </div>
                                                
                                                <a href="filtroCertificado.php?id=4" class="small-box-footer">
                                                clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </div>
                                        </div>                                       
                                        <div class="col-lg-6 col-xs-6">                                       
                                            <div class="small-box bg-orange">
                                                <div class="inner">
                                                    <h4 style="font-size: 25px; !important;"><b> Vouchers</b></h4>
                                                    <p>Download de Vouchers, filtro por Guerreiro, Tipo de Evento, Aliança, Categoria, Data e etc.</p>
                                                </div>
                                               
                                                <a href="filtroCertificado.php?id=2" class="small-box-footer">
                                                clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </div>
                                        </div>                                        
                                        <div class="col-lg-6 col-xs-6">                                       
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h4 style="font-size: 25px; !important;"><b> Base de Conhecimentos (Em Breve)</b></h4>
                                                    <p>Download de arquvivos da base de conhecimentos, filtro por Guerreiro, Tipo de Evento, Aliança, Categoria, Data e etc.</p>
                                                </div>
                                                
                                                <a href="#" class="small-box-footer">
                                                clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </div>
                                        </div>                                        
                                        <div class="col-lg-6 col-xs-6">                                       
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                    <h4 style="font-size: 25px; !important;"><b> Evidencias</b></h4>
                                                    <p>Download de Evidencias, filtro por Guerreiro, Tipo de Evento, Aliança, Categoria, Data e etc.</p>
                                                </div>                                               
                                                <a href="filtroCertificado.php?id=3" class="small-box-footer">
                                                clique aqui <i class="fa fa-arrow-circle-right"></i>
                                                </a>
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
    </body>
</html>
 