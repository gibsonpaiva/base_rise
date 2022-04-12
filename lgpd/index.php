<?php
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();

    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    $guerreiro = $db_igot->getGuerreiros($_SESSION['user']['id']);
    $_SESSION['igot']['Guerreiro'] = $guerreiro[0];
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Favicon -->
        <link rel="icon" href="/igot/img/torre1.ico" />
        <!-- Titulo -->
        <title>IGOT</title>
		<?php include_once "../frames/head.php"; ?>
        <!-- autoNumeric Script -->
        <script src="/stylesheet/autoNumeric/autoNumeric.js" type=text/javascript></script>
        <!-- iCheck -->
        <script src="/stylesheet/AdminLTE/2.4.5/plugins/iCheck/icheck.min.js"></script>
        <!-- Select2 Script -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
        <!-- DataTable Script -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- ChartJS -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/chart.js/Chart.js"></script>
        <!-- FLOT CHARTS -->
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.js"></script>
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.resize.js"></script>
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.pie.js"></script>
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.categories.js"></script>
        <!-- Datagrid Custom Script -->
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
                <!-- Título da Página -->
                <section class="content-header">                    
                   
                    <h4><b>POLÍTICAS E PRIVACIDADE</b></h4>
                   
                    <!-- Bredcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> HOME</a></li>
                        <li class="active">LGPD</li>
                    </ol>
                </section>

                <!-- Conteúdo da Página -->
                <section class="content">
                    <!-- Caixa Principal -->
                    <div class="box">
                        <!-- Primeira linha de conteúdo -->
                        <div class="row">
                            <!-- Caixa de Conteúdo -->
                            <div class="col-xs-12">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#Politicas" data-toggle="tab">POLÍTICAS</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <?php
                                            /*** Guias ***/
                                            // Políticas
                                            include_once "frames/politicas.php";
                                            // Privacidade
                                            include_once "frames/privacidade.php";                                  
                                        ?>    
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
            <?php include_once "frames/controlpanel.php"; ?>

            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
    </body>    
</html>

