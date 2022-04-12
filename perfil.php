<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    
    // Inclue objetos referentes a bases de dados
    include_once "config/db.php";

    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
    $db_filebox = new filebox();

    // Obtém dados do Guerreiro a partir do Quadro de Medalhas
    $QuadroMedalhas = $db_igot->getMedalhas($_SESSION['igot']['Guerreiro']['idExercito'], $_SESSION['igot']['Guerreiro']['id']);
    
    // Obtém dados das cerificações do guerreiro
    $itensCertificacoes = $db_igot->getEventos($_SESSION['igot']['Guerreiro']['id'], 0, false);
    $Certificacoes = "";
    for ($i=0;$i<count($itensCertificacoes);$i++){
        if ($itensCertificacoes[$i]['idCategoria'] == 6){
        $Certificacoes .= "
            <button name='certificacao' style='margin-bottom: 0px; text-align: left; color: #333;' class='panel' type='button'data-toggle='collapse' data-target='#certificacoes".$itensCertificacoes[$i]['id']."'>{$itensCertificacoes[$i]['Tipo']}</button>
            <div style='color: light-grey;' id='certificacoes".$itensCertificacoes[$i]['id']."' class='panel-body panel'>
                <b>Data de Conclusão : </b>{$itensCertificacoes[$i]['DataConclusao']}<br>
                <b>Data de Expiração : </b>{$itensCertificacoes[$i]['DataExpiracaoEvento']}<br>
                <b>Aliança : </b>{$itensCertificacoes[$i]['NomeAlianca']}<br>
                <b>Torre : </b>{$itensCertificacoes[$i]['Torre']}<br>
            </div>
        ";
        }
    }

    //Obtém os eventos futuros
    $itensEventosfuturos = $db_igot->getEventos($_SESSION['igot']['Guerreiro']['id'], 0, true);
    $Eventosfuturos = "";
    for ($i=0;$i<count($itensEventosfuturos);$i++){
        $Eventosfuturos .= "
                <br><b>Evento</b><br> 
                <button style='margin-bottom: 0px;' class='panel text-left' type='button' data-toggle='collapse' data-target='#eventos".$itensEventosfuturos[$i]['id']."'>{$itensEventosfuturos[$i]['Tipo']}</button>
                <div style='display: hidden;' id='eventos".$itensEventosfuturos[$i]['id']."' class='panel-body panel'>
                    <b>Data de Início : </b>{$itensEventosfuturos[$i]['DataInicio']}<br>
                    <b>Tipo de Evento : </b>{$itensEventosfuturos[$i]['Categoria']}<br>
                    <b>Aliança : </b>{$itensEventosfuturos[$i]['NomeAlianca']}<br>
                    <b>Torre : </b>{$itensEventosfuturos[$i]['Torre']}<br>
                </div>
        ";
    }

    //Obtém o progresso do guerreiro
    $itensAndares = $db_igot->getAndares(true);
    $itensReinos = $db_igot->getGuerreirosNasTorres("idGuerreiro={$_SESSION['igot']['Guerreiro']['id']}", "idReino");
    $Guerreiros = "";
    for ($i=0;$i<count($itensReinos);$i++){
        $Guerreiros .= "
            <button id='progresso".$itensReinos[$i]['idReino']."' style='margin-bottom: 0px;' class='panel' type='button' data-toggle='collapse' data-target='#eventos".$itensReinos[$i]['idReino']."'>{$itensReinos[$i]['Reino']}</button>
            <div style='display: hidden; padding-bottom: 0px;' id='eventos".$itensReinos[$i]['idReino']."' class='panel-body panel'>
        ";
        $itensGuerreiros = $db_igot->getGuerreirosNasTorres("idGuerreiro={$_SESSION['igot']['Guerreiro']['id']} AND idReino={$itensReinos[$i]['idReino']}");
        for ($j=0;$j<count($itensGuerreiros);$j++){
            $width = ceil((100*$itensGuerreiros[$j]['idPosicao'])/($itensAndares['Andares']));
            //Para Andares acima de comandante
            if($width > 100){$width = 100;}
            //Trata as cores da barra de progresso
            if ($width <= 25){
                $nivel = "progress-bar progress-bar-danger progress-bar-striped";
            }elseif ($width <= 75){
                $nivel = "progress-bar progress-bar-warning progress-bar-striped";
            }else{
                $nivel = "progress-bar progress-bar-success progress-bar-striped";
            }
            $Guerreiros .= "   
                <div><b>Torre:</b>&nbsp;{$itensGuerreiros[$j]['Torre']}</div>
                <div class='progress'>
                    <div class='".$nivel."' role='progressbar' aria-valuenow='70' aria-valuemin='0' aria-valuemax='100' style='width:".$width."%'>{$width}%</div>
                </div>
            ";
        }
        $Guerreiros .= "
            </div>
        ";
    }
    
    //Obtém Imagem do Perfil
    $ImagemPerfil = $db_filebox->loadImagem(2, $_SESSION['user']['id']);
    if($_SESSION['igot']['Guerreiro']['id'] <> null){$Card = $db_igot->getCard($_SESSION['igot']['Guerreiro']['id']);}else{$Card="-";}

    // Monta a Linha do Tempo
    $itens = $db_igot->getTimeLine(5, "idGuerreiro={$_SESSION['igot']['Guerreiro']['id']}");    
    // Motagem da Linha do Tempo
    $timeline_html="";
    $pkItens = (is_array($itens) ? count($itens) : 0);
    for ($i=0; $i<$pkItens; $i++){
        // Data de Conclusão do Evento
        if($i==0 || ($itens[$i-1]['DataConclusao'] <> $itens[$i]['DataConclusao'])) {
            $timeline_html .= '
                <!-- Data -->
                <li class="time-label">
                    <span class="bg-red">
                        '.$itens[$i]['DataConclusao'].'
                    </span>
                </li>
            ';
        }
        // Icone do Tipo do Evento
        $timeline_html .= '
                <li>
                    <!-- Timeline - Icone -->
                    <i class="'.$itens[$i]['Icone'].' bg-gray"></i>
        ';
        // Corpo do Evento
        $timeline_html .= '
                    <!-- Timeline - body -->
                    <div class="timeline-item">
                        <!-- Tipo de Evento -->
                        <h3 class="timeline-header"><b>'.$itens[$i]['Categoria'].'</b></h3>
                        <!-- Descrição do Evento -->
                        <div class="timeline-body">
                            <b>'.$itens[$i]['Guerreiro'].'</b> '.strtolower($itens[$i]['Mensagem']).' '.$itens[$i]['Tipo'].'<br>
                            | Exército : '.$itens[$i]['Exercito'].' | Aliança : '.$itens[$i]['NomeAlianca'].'. | Torre : '.$itens[$i]['Torre'].' | Custo : R$ '.number_format($itens[$i]['Custo'], 2, ',', '.').' | Moedas : '.$itens[$i]['Moedas'].' |
                        </div>
                    </div>
                </li>
        ';
    }


   // Obtém as Solicitações
   $itens = $db_igot->getSolicitacaoDoGuerreiro($_SESSION['igot']['Guerreiro']['id']);
   if($itens == null){
       $html_MinhasSolicitações = "<tr>Você não possui férias recentes</tr>";       
   } else {        
    $html_MinhasSolicitações = "";
       for ($i=0; $i<count($itens); $i++){                     
           $contador = $i +1;         
           $html_MinhasSolicitações .= '
               <tr id="trSolicitacao'.$itens[$i]['id'].'">                                        
                   <td>'.$contador.'</td>
                   <td>'.$itens[$i]['id'].'</td>
                   <td>'.$itens[$i]['Guerreiro'].'</td>
                   <td>'.$itens[$i]['RegistradoEm'].'</td>
                   <td class="tipo"><span>'.$itens[$i]['Tipo'].'</span></td>
                   <td class="dataI"><span>'.$itens[$i]['DataInicioEvento'].'</span></td>
                   <td class="dataF"><span>'.$itens[$i]['DataConclusao'].'</span></td>
           ';
           
           switch ($itens[$i]['StatusAprovacao']) {
               
               case "Reprovado":
                    $html_MinhasSolicitações .= '
                       <td><span class="badge bg-red">Reprovado</span></td>
                   
                   '; 
               break;
               case "Aprovado":
                    $html_MinhasSolicitações.= '
                       <td><span class="badge bg-green">Aprovado</span></td>
                   
                   ';
               break;
               case "Pendente":
                    $html_MinhasSolicitações .= '
                       <td><span class="badge bg-yellow">Pendente</span></td>
                   
                   ';
               break;
           }
           $html_MinhasSolicitações .= '

           
           </tr>


           ';
       }
   }

    // Obtém o Total de Guerreiros em férias
    $itens = $db_igot->getFeriasGuerreiroLogado($_SESSION['igot']['Guerreiro']['id']);
    if($itens == null){
        $html_FeriasGuerreiroLogado = "<tr>Você não possui férias recentes</tr>";       
    } else {        
        $html_FeriasGuerreiroLogado = "";
        for ($i=0; $i<count($itens); $i++){                     
            $contador = $i +1;         
            $html_FeriasGuerreiroLogado .= '
                <tr>                    
                    <td>'.$contador.'</td>
                    <td>'.$itens[$i]['Guerreiro'].'</td>
                    <td>'.$itens[$i]['RegistradoEm'].'</td>
                    <td>'.$itens[$i]['Tipo'].'</td>
                    <td>'.$itens[$i]['DataInicioEvento'].'</td>
                    <td>'.$itens[$i]['DataConclusao'].'</td>
            ';
            
            switch ($itens[$i]['StatusAprovacao']) {
                
                case "Reprovado":
                    $html_FeriasGuerreiroLogado .= '
                        <td><span class="badge bg-red">Reprovado</span></td>
                    </tr>
                    '; 
                break;
                case "Aprovado":
                    $html_FeriasGuerreiroLogado .= '
                        <td><span class="badge bg-green">Aprovado</span></td>
                    </tr>
                    ';
                break;
                case "Pendente":
                    $html_FeriasGuerreiroLogado .= '
                        <td><span class="badge bg-yellow">Pendente</span></td>
                    </tr>
                    ';
                break;
            }
            
        }
    }
    
    // Obtém o Total de Guerreiros em férias
    $itens = $db_igot->getTotalGuerreirosEmFerias();
    if($itens == null){
        $html_guerreiroEmFerias = "<tr>Não há guerreiros em período de férias</tr>";
        $totalGuerreirosEmFerias = 0;
    } else {
        $totalGuerreirosEmFerias = count($itens);
        $html_guerreiroEmFerias = "";
        for ($i=0; $i<$totalGuerreirosEmFerias; $i++){                     
            $contador = $i +1;         
            $html_guerreiroEmFerias .= '
                <tr>                    
                    <td>'.$contador.'</td>
                    <td>'.$itens[$i]['Guerreiro'].'</td>
                    <td>'.$itens[$i]['RegistradoEm'].'</td>
                    <td>'.$itens[$i]['DataInicioEvento'].'</td>
                    <td>'.$itens[$i]['DataConclusao'].'</td>
                    
            ';
                
            
            switch ($itens[$i]['StatusAprovacao']) {
                
                case "Reprovado":
                    $html_guerreiroEmFerias .= '
                            <td><span class="badge bg-red">Reprovado</span></td>
                        </tr>
                    '; 
                break;
                case "Aprovado":
                    $html_guerreiroEmFerias .= '
                            <td><span class="badge bg-green">Aprovado</span></td>
                        </tr>
                    ';
                break;
                case "Pendente":
                    $html_guerreiroEmFerias .= '
                            <td><span class="badge bg-yellow">Pendente</span></td>
                        </tr>
                    ';
                break;
            }
        }
    }

    // Obtém o Total de Guerreiros em férias Agendadas
    $itens = $db_igot->getTotalGuerreirosEmFeriasAgendadas();
    if($itens == null){
        $html_guerreiroEmFeriasAgendadas = "<tr>Não há guerreiros em período de férias agendadas</tr>";
        $TotalGuerreiroEmFeriasAgendadas = 0;
    } else {
        $TotalGuerreiroEmFeriasAgendadas = count($itens);        
        $html_guerreiroEmFeriasAgendadas = "";        
        for ($i=0; $i<$TotalGuerreiroEmFeriasAgendadas; $i++){
            $contador = $i +1;        
            $html_guerreiroEmFeriasAgendadas .= '
                <tr>
                    <td>'.$contador.'</td>
                    <td>'.$itens[$i]['Guerreiro'].'</td>
                    <td>'.$itens[$i]['RegistradoEm'].'</td>
                    <td>'.$itens[$i]['DataInicioEvento'].'</td>
                    <td>'.$itens[$i]['DataConclusao'].'</td>
                   
            ';

            switch ($itens[$i]['StatusAprovacao']) {
                
                case "Reprovado":
                    $html_guerreiroEmFeriasAgendadas .= '
                            <td><span class="badge bg-red">Reprovado</span></td>
                        </tr>
                    '; 
                break;
                case "Aprovado":
                    $html_guerreiroEmFeriasAgendadas .= '
                            <td><span class="badge bg-green">Aprovado</span></td>
                        </tr>
                    ';
                break;
                case "Pendente":
                    $html_guerreiroEmFeriasAgendadas .= '
                            <td><span class="badge bg-yellow">Pendente</span></td>
                        </tr>
                    ';
                break;
            }          
        }
    }

    // Obtém o Total de Guerreiros em férias Concluidas
    $itens = $db_igot->getTotalGuerreirosEmFeriasConcluidas();
    if($itens == null){
        $html_guerreirosEmFeriasConcluidas = "<tr>Não há guerreiros em período de férias agendadas</tr>";
        $totalguerreirosEmFeriasConcluidas = 0;
    } else {
        $totalguerreirosEmFeriasConcluidas = count($itens);
        $html_guerreirosEmFeriasConcluidas = "";
        for ($i=0; $i<$totalguerreirosEmFeriasConcluidas; $i++){
            $contador = $i +1;         
            $html_guerreirosEmFeriasConcluidas .= '        
                <tr>
                    <td>'.$contador.'</td>
                    <td>'.$itens[$i]['Guerreiro'].'</td>
                    <td>'.$itens[$i]['RegistradoEm'].'</td>
                    <td>'.$itens[$i]['DataInicioEvento'].'</td>
                    <td>'.$itens[$i]['DataConclusao'].'</td>
            ';
            switch ($itens[$i]['StatusAprovacao']) {
                
                case "Reprovado":
                    $html_guerreirosEmFeriasConcluidas .= '
                            <td><span class="badge bg-red">Reprovado</span></td>
                        </tr>
                    '; 
                break;
                case "Aprovado":
                    $html_guerreirosEmFeriasConcluidas .= '
                            <td><span class="badge bg-green">Aprovado</span></td>
                        </tr>
                    ';
                break;
                case "Pendente":
                    $html_guerreirosEmFeriasConcluidas .= '
                            <td><span class="badge bg-yellow">Pendente</span></td>
                        </tr>
                    ';
                break;
            }
                             
        }
    }
     
    // Tipos de Eventos
    $itens = $db_igot->getEventosTipos(FERIAS); // Obtenção dos Eventos para montagem das opções do ComboBox
    $optEventosTipos = "";
    for ($i=0; $i<count($itens); $i++){
        $optEventosTipos .= '<option  value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }  
    
?>

<!DOCTYPE html>
<html>	
    <head>  
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Favicon -->
        <link rel="icon" href="/igot/img/torre1.ico" />
        <!-- Titulo -->
        <title>RISE</title>
        <!-- Rotating Card -->
        <link href="/stylesheet/others/rotating-card.css" rel="stylesheet" />
        <!-- Card perfil atributos-->
        <?php include_once "frames/head.php"; ?>

             <!-- Favicon -->
             <link rel="icon" href="/igot/img/torre1.ico" />
        <!-- Titulo -->
        <title>IGOT</title>
		<?php include_once "../frames/head.php"; ?>
        <!-- autoNumeric Script -->
        <script src="/stylesheet/autoNumeric/autoNumeric.js" type=text/javascript></script>
        <!-- iCheck -->
    </head>    
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            <!-- Cabeçalho -->
            <?php include_once "frames/header.php";?>
            <!-- Barra Lateral -->
            <?php include_once "frames/sidebar.php";?>
            <div class="content-wrapper">                
                <section class="content-header">                    
                    MINHA CONTA                
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> HOME</a></li>
                        <li class="active">PERFIL</li>
                    </ol>
                </section>        
                <section class="content">                   
                    <div class="box" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                        <section class="content">
                            <div class="row">
                                <div class="col-md-3">                                   
                                    <div class="box box">
                                        <div class="box-body box-profile">
                                            <img class="profile-user-img img-responsive img-circle" src="<?php echo $ImagemPerfil;?>"alt="User Image" style='border: 3px solid #dddd;' id="img">
                                            <h3 class="profile-username text-center"><?php echo $_SESSION['igot']['Guerreiro']['Nome']; ?></h3>
                                            <p class="text-muted text-center">TAMANHO DA ARMADURA : <?php echo $_SESSION['igot']['Guerreiro']['Exercito']; ?></p>
                                            <p class="text-muted text-center">EXÉRCITO : <?php echo $_SESSION['igot']['Guerreiro']['Exercito']; ?></p>
                                            <p align="center">
                                                <!--<label class="btn btn-sm btn-default btn-flat" type="button" data-toggle="modal" data-target="#Armadura" for='Armadura'>Alterar Armadura</label>-->
                                                <label class="btn btn-sm btn-default btn-flat" for='perfil_Arquivo'>Alterar Foto</label>
                                                <input  class="inputfile"  type='file' name='Arquivo' id='perfil_Arquivo' title='default'>
                                            </p>                                            
                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item">                                                    
                                                    <h4><img width="30px" height="30px" alt="Guerreiro" src="/igot/img/torre-botao-1.png">
                                                    <b>TORRES</b> <a class="titulo"></a></h4>
                                                </li>                                                
                                                <li class="list-group-item">
                                                    <b>Medalhas acumuladas <a class="pull-right" style="color:#dd4b39;"><?php echo $QuadroMedalhas['TotalMedalhas']; ?></a></b>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Posição no quadro de medalhas <a class="pull-right" style="color:#dd4b39;"><?php echo $QuadroMedalhas['Rank']; ?></a></b>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Comandante de torres <a class="pull-right"style="color:#dd4b39;"><?php echo $Card['Comandante'];?></a></b>
                                                </li>
                                                <li class="list-group-item">                                                    
                                                    <h4><img width="30px" height="30px" alt="Guerreiro" src="/igot/img/eventos-2.png">
                                                    <b>EVENTOS</b> <a class="titulo"></a></h4>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Moedas acumuladas <a class="pull-right"style="color:#dd4b39;"><?php echo $Card['Moedas'];?></a></b>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Treinamentos <a class="pull-right"style="color:#dd4b39;"><?php echo $Card['Treinamento'];?></a></b>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Certificações <a class="pull-right"style="color:#dd4b39;"><?php echo $Card['Certificacoes'];?></a></b>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="nav-tabs-custom" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#activity" data-toggle="tab">LINHA DO TEMPO</a></li>
                                            <!--li><a href="#timeline" data-toggle="tab">Ultimas Conquistas</a></li>-->
                                            <li><a href="#Objetivos" data-toggle="tab">OBJETIVOS</a></li>
                                            <li><a href="#Certificacao" data-toggle="tab">CERTIFICAÇÃO</a></li>
                                            <li><a href="#EventosFuturos" data-toggle="tab">EVENTOS FUTUROS</a></li>
                                            <li><a href="#Ferias" data-toggle="tab">FÉRIAS</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane" id="Objetivos">
                                                <!-- Cartão de perfil -->
                                                <div class="box box-solid">
                                                    <!-- Frente do Cartão -->
                                                    <div class="box-body" id="ListaProgressos">
                                                        <?php
                                                            if($Guerreiros != "" && $_SESSION['igot']['Guerreiro']['idExercito'] != null){
                                                                echo $Guerreiros;
                                                            }else{
                                                                echo "Você não está em nenhuma torre!";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>                                                                                                  
                                            </div>                                            
                                            <div class="tab-pane" id="Certificacao">                                                    
                                                <!-- Cartão de certificações -->
                                                <div class="box box-solid">
                                                    <div class="box-body">
                                                        <?php if($Certificacoes != "" && $_SESSION['igot']['Guerreiro']['idExercito'] != null){echo $Certificacoes;}else{echo "Você não possui nenhuma certificação cadastrada!";}?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="Ferias">
                                                <div class="box box-solid">
                                                    <div class="box-body">
                                                        <div class="col-lg-4 col-xs-6">
                                                            <!-- small box -->
                                                            <div class="small-box bg-aqua">
                                                                <div class="inner">
                                                                    <h3><?php echo $totalGuerreirosEmFerias;?></h3>
                                                                    <p>Em Férias</p>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="fa fa-camera"></i>
                                                                </div>
                                                                <a href="/ferias.php" class="small-box-footer">
                                                                    Saiba Mais <i class="fa fa-arrow-circle-right"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-4 col-xs-6">
                                                            <!-- small box -->
                                                            <div class="small-box bg-green">
                                                                <div class="inner">
                                                                <h3><?php echo  $TotalGuerreiroEmFeriasAgendadas;?></h3>
                                                                    <p>Períodos agendados</p>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="ion ion-stats-bars"></i>
                                                                </div>
                                                                <a href="/ferias.php" class="small-box-footer">
                                                                    Saiba Mais <i class="fa fa-arrow-circle-right"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <!-- ./col -->
                                                        <div class="col-lg-4 col-xs-6">
                                                            <!-- small box -->
                                                            <div class="small-box bg-yellow">
                                                                <div class="inner">
                                                                <h3><?php echo $totalguerreirosEmFeriasConcluidas;?></h3>
                                                                    <p>Períodos concluídos</p>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="fa fa-users"></i>
                                                                </div>
                                                                <a href="/ferias.php" class="small-box-footer">
                                                                    Saiba Mais <i class="fa fa-arrow-circle-right"></i>
                                                                </a>
                                                            </div>                                                            
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="box">
                                                                <div class="box-header">
                                                                    <h3 class="box-title">Minhas Solicitações</h3>                                                                    
                                                                </div>                                                                
                                                                <div class="box-body no-padding">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th style="width: 10px">#</th>
                                                                                <th>ID</th>
                                                                                <th>Guerreiro</th>                                                                                
                                                                                <th>Data da solictação</th>
                                                                                <th>Tipo das férias</th>                                                                                
                                                                                <th>Inicio das férias</th> 
                                                                                <th>Fim das férias</th>
                                                                                <th>Status</th>                                                                                
                                                                            </tr>
                                                                            <?php echo $html_MinhasSolicitações;?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>                                                               
                                                            </div>
                                                            <div class="box">
                                                                <div class="box-header">
                                                                    <h3 class="box-title">Meus últimos períodos de férias</h3>                                                                    
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <div class="box-body no-padding">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th style="width: 10px">#</th>
                                                                                <th>Guerreiro</th>                                                                                
                                                                                <th>Data da solictação</th>
                                                                                <th>Tipo das férias</th>                                                                                
                                                                                <th>Inicio das férias</th> 
                                                                                <th>Fim das férias</th>
                                                                                <th>Status</th>
                                                                            </tr>
                                                                            <?php echo $html_FeriasGuerreiroLogado;?></h3>
                                                                        </tbody>
                                                                    </table>
                                                                </div>                                                               
                                                            </div>                                  
                                                           
                                                        </div>
                                                    </div>                                                       
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="EventosFuturos">
                                                <div class="box box-solid">                                                    
                                                    <div class="box-body">
                                                        <?php if($Eventosfuturos != "" && $_SESSION['igot']['Guerreiro']['idExercito'] != null){echo $Eventosfuturos;}else{echo "Você não possui nenhum evento futuro cadastrado!";}?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="active tab-pane" id="activity"> 
                                                <section class="content">
                                                    <div class="row">                                                                   
                                                        <div class="col-lg-12 col-xs-12">
                                                            <img width="100px" align="left" height="auto" alt="LinhaDoTempo" src="/igot/img/linha-tempo-1.png">
                                                            <br><h4><p class="titulo">LINHA DO TEMPO DO GUERREIRO</p></h4>
                                                            <p class="conteudo"> 
                                                                &nbsp;&nbsp;&nbsp;&nbsp; A linha do tempo reflete os útimos eventos do guerreiro.<br>
                                                                &nbsp;&nbsp;&nbsp;&nbsp; Categoria/Tipos de eventos : Todos eles | Guerreiro : <strong> <?php echo $_SESSION['igot']['Guerreiro']['Nome']; ?> </strong>  | Ordem : Cronológica
                                                            </p>
                                                            <br>
                                                            <ul class="timeline">                                                            
                                                                <?php echo $timeline_html; ?>
                                                            </ul>
                                                        </div>    
                                                    </div>
                                                </section>
                                            </div>                                                
                                        </div>                                
                                    </div>                           
                                </div>
                                <div class='modal fade' id='Armadura' style='display:none;'>
                                    <div class='modal-dialog modal-megamenu' align='center' style='height:700px;'>
                                        <div class='modal-content' style='width:800px; !important;'>                                        
                                            <div class='modal-header' align='center' style='height:60px;' >
                                                <button class='close' aria-label='Close' type='button' data-dismiss='modal'><span class='fa fa-times' aria-hidden='true'></span></button>  
                                                <h4> SELECIONE SUA ARMADURA </h4>
                                            </div>                                                
                                            <div class='modal-body' align='left'>
                                                <div class="row">                                                    
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title">Qual a sua armadura guerreiro?</h3>
                                                    </div>                                                       
                                                    <div class="box-body">
                                                    <form name="feedback" action="javascript:void(0)">
                                                        <label>
                                                            <input type="radio" id="yes" name="yesOrNo" value="yes" onchange="displayQuestion(this.value)" />Masculino
                                                        </label>
                                                        <label>
                                                            <input type="radio" id="no" name="yesOrNo" value="no" onchange="displayQuestion(this.value)" />Feminino
                                                        </label>
                                                        <div class="form-group" id="yesQuestion" style="display:none;">
                                                            <label>Tamanhos</label>
                                                            <select class="form-control">
                                                                <option>P</option>
                                                                <option>M</option>
                                                                <option>G</option>
                                                                <option>GG</option>                                                                
                                                            </select>
                                                        </div>
                                                        <div class="form-group" id="noQuestion" style="display:none;">
                                                            <label>Tamanhos</label>
                                                            <select class="form-control">                                                                
                                                                <option>PP</option>
                                                                <option>BABY LOOK-PP</option>
                                                                <option>P</option>
                                                                <option>BABY LOOK-P</option>
                                                                <option>M</option>
                                                                <option>BABY LOOK-M</option>
                                                                <option>G</option>
                                                                <option>BABY LOOK-G</option>
                                                                <option>GG</option>
                                                                <option>BABY LOOK-GG</option>                                                                
                                                            </select>
                                                        </div>
                                                        <br/><br/><input type="submit">
                                                    </form>                                                  
                                                </div>   
                                            </div>
                                        </div>
                                        <div class='modal-footer' align='center'>
                                            <p align="center"> ARMADURA DO GUERREIRO</p>
                                        </div>                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
        <!-- Rodapé -->
        <?php include_once "frames/footer.php"; ?>
        <!-- Painel de Controle -->
        <?php include_once "frames/controlpanel.php"; ?>
        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
        <script>
            $(document).ready(function() {
                $('#perfil_Arquivo').change(function(){
                    UpdatePerfil();
                });
            });
        </script> 
    </div>
</body>
</html>

