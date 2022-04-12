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
        <!-- Carrega componentes como iCheck, Select2 e DataTable -->      
        <script>
            $(document).ready(function() {
                // iCheck
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red'
                });
                // Select2
                $('.select2').select2();
                // DataTable (Quadro de Medalhas)
                $('#tblQuadroDeMedalhas').DataTable({
                    "order": [[0, "asc"]],
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
                    <img src="/igot/img/game2.png" style="margin-top:-13px; margin-bottom:-10px; max-width:165px;">
                    <!-- Bredcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> HOME</a></li>
                        <li class="active">IGOT</li>
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
                                        <li class="active"><a href="#Regras" data-toggle="tab">REGRAS</a></li>
                                        <li><a href="#Dashboard" onclick="tabDashBoard()" data-toggle="tab">DASHBOARD</a></li>
                                        <li><a href="#Reinos" data-toggle="tab">REINOS</a></li>
                                        <li><a href="#Conselhos" data-toggle="tab">CONSELHOS</a></li>
                                        <li><a href="#Habilidades" data-toggle="tab">HABILIDADES</a></li>
                                        <li><a href="#QuadroDeMedalhas" onclick="tabQuadroDeMedalhas()" data-toggle="tab">MEDALHAS</a></li>
                                        <li><a href="#Eventos" data-toggle="tab">EVENTOS</a></li>
                                        <li><a href="#Conformidades" data-toggle="tab">CONFORMIDADES</a></li>
                                        <li><a href="#LinhaDoTempo" onclick="tabLinhaDoTempo()" data-toggle="tab">LINHA DO TEMPO</a></li>
                                        <li><a href="#CampoDeTreinamento" data-toggle="tab">CAMPO DE TREINAMENTO</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <?php
                                            /*** Guias ***/
                                            // Regras do Jogo
                                            include_once "frames/regras.php";
                                            // Estrutura dos Reinos
                                            include_once "frames/reinos.php";                                          
                                            // Dashboard
                                            include_once "frames/dashboard.php";
                                            // Conselhos
                                            include_once "frames/conselhos.php";
                                            // Guerreiros nas Torres
                                            include_once "frames/habilidades.php";
                                            // Quadro de Medalhas
                                            include_once "frames/quadro_medalhas.php";
                                            // Eventos
                                            include_once "frames/eventos.php";
                                            // Linha do Tempo
                                            include_once "frames/linha_tempo.php";
                                            // Conformidades
                                            include_once "frames/conformidades.php";
                                            // Campo de Treinamento
                                            include_once "frames/campo_treinamento.php";
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
        
        <!-- Page Scripts -->
        <script>
            // Carrega os Gráficos da TAB Dashboard
            function tabDashBoard() {
                // Carrega os Gráficos de Alianças
                updBarChart('AliancasBarChart');
                updKnobChart('AliancasKnobChart');
            }

            // Carrega os Quadros de Medalhas da TAB Medalhas
            function tabQuadroDeMedalhas() {
                var idExercito = null; // Inicia a variável idExercito
                idExercito = <?php echo $_SESSION['igot']['Guerreiro']['idExercito']; ?>; // Atribui o ID do Exército do Usuário corrente para priorizá-lo na exibição
                // Carrega os Quadro de Medalhas
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    content = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    content = new ActiveXObject("Microsoft.XMLHTTP");
                }
                // Popula a TAB Medalhas com os Quadros de Medalhas
                content.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("QuadrosDeMedalhas").innerHTML = this.responseText;
                    }
                };
                content.open("GET", 'config/results.php?return=QuadrosDeMedalhas&idExercito='+idExercito, true);
                content.send();
            }

            // Carrega os Eventos na TAB Linha do Tempo
            function tabLinhaDoTempo(pag) {
                if (pag === undefined) { pag = 0; } // Define um valor de Página padrão. Não especificado utiliza-se 0.
                // Carrega a Linha do Tempo
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    content = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    content = new ActiveXObject("Microsoft.XMLHTTP");
                }
                // Popula a TAB Medalhas com os Quadros de Medalhas
                content.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (pag === 0) {
                            document.getElementById("TimeLine").innerHTML = this.responseText;
                        } else {
                            $("#TimeLine").find(".button").remove(); // Remove o Botão para Carregar mais Eventos
                            $("#TimeLine").append(this.responseText); // Concatena os Eventos mais antigos
                        }
                    }
                };
                content.open("GET", 'config/results.php?return=LinhaDoTempo&pag='+pag, true);
                content.send();
            }
        </script>
    </body>    
</html>

