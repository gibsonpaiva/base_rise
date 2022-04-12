<?php
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
?>

<!DOCTYPE html>
<html>	
    <head>  
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Favicon -->
        <link rel="icon" href="img/torre1.ico" />
        <!-- Titulo -->
        <title>RISE</title>
        <?php include_once "../frames/head.php"; ?>
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
                    <!-- Quantitativo Geral -->
                    <div class="row">
                        <!-- Reinos -->
                        <div class="col-xs-6 col-lg-2">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3><?php echo count($db_igot->getReinos()); ?></h3>
                                    <p>REINOS</p>
                                </div>
                                <div class="icon">
                                    <img class="menu-icon" src="/igot/img/reino-branco-1.png" align="center" width="80px" height="80px">            
                                </div>
                            </div>
                        </div>
                        <!-- Castelos -->
                        <div class="col-xs-6 col-lg-2">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3><?php echo count($db_igot->getCastelos()); ?><sup style="font-size: 20px"></sup></h3>
                                    <p>CASTELOS</p>
                                </div>
                                <div class="icon" align="center">
                                    <img class="menu-icon" src="/igot/img/castelo-branco-1.png" align="center" width="80px" height="80px">
                                </div>
                            </div>
                        </div>
                        <!-- Alianças -->
                        <div class="col-xs-6 col-lg-2">
                            <div class="small-box bg-purple">
                                <div class="inner">
                                    <h3><?php echo count($db_igot->getAliancas()); ?></h3>
                                    <p>ALIANÇAS</p>
                                </div>
                                <div class="icon">
                                    <img class="menu-icon" src="/igot/img/aliancas-branco-1.png" align="center" width="80px" height="80px">            
                                </div>
                            </div>
                        </div>
                        <!-- Torres -->
                        <div class="col-xs-6 col-lg-2">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3><?php echo count($db_igot->getTorres()); ?></h3>
                                    <p>TORRES</p>
                                </div>
                                <div class="icon">
                                    <img class="menu-icon" src="/igot/img/torre-branca-1.png" align="center" width="80px" height="80px">
                                </div>
                            </div>
                        </div>
                        <!-- Exércitos -->
                        <div class="col-xs-6 col-lg-2">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3><?php echo count($db_igot->getExercitos()); ?></h3>
                                    <p>EXÉRCITOS</p>
                                </div>
                                <div class="icon">
                                    <img class="menu-icon" src="/igot/img/exercitos-branco-1.png" align="center" width="80px" height="80px">            
                                </div>
                            </div>
                        </div>
                        <!-- Guerreiros -->
                        <div class="col-xs-6 col-lg-2">
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3><?php echo count($db_igot->getGuerreiros()); ?></h3>
                                    <p>GUERREIROS</p>
                                </div>
                                <div class="icon">
                                    <img class="menu-icon" src="/igot/img/guerreiro-branco-1.png" align="center" width="80px" height="80px">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Gráfico - Força das Alianças (Medalhas por Alianças) -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <i class="fa fa-bar-chart-o"></i>
                                    <h3 class="box-title">Medalhas por Aliança</h3>
                                    <div class="box-tools pull-right">
                                        <!--button class="btn btn-default btn-sm" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-default btn-sm" type="button" data-widget="remove"><i class="fa fa-times"></i></button-->
                                        <button class="btn btn-default btn-sm" onclick="updBarChart('AliancasBarChart')" type="button"><i class="fa fa-refresh"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <div id="AliancasBarChart" style="padding: 0px; height: 300px; position: relative;"><canvas width="787" height="300" class="flot-base" style="left: 0px; top: 0px; width: 787px; height: 300px; position: absolute; direction: ltr;"></canvas><div class="flot-text" style="left: 0px; top: 0px; right: 0px; bottom: 0px; color: rgb(84, 84, 84); font-size: smaller; position: absolute;"><div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="left: 0px; top: 0px; right: 0px; bottom: 0px; position: absolute;"><div class="flot-tick-label tickLabel" style="left: 37px; top: 280px; text-align: center; position: absolute; max-width: 131px;">January</div><div class="flot-tick-label tickLabel" style="left: 168px; top: 280px; text-align: center; position: absolute; max-width: 131px;">February</div><div class="flot-tick-label tickLabel" style="left: 310px; top: 280px; text-align: center; position: absolute; max-width: 131px;">March</div><div class="flot-tick-label tickLabel" style="left: 447px; top: 280px; text-align: center; position: absolute; max-width: 131px;">April</div><div class="flot-tick-label tickLabel" style="left: 583px; top: 280px; text-align: center; position: absolute; max-width: 131px;">May</div><div class="flot-tick-label tickLabel" style="left: 714px; top: 280px; text-align: center; position: absolute; max-width: 131px;">June</div></div><div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="left: 0px; top: 0px; right: 0px; bottom: 0px; position: absolute;"><div class="flot-tick-label tickLabel" style="left: 8px; top: 264px; text-align: right; position: absolute;">0</div><div class="flot-tick-label tickLabel" style="left: 8px; top: 198px; text-align: right; position: absolute;">5</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 132px; text-align: right; position: absolute;">10</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 66px; text-align: right; position: absolute;">15</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 0px; text-align: right; position: absolute;">20</div></div></div><canvas width="787" height="300" class="flot-overlay" style="left: 0px; top: 0px; width: 787px; height: 300px; position: absolute; direction: ltr;"></canvas></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Gráfico - Objetivos por Aliança -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <i class="fa fa-bar-chart-o"></i>
                                    <h3 class="box-title">Objetivos por Aliança</h3><br><span style="font-size:9px;">(qtdeMedalhas / (qtdeTorres X qtdeGuerreiros X 10)) X 100</span>
                                    <div class="box-tools pull-right">
                                        <!--button class="btn btn-default btn-sm" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-default btn-sm" type="button" data-widget="remove"><i class="fa fa-times"></i></button-->
                                        <button class="btn btn-default btn-sm" onclick="updKnobChart('AliancasKnobChart')" type="button"><i class="fa fa-refresh"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row" id="AliancasKnobChart">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--div class="row">
                        <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <h3 class="box-title text-danger">Sparkline Pie</h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-default btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                    </div>
                                </div>
                                <div class="box-body text-center">
                                    <div class="sparkline" data-height="100px" data-width="100px" data-offset="90" data-type="pie"><canvas width="100" height="100" style="width: 100px; height: 100px; vertical-align: top; display: inline-block;"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <h3 class="box-title text-blue">Sparkline line</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-default btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                        </div>
                                </div>
                                <div class="box-body text-center">
                                    <div class="sparkline" data-height="100px" data-width="100%" data-offset="90" data-type="line" data-fill-color="rgba(57, 204, 204, 0.08)" data-line-color="#39CCCC" data-line-width="2" data-spot-color="#39CCCC" data-max-spot-color="#00a65a" data-min-spot-color="#f56954" data-highlight-line-color="#222" data-highlight-spot-color="#f39c12" data-spot-radius="3"><canvas width="507" height="100" style="width: 507.94px; height: 100px; vertical-align: top; display: inline-block;"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header">
                                    <h3 class="box-title text-warning">Sparkline Bar</h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-default btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                    </div>
                                </div>
                                <div class="box-body text-center">
                                    <div class="sparkline" data-height="100px" data-width="97%" data-type="bar" data-bar-color="#f39c12" data-bar-spacing="7" data-bar-width="14"><canvas width="224" height="100" style="width: 224px; height: 100px; vertical-align: top; display: inline-block;"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div-->
                </section>
			</div>

            <!-- Rodapé -->
            <?php include_once "../frames/footer.php"; ?>

            <!-- Painel de Controle -->
            <?php include_once "../frames/controlpanel.php"; ?>

            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

            <!-- Chart Scripts -->
            <!-- FLOT CHARTS -->
            <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.js"></script>
            <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.resize.js"></script>
            <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.pie.js"></script>
            <script src="/stylesheet/AdminLTE/2.4.5/bower_components/Flot/jquery.flot.categories.js"></script>
            <!-- KNOB CHART -->
            <script src="/stylesheet/AdminLTE/2.4.5/bower_components/jquery-knob/js/jquery.knob.js"></script>
            <!-- Page Scripts -->
            <script>
                $(function () {
                    // Carrega os Gráficos de Alianças
                    updBarChart('AliancasBarChart');
                    updKnobChart('AliancasKnobChart');
                });

                // Bar Chart
                function updBarChart(chartID){
                    // Carrega os dados das Alianças
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        grafico = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        grafico = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    // Popula os dados do Gráfico
                    grafico.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            chartBar(chartID, this.responseText);
                        }
                    };
                    grafico.open("GET", '/igot/config/results.php?return=Dashboard&frame='+chartID, true);
                    grafico.send();
                }
                // Formata os Gráficos de Barra (FLOT)
                function chartBar(chartID, chartData){
                    /* BAR CHART (FLOT) */
                    var bar_data = {
                        //label: "Torres",
                        data: eval("[" + chartData + "]"),
                        color: '#605ca8'
                    };
                    $.plot('#'+chartID, [bar_data], {
                        grid  : {
                        borderWidth: 0,
                        borderColor: '#f3f3f3',
                        tickColor  : '#f3f3f3'
                        },
                        series: {
                        bars: {
                            show    : true,
                            barWidth: 0.5,
                            align   : 'center'
                        }
                        },
                        xaxis : {
                        mode      : 'categories',
                        tickLength: 0
                        }
                    });
                }

                // KNOB Chart
                function updKnobChart(chartID){
                    // Carrega os dados das Alianças
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        grafico = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        grafico = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    // Popula os dados do Gráfico
                    grafico.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById(chartID).innerHTML = this.responseText;
                            chartKnob();
                        }
                    };
                    grafico.open("GET", '/igot/config/results.php?return=Dashboard&frame='+chartID, true);
                    grafico.send();
                }

                // Formata os Gráficos (KNOB)
                function chartKnob(){
                    $(".knob").knob({
                        draw: function() {
                            // "tron" case
                            if (this.$.data('skin') == 'tron') {
                            var a = this.angle(this.cv),  // Angle
                                sa = this.startAngle,     // Previous start angle
                                sat = this.startAngle,    // Start angle
                                ea,                       // Previous end angle
                                eat = sat + a,            // End angle
                                r = true
                            ;
                            
                            this.g.lineWidth = this.lineWidth;

                            this.o.cursor
                            && (sat = eat - 0.3)
                            && (eat = eat + 0.3);

                            if (this.o.displayPrevious) {
                                ea = this.startAngle + this.angle(this.value);
                                this.o.cursor
                                && (sa = ea - 0.3)
                                && (ea = ea + 0.3);
                                this.g.beginPath();
                                this.g.strokeStyle = this.previousColor;
                                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                                this.g.stroke();
                            }

                            this.g.beginPath();
                            this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                            this.g.stroke();

                            this.g.lineWidth = 2;
                            this.g.beginPath();
                            this.g.strokeStyle = this.o.fgColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                            this.g.stroke();

                            return false;
                            }
                        }
                    });
                }
            </script>
        </div>
    </body>
</html>