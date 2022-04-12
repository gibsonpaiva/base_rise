<?php
  /*** HTML dinâmico do conteúdo da Página ***/
  // Inclue objetos referentes a bases de dados
  include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
  // Instancia os objetos referente a base de dados
  $db_igot = new IGOT();  

  // Prepara as Variaves
  $TotalCastelos=0;
  $TotalTorres=0;
  $TotalGuerreiros = $db_igot->getTotalGuerreiros();  
  $TotalTorreDoReino = $db_igot->getTotalTorreDoReino();
 
  $estrutura_html = "";
  // Listagem dos Reinos
  $reinos = $db_igot->getReinos();
  if($reinos!=null){    
    for($r=0; $r<count($reinos); $r++){
      $TotalTorreDoReino = $db_igot->getTotalTorreDoReino($reinos[$r]['id']);   
      // Obtem o Total de Guerreiros no Reino
      $GuerreirosNoReino = $db_igot->getTotalGuerreiros($reinos[$r]['id']);
      //$TotalGuerreiros += $GuerreirosNoReino; // Soma o número de Guerreiros deste Reino para obter o número total de Guerreiros
      // Listagem dos Castelos
      $castelos = $db_igot->getCastelos($reinos[$r]['id']);
      $TotalCastelos += count($castelos); // Soma o número de Castelos deste Reino para obter o número total de Castelos
      // HTML dos Reinos
      $estrutura_html .= '
        <div class="panel panel-default">
          <div class="panel-heading"> 
            <h4 class="panel-title">
              <a id="font-responsivo" onclick="showPortalDoReino('.$reinos[$r]['id'].')" href="#Reino'.$reinos[$r]['id'].'" class="reino" data-toggle="collapse" data-parent="ListaReinos"><img src="/igot/img/reino-botao-1.png" width="30px" height="30px"> Reino: '.$reinos[$r]['Nome'].'
                <span class="pull-right">
                  <span id="responsivo"  class="label label-danger">'.count($castelos).'<img class="menu-icon" src="/igot/img/castelo-branco-1.png" width="15px" height="15px" style="margin-left:5px; margin-right:2px;"></span>
                  <span id="responsivo"  class="label label-success">'.$TotalTorreDoReino.'<img class="menu-icon" src="/igot/img/torre-branca-1.png" width="15px" height="15px" style="margin-left:5px; margin-right:2px;"></span>
                  <span id="responsivo"  class="label label-warning">'.$GuerreirosNoReino.'<img class="menu-icon" src="/igot/img/guerreiro-branco-1.png" width="15px" height="15px" style="margin-left:5px; margin-right:2px;"></span>
                </span>
              </a>
            </h4>
          </div>
          <div id="Reino'.$reinos[$r]['id'].'" class="panel-collapse collapse">
            <ul class="list-group">
      ';
      // Loop para Identificar as Torres de cada um dos Castelos
      if($castelos!=null){
        for($c=0; $c<count($castelos); $c++){
        // HTML dos Castelos
        $estrutura_html .= '
              <li class="list-group-item list-group-item-n1">
                <a onclick="showPortalDoCastelo('.$castelos[$c]['id'].')" href="#Castelo'.$castelos[$c]['id'].'" data-toggle="collapse" data-parent="ListaReinos" style="color:#333333;"><img src="/igot/img/castelo-botao-1.png" width="30px" height="30px"> Castelo : '.$castelos[$c]['Nome'].'</a>
              </li>
        ';
        // Listagem das Torres
        $torres = $db_igot->getTorres($castelos[$c]['id']);
        $TotalTorres += count($torres);
        if($torres!=null){
            $estrutura_html .= '
              <div id="Castelo'.$castelos[$c]['id'].'" class="panel-collapse collapse">
            ';
            for($t=0; $t<count($torres); $t++){
              $GuerreiroNaTorre = $db_igot->getGuerreirosNasTorres("idGuerreiro={$_SESSION['igot']['Guerreiro']['id']} AND idTorre={$torres[$t]['id']}");
              $estrutura_html .= '
                <li class="list-group-item list-group-item-n2" name="Torre'.$torres[$t]['id'].'">
                  <a onclick="showPortalDaTorre('.$torres[$t]['id'].')" href="#" style="color:#333333;"><img src="/igot/img/'; if($GuerreiroNaTorre==null){$estrutura_html.='torre-botao-2.png';}else{$estrutura_html.='torre-botao-1.png';} $estrutura_html .= '" width="30px" height="30px"> Torre : '.$torres[$t]['Nome'].'</a>
              ';
              if($GuerreiroNaTorre==null){
                $estrutura_html .= '
                  <span class="pull-right">
                    <button class="btn btn-xs btn-default" onclick="Alistar('.$torres[$t]['id'].')" type="button">Alistar</button>
                  </span>
                ';
              }
              $estrutura_html .= '                  
                </li>
              ';
            }
            $estrutura_html .= '
              </div>
            ';
          }
        }
      }
      $estrutura_html .= '
            </ul>
          </div>
        </div>
      ';
    }
  }
?>

<div class="tab-pane" id="Reinos">

    <img width="100px" align="left" height="auto" alt="Reinos" src="/igot/img/reino-botao-1.png">
        <br><h4><p class="titulo"> OS REINOS E SUAS ESTRUTURAS</p></h4>
        <p class="conteudo"> 
            Veja como os Reinos estão estruturados e relacionados aos seus castelos e torres e guerreiros.
        </p>
        <hr class='featurette-divider'>
  
  <div class="box-body">
    <!-- Quantitativo Geral -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">        
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?php echo count($reinos); ?></h3>
            <p>REINOS</p>
          </div>
          <div class="icon">
            <img class="menu-icon" src="/igot/img/reino-branco-1.png" align="center" width="80px" height="80px">            
          </div>
        </div>
      </div>      
      <div class="col-lg-3 col-xs-6">        
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo $TotalCastelos; ?><sup style="font-size: 20px"></sup></h3>
            <p>CASTELOS</p>
          </div>
          <div class="icon" align="center">
            <img class="menu-icon" src="/igot/img/castelo-branco-1.png" align="center" width="80px" height="80px">
          </div>
        </div>
      </div>     
      <div class="col-lg-3 col-xs-6">        
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo $TotalTorres; ?></h3>
            <p>TORRES</p>
          </div>
          <div class="icon">
            <img class="menu-icon" src="/igot/img/torre-branca-1.png" align="center" width="80px" height="80px">
          </div>
        </div>
      </div>     
      <div class="col-lg-3 col-xs-6">        
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo $TotalGuerreiros; ?></h3>
            <p>GUERREIROS</p>
          </div>
          <div class="icon">
            <img class="menu-icon" src="/igot/img/guerreiro-branco-1.png" align="center" width="80px" height="80px">
          </div>
        </div>
      </div>        
    </div>
    <!-- Navegação entre Reinos, Castelos e Torres + Portal do Reino, Castelo e Torre -->
    <div class="row">
      <!-- Lista de Reinos, Castelos e Torres -->
      <div class="col-xs-12 col-sm-12 col-md-12" id="ListaReinos">
        <?php echo $estrutura_html; ?>
      </div>
      <!-- Portais - Reino, Castelo e Torre -->
      <div class="col-xs-12 col-sm-12 col-md-12" id="Portal" style="display:none;">
        <!-- Cabeçalho dos Portais - Reino, Castelo e Torre -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12" id="Portal_Cabecalho" style="min-height:95px;"></div>
        </div>
        <!-- Conteúdo do Portal do Reino -->
        <div id="PortalDoReino" style="display:none;">
          <!-- Perfil Quantitativo do Reino-->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12" id="PortalDoReino_Perfil"></div>
          </div>          
          <!-- Texto Descritivo Sobre o Reino -->
          <div class='row' id='PortalDoReino_Sobre-Descricao'></div>
          <!-- Gráfico - Flot -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Medalhas por Castelos</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" type="button" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div id="PortalDoReino_BarChart" style="padding: 0px; height: 300px; position: relative;"><canvas width="787" height="300" class="flot-base" style="left: 0px; top: 0px; width: 787px; height: 300px; position: absolute; direction: ltr;"></canvas><div class="flot-text" style="left: 0px; top: 0px; right: 0px; bottom: 0px; color: rgb(84, 84, 84); font-size: smaller; position: absolute;"><div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="left: 0px; top: 0px; right: 0px; bottom: 0px; position: absolute;"><div class="flot-tick-label tickLabel" style="left: 37px; top: 280px; text-align: center; position: absolute; max-width: 131px;">January</div><div class="flot-tick-label tickLabel" style="left: 168px; top: 280px; text-align: center; position: absolute; max-width: 131px;">February</div><div class="flot-tick-label tickLabel" style="left: 310px; top: 280px; text-align: center; position: absolute; max-width: 131px;">March</div><div class="flot-tick-label tickLabel" style="left: 447px; top: 280px; text-align: center; position: absolute; max-width: 131px;">April</div><div class="flot-tick-label tickLabel" style="left: 583px; top: 280px; text-align: center; position: absolute; max-width: 131px;">May</div><div class="flot-tick-label tickLabel" style="left: 714px; top: 280px; text-align: center; position: absolute; max-width: 131px;">June</div></div><div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="left: 0px; top: 0px; right: 0px; bottom: 0px; position: absolute;"><div class="flot-tick-label tickLabel" style="left: 8px; top: 264px; text-align: right; position: absolute;">0</div><div class="flot-tick-label tickLabel" style="left: 8px; top: 198px; text-align: right; position: absolute;">5</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 132px; text-align: right; position: absolute;">10</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 66px; text-align: right; position: absolute;">15</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 0px; text-align: right; position: absolute;">20</div></div></div><canvas width="787" height="300" class="flot-overlay" style="left: 0px; top: 0px; width: 787px; height: 300px; position: absolute; direction: ltr;"></canvas></div>
            </div>
          </div>
        </div>
        <!-- Conteúdo do Portal do Castelo -->
        <div id="PortalDoCastelo" style="display:none;">
          <!-- Perfil Quantitativo do Castelo -->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12" id="PortalDoCastelo_Perfil"></div>
          </div>
          <!-- Texto Descritivo Sobre o Castelo -->
          <div class='row' id='PortalDoCastelo_Sobre-Descricao'></div>
          <!-- Gráfico - Flot -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Medalhas por Torres</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" type="button" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" type="button" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div id="PortalDoCastelo_BarChart" style="padding: 0px; height: 300px; position: relative;"><canvas width="787" height="300" class="flot-base" style="left: 0px; top: 0px; width: 787px; height: 300px; position: absolute; direction: ltr;"></canvas><div class="flot-text" style="left: 0px; top: 0px; right: 0px; bottom: 0px; color: rgb(84, 84, 84); font-size: smaller; position: absolute;"><div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="left: 0px; top: 0px; right: 0px; bottom: 0px; position: absolute;"><div class="flot-tick-label tickLabel" style="left: 37px; top: 280px; text-align: center; position: absolute; max-width: 131px;">January</div><div class="flot-tick-label tickLabel" style="left: 168px; top: 280px; text-align: center; position: absolute; max-width: 131px;">February</div><div class="flot-tick-label tickLabel" style="left: 310px; top: 280px; text-align: center; position: absolute; max-width: 131px;">March</div><div class="flot-tick-label tickLabel" style="left: 447px; top: 280px; text-align: center; position: absolute; max-width: 131px;">April</div><div class="flot-tick-label tickLabel" style="left: 583px; top: 280px; text-align: center; position: absolute; max-width: 131px;">May</div><div class="flot-tick-label tickLabel" style="left: 714px; top: 280px; text-align: center; position: absolute; max-width: 131px;">June</div></div><div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="left: 0px; top: 0px; right: 0px; bottom: 0px; position: absolute;"><div class="flot-tick-label tickLabel" style="left: 8px; top: 264px; text-align: right; position: absolute;">0</div><div class="flot-tick-label tickLabel" style="left: 8px; top: 198px; text-align: right; position: absolute;">5</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 132px; text-align: right; position: absolute;">10</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 66px; text-align: right; position: absolute;">15</div><div class="flot-tick-label tickLabel" style="left: 1px; top: 0px; text-align: right; position: absolute;">20</div></div></div><canvas width="787" height="300" class="flot-overlay" style="left: 0px; top: 0px; width: 787px; height: 300px; position: absolute; direction: ltr;"></canvas></div>
            </div>
          </div>
        </div>
        <!-- Conteúdo do Portal da Torre -->
        <div id="PortalDaTorre" style="display:none;">
          <!-- Perfil Quantitativo da Torre-->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12" id="PortalDaTorre_Perfil"></div>
          </div>
          <!-- Guias -->
          <div class='row'>
            <div class='col-xs-12 col-sm-12 col-md-12'>
              <div class='nav-tabs-custom'>
                <ul class='nav nav-tabs'>
                    <li class='active'><a href='#PortalDaTorre_Forca' data-toggle='tab'>Força</a></li>
                    <li><a href='#PortalDaTorre_Trilha' data-toggle='tab'>Trilha</a></li>
                    <li><a href='#PortalDaTorre_Eventos' data-toggle='tab'>Próximos Eventos</a></li>
                    <li><a href='#PortalDaTorre_TimeLine' data-toggle='tab'>Linha do Tempo</a></li>
                </ul>
              </div>
              <div class='tab-content'>
                <div class='active tab-pane row' id='PortalDaTorre_Forca'>              
                  <div class='box-header with-border'>
                    <h3 class='box-title'>Patentes da Torre : </h3>                  
                  </div>                               
                  <div class='box-body'>
                    <!--Gráfico do Perfil da Torre--> 
                    <div class='row' style='min-height:170px;'>
                      <div class='col-xs-12 col-sm-12 col-md-12' align='center'>                                      
                        <div class='chart-responsive'>                                        
                          <canvas id='mycanvas' height='155' width='257' style='width:257px; height:155px;'></canvas>                                            
                          <h3 class='description-header' name='Medalhas' style='margin-top:-107px;'></h3>Medalhas                           
                        </div>                                                                                     
                      </div>
                      <div class='col-xs-12 col-sm-12 col-md-12'>
                        <ul class='chart-legend clearfix'>
                          <li><i class='fa fa-circle-o' style='color:#2b6ca7;'></i> Comandantes</li>
                          <li><i class='fa fa-circle-o' style='color:#3584cb;'></i> Cavaleiros</li>
                          <li><i class='fa fa-circle-o' style='color:#5b9bd5;'></i> Soldados</li>
                          <li><i class='fa fa-circle-o' style='color:#98c0e4;'></i> Recrutas</li>
                        </ul>
                      </div>                     
                    </div>
                    <hr class='featurette-divider'>
                    <!--Membros da Torre--> 
                    <div class='row' id='PortalDaTorre_Forca-Membros'></div>
                  </div>
                </div>
                <div class='tab-pane row' id='PortalDaTorre_Trilha'></div>
                <div class='tab-pane row' id='PortalDaTorre_Eventos'></div>              
                <div class='tab-pane row' id='PortalDaTorre_TimeLine'></div>                  
              </div>
            </div>
          </div>
        </div>
        <!-- Rodapé dos Portais - Reino, Castelo e Torre -->
        <div class="row">
          <div class="col-md-12" id="Portal_Rodape">
            <p align="center" class="conteudo">
              <img width="40%" height="20%" align="middle" class="conteudo" alt="ESPADA" src="/igot/img/game2.png"><br>            
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>   

  <script>
    // Carrega o Portal do Reino Selecionado
    function showPortalDoReino(idReino){
      // Carrega os dados do Reino selecionado
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        cabecalho = new XMLHttpRequest();
        perfil = new XMLHttpRequest();
        descricao = new XMLHttpRequest();
        grafico = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        cabecalho = new ActiveXObject("Microsoft.XMLHTTP");
        perfil = new ActiveXObject("Microsoft.XMLHTTP");
        descricao = new ActiveXObject("Microsoft.XMLHTTP");
        grafico = new ActiveXObject("Microsoft.XMLHTTP");
      }
      // Popula o Cabeçalho do Portal do Reino
      cabecalho.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("Portal_Cabecalho").innerHTML = this.responseText;
          $("#Portal_Cabecalho").attr("class", "col-md-12 bg-aqua"); // Aplica cor de fundo referente ao Reino no Cabeçalho
        }
      };
      cabecalho.open("GET", '/igot/config/results.php?return=PortalDoReino&frame=Cabecalho&id='+idReino, true);
      cabecalho.send();
      // Popula o Portal do Reino - Perfil Quantitativo
      perfil.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("PortalDoReino_Perfil").innerHTML = this.responseText;
        }
      };
      perfil.open("GET", '/igot/config/results.php?return=PortalDoReino&frame=Perfil&id='+idReino, true);
      perfil.send();
      // Popula o Portal do Reino - Guia (Sobre)
      descricao.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("PortalDoReino_Sobre-Descricao").innerHTML = this.responseText;
        }
      };
      descricao.open("GET", '/igot/config/results.php?return=PortalDoReino&frame=Descricao&id='+idReino, true);
      descricao.send();
      // Popula os dados do Gráfico
      grafico.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          chartPortalDoReino(this.responseText);
        }
      };
      grafico.open("GET", '/igot/config/results.php?return=PortalDoReino&frame=Grafico&id='+idReino+'&sort=Castelo', true);
      grafico.send();
      // Exibe o Portal do Castelo
      $("#ListaReinos").attr("class", "col-xs-12 col-sm-12 col-md-5"); // Redimensiona a coluna com a Lista de Reinos
      $("#Portal").attr("class", "col-xs-12 col-sm-12 col-md-7"); // Redimensiona a coluna com o Portal do Castelo
      $("#PortalDoCastelo").hide(); // Oculta o conteúdo do Portal do Castelo
      $("#PortalDaTorre").hide(); // Oculta o conteúdo do Portal da Torre
      $("#PortalDoReino").show(); // Exibe o conteúdo do Portal do Reino
      $("#Portal").show(); // Exibe o Portal do Reino
    }
    function chartPortalDoReino(chartData){
      /* BAR CHART (FLOT) */
      var bar_data = {
        //label: "Castelos",
        data: eval("[" + chartData + "]"),
        color: '#dd4b39'
      };
      $.plot('#PortalDoReino_BarChart', [bar_data], {
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

    // Carrega o Portal do Castelo Selecionado
    function showPortalDoCastelo(idCastelo){
      // Carrega os dados do Castelo selecionado
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        cabecalho = new XMLHttpRequest();
        perfil = new XMLHttpRequest();
        descricao = new XMLHttpRequest();
        grafico = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        cabecalho = new ActiveXObject("Microsoft.XMLHTTP");
        perfil = new ActiveXObject("Microsoft.XMLHTTP");
        descricao = new ActiveXObject("Microsoft.XMLHTTP");
        grafico = new ActiveXObject("Microsoft.XMLHTTP");
      }
      // Popula o Cabeçalho do Portal do Castelo
      cabecalho.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("Portal_Cabecalho").innerHTML = this.responseText;
          $("#Portal_Cabecalho").attr("class", "col-md-12 bg-red"); // Aplica cor de fundo referente ao Castelo no Cabeçalho
        }
      };
      cabecalho.open("GET", '/igot/config/results.php?return=PortalDoCastelo&frame=Cabecalho&id='+idCastelo, true);
      cabecalho.send();
      // Popula o Portal do Castelo - Perfil Quantitativo
      perfil.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("PortalDoCastelo_Perfil").innerHTML = this.responseText;
        }
      };
      perfil.open("GET", '/igot/config/results.php?return=PortalDoCastelo&frame=Perfil&id='+idCastelo, true);
      perfil.send();
      // Popula o Portal do Castelo - Guia (Sobre)
      descricao.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("PortalDoCastelo_Sobre-Descricao").innerHTML = this.responseText;
        }
      };
      descricao.open("GET", '/igot/config/results.php?return=PortalDoCastelo&frame=Descricao&id='+idCastelo, true);
      descricao.send();
      // Popula os dados do Gráfico
      grafico.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          chartPortalDoCastelo(this.responseText);
        }
      };
      grafico.open("GET", '/igot/config/results.php?return=PortalDoCastelo&frame=Grafico&id='+idCastelo+'&sort=Torre', true);
      grafico.send();
      // Exibe o Portal do Castelo
      $("#ListaReinos").attr("class", "col-xs-12 col-sm-12 col-md-5"); // Redimensiona a coluna com a Lista de Reinos
      $("#Portal").attr("class", "col-xs-12 col-sm-12 col-md-7"); // Redimensiona a coluna com o Portal do Castelo
      $("#PortalDoReino").hide(); // Oculta o conteúdo do Portal do Reino
      $("#PortalDaTorre").hide(); // Oculta o conteúdo do Portal da Torre
      $("#PortalDoCastelo").show(); // Exibe o conteúdo do Portal do Castelo
      $("#Portal").show(); // Exibe o Portal do Castelo
    }
    function chartPortalDoCastelo(chartData){
      /* BAR CHART (FLOT) */
      var bar_data = {
        //label: "Torres",
        data: eval("[" + chartData + "]"),
        color: '#00a65a'
      };
      $.plot('#PortalDoCastelo_BarChart', [bar_data], {
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

    // Carrega o Portal da Torre Selecionada
    function showPortalDaTorre(idTorre){
      // Carrega os dados da Torre selecionada
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        cabecalho = new XMLHttpRequest();
        perfil = new XMLHttpRequest();
        forca = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        cabecalho = new ActiveXObject("Microsoft.XMLHTTP");
        perfil = new ActiveXObject("Microsoft.XMLHTTP");
        forca = new ActiveXObject("Microsoft.XMLHTTP");
      }
      // Popula o Cabeçalho do Portal da Torre
      cabecalho.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("Portal_Cabecalho").innerHTML = this.responseText;
          $("#Portal_Cabecalho").attr("class", "col-md-12 bg-green"); // Aplica cor de fundo referente à Torre no Cabeçalho
        }
      };
      cabecalho.open("GET", '/igot/config/results.php?return=PortalDaTorre&frame=Cabecalho&id='+idTorre, true);
      cabecalho.send();
      // Popula o Portal da Torre - Perfil Quantitativo
      perfil.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("PortalDaTorre_Perfil").innerHTML = this.responseText;
        }
      };
      perfil.open("GET", '/igot/config/results.php?return=PortalDaTorre&frame=Perfil&id='+idTorre+'&sort=Medalhas DESC', true);
      perfil.send();
      // Popula o Portal da Torre - Guia (Força)
      forca.onreadystatechange = function() { // Guia Força
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("PortalDaTorre_Forca-Membros").innerHTML = this.responseText;
        }
      };
      forca.open("GET", '/igot/config/results.php?return=PortalDaTorre&frame=Forca-Membros&id='+idTorre+'&sort=idPosicao DESC, Guerreiro', true);
      forca.send();
      // Aguarda para formatar o Gráfico
      setTimeout(chartPortalDaTorre, 500);
      // Exibe o Portal da Torre
      $("#ListaReinos").attr("class", "col-xs-12 col-sm-12 col-md-5"); // Redimensiona a coluna com a Lista de Reinos
      $("#Portal").attr("class", "col-xs-12 col-sm-12 col-md-7"); // Redimensiona a coluna com a Portal da Torre
      $("#PortalDoReino").hide(); // Oculta o conteúdo do Portal do Reino
      $("#PortalDoCastelo").hide(); // Oculta o conteúdo do Portal do Castelo
      $("#PortalDaTorre").show(); // Exibe o conteúdo do Portal da Torre
      $("#Portal").show(); // Exibe o Portal da Torre
    }
    // Carrega o gráfico da Torre
    function chartPortalDaTorre(){
      var pagPart = $('#PortalDaTorre'); // Restringe os objetos em uma parte específica da página
      pagPart.find("[name='Medalhas']").html(pagPart.find("[name='qtdeMedalhas']").attr("data"));
      var qtdeComandante = pagPart.find("[name='qtdeComandantes']").attr("data");
      var qtdeCavaleiro = pagPart.find("[name='qtdeCavaleiros']").attr("data");
      var qtdeSoldado = pagPart.find("[name='qtdeSoldados']").attr("data");
      var qtdeRecruta = pagPart.find("[name='qtdeRecrutas']").attr("data");            
      if(qtdeComandante === undefined) { qtdeComandante=0; }
      if(qtdeCavaleiro === undefined) { qtdeCavaleiro=0; }
      if(qtdeSoldado === undefined) { qtdeSoldado=0; }
      if(qtdeRecruta === undefined) { qtdeRecruta=0; }

      var ctx = $('#mycanvas').get(0).getContext('2d');
      var data = [
        {
          value: parseInt(qtdeComandante),
          color: '#2b6ca7',
          highlight: '#c7ddf1',
          label: 'Comandante'
        },
        {
          value: parseInt(qtdeCavaleiro),
          color: '#3584cb',
          highlight: '#c7ddf1',
          label: 'Cavaleiro'
        },
        {
          value: parseInt(qtdeSoldado),
          color: '#5b9bd5',
          highlight: '#c7ddf1',
          label: 'Soldado'
        },
        {
          value: parseInt(qtdeRecruta),
          color: '#98c0e4',
          highlight: '#c7ddf1',
          label: 'Recruta'
        }
      ];
      var chart = new Chart(ctx).Doughnut(data);
    }

    // Alista o Guerreiro na Torre
    function Alistar(idTorre){
      var pagPart = $('#ListaReinos'); // Restringe os objetos em uma parte específica da página
      var trObj = pagPart.find($("[name='Torre"+idTorre+"']")); // Variável para resumo na identificação da linha referente à Torre
      $.ajax({
        url:'admin/ajax/updGuerreirosNasTorres.php',
        type:'POST',
        dataType: "json",
        data:'action=insert&idGuerreiro='+<?php echo $_SESSION['igot']['Guerreiro']['id']; ?>+'&idTorre='+idTorre+'&idPosicao=1',
        success:function(response){
          trObj.find("span").hide(); // Oculta o Botão para Alistar
          trObj.find("img").attr("src", "/igot/img/torre-botao-1.png"); // Atualiza a imagem da Torre
          showPortalDaTorre(idTorre); // Carrega o Portal da Torre
        }
      });
    }
  </script>
</div>