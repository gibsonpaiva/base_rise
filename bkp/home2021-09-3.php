<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    
    // Inclue objetos referentes a bases de dados
    include_once "config/db.php";
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
    $db_filebox = new FileBox();

    // Obtém dados do Guerreiro a partir do Quadro de Medalhas
    $QuadroMedalhas = $db_igot->getMedalhas($_SESSION['igot']['Guerreiro']['idExercito'], $_SESSION['igot']['Guerreiro']['id']);
    
    // Obtém dados das cerificações do guerreiro
    $itensCertificacoes = $db_igot->getEventos($_SESSION['igot']['Guerreiro']['id'], 0, false);
    $Certificacoes = "";
    for ($i=0;$i<count($itensCertificacoes);$i++){
        if ($itensCertificacoes[$i]['idCategoria'] == 6){
            $Certificacoes .= "
                <button name='certificacao' style='margin-bottom: 0px; text-align: left; color: #333;' class='panel' type='button' data-toggle='collapse' data-target='#certificacoes".$itensCertificacoes[$i]['id']."'>{$itensCertificacoes[$i]['Tipo']}</button>
                <div style='color: light-grey;' id='certificacoes".$itensCertificacoes[$i]['id']."' class='panel-body panel-collapse collapse'>
                    <b>Data de Conclusão:</b>{$itensCertificacoes[$i]['DataConclusao']}<br>
                    <b>Data de Expiração:</b>{$itensCertificacoes[$i]['DataExpiracaoEvento']}<br>
                    <b>Aliança:</b>{$itensCertificacoes[$i]['NomeAlianca']}<br>
                    <b>Torre:</b>{$itensCertificacoes[$i]['Torre']}<br>
                </div>
            ";
        }
    }

    // Obtém os eventos futuros
    $itensEventosfuturos = $db_igot->getEventos($_SESSION['igot']['Guerreiro']['id'], 0, true);
    $Eventosfuturos = "";
    if($itensEventosfuturos != null){ // Somente se houver Eventos Futuros
        for($i=0; $i<count($itensEventosfuturos); $i++){
            $Eventosfuturos .= "
                <br><b>Evento</b><br> 
                <button style='margin-bottom: 0px;' class='panel text-left' type='button' data-toggle='collapse' data-target='#eventos".$itensEventosfuturos[$i]['id']."'>{$itensEventosfuturos[$i]['Tipo']}</button>
                <div style='display: hidden;' id='eventos".$itensEventosfuturos[$i]['id']."' class='panel-body panel-collapse collapse'>
                    <b>Data de Início:</b>{$itensEventosfuturos[$i]['DataInicio']}<br>
                    <b>Tipo de Evento:</b>{$itensEventosfuturos[$i]['Categoria']}<br>
                    <b>Aliança:</b>{$itensEventosfuturos[$i]['NomeAlianca']}<br>
                    <b>Torre:</b>{$itensEventosfuturos[$i]['Torre']}<br>
                </div>
            ";
        }
    }

    //Obtém o progresso do guerreiro
    $itensAndares = $db_igot->getAndares(true);
    $itensReinos = $db_igot->getGuerreirosNasTorres("idGuerreiro={$_SESSION['igot']['Guerreiro']['id']}", "idReino");
    $Guerreiros = "";
    for ($i=0;$i<count($itensReinos);$i++){
        $Guerreiros .= "
            <button id='progresso".$itensReinos[$i]['idReino']."' style='margin-bottom: 0px;' class='panel' type='button' data-toggle='collapse' data-target='#eventos".$itensReinos[$i]['idReino']."'>{$itensReinos[$i]['Reino']}</button>
            <div style='display: hidden; padding-bottom: 0px;' id='eventos".$itensReinos[$i]['idReino']."' class='panel-body panel-collapse collapse'>
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
    $itens = $db_igot->getTimeLine(20);
    
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
        // Imagem do Guerreiro
        $img = $db_filebox->loadImagem(2, $itens[$i]['idUsuario']);
        $timeline_html .= '
            <li>
                <!-- Timeline - Icone -->
                <img class="img-responsive img-circle" src="'.$img.'" alt="User Image" style="width:50px; height:50px; margin-left: 9px; border: 3px solid #ddd;">
                 
        ';
        // Corpo do Evento
        $timeline_html .= '
                <!-- Timeline - body -->                
                <div class="timeline-item"  style="margin-top: -45px;">
                    <!-- Tipo de Evento -->                    
                    <!--<h3 class="timeline-header"><b>'.$itens[$i]['Guerreiro'].' - '.$itens[$i]['Exercito'].'</b></h3> -->                    
                    <h3 class="timeline-header"><i class="'.$itens[$i]['Icone'].'"></i> <b>'.$itens[$i]['Categoria'].'</b></h3>
                    <!-- Descrição do Evento -->
                    <div class="timeline-body">
                        <b>'.$itens[$i]['Guerreiro']. '<br></b> Do exército de <b> '.$itens[$i]['Exercito'].' </b> '.strtolower($itens[$i]['Mensagem']).' '.$itens[$i]['Tipo'].'<br>
                        <b>Aliança :</b> '.$itens[$i]['NomeAlianca'].'. <b>| Torre :</b> '.$itens[$i]['Torre'].'  <b> | Moedas : </b>'.$itens[$i]['Moedas'].' 
                    </div>
                </div>
            
        ';
    }

    // Monta a Linha do Tempo de certificação
    $itens = $db_igot->getTimeLine(20, "idCategoria=6");   

    // Motagem da Linha do Tempo de certificações
    $timeline_html2="";
    $pkItens = (is_array($itens) ? count($itens) : 0);
    for ($i=0; $i<$pkItens; $i++){
        // Data de Conclusão do Evento
        if($i==0 || ($itens[$i-1]['DataConclusao'] <> $itens[$i]['DataConclusao'])) {
            $timeline_html2 .= '
                <!-- Data -->
                <li class="time-label">
                    <span class="bg-red">
                        '.$itens[$i]['DataConclusao'].'
                    </span>
                </li>
            ';
        }
        // Imagem do Guerreiro
        $img = $db_filebox->loadImagem(2, $itens[$i]['idUsuario']);
        $timeline_html2 .= '
            <li>
                <!-- Timeline - Icone -->
                <img class="img-responsive img-circle" src="'.$img.'" alt="User Image" style="width:50px; height:50px; margin-left: 9px; border: 3px solid #ddd;">
                 
        ';
        // Corpo do Evento
        $timeline_html2 .= '
                <!-- Timeline - body -->                
                <div class="timeline-item"  style="margin-top: -45px;">
                    <!-- Tipo de Evento -->                    
                    <!--<h3 class="timeline-header"><b>'.$itens[$i]['Guerreiro'].' - '.$itens[$i]['Exercito'].'</b></h3> -->                    
                    <h3 class="timeline-header"><i class="'.$itens[$i]['Icone'].'"></i> <b>'.$itens[$i]['Categoria'].'</b></h3>
                    <!-- Descrição do Evento -->
                    <div class="timeline-body">
                        <b>'.$itens[$i]['Guerreiro']. '<br></b> Do exército de <b>'.$itens[$i]['Exercito'].' </b> '.strtolower($itens[$i]['Mensagem']).' '.$itens[$i]['Tipo'].'<br>
                        <b>Aliança :</b> '.$itens[$i]['NomeAlianca'].'. <b>| Torre :</b> '.$itens[$i]['Torre'].' <b> | Moedas : </b>'.$itens[$i]['Moedas'].' 
                    </div>
                </div>
            
        ';
    }

     // Monta a Linha do Tempo de Treinamentos
     $itens = $db_igot->getTimeLine(20, "idCategoria=1");   

     // Motagem da Linha do Tempo de Treinamentos
     $timeline_html3="";
     $pkItens = (is_array($itens) ? count($itens) : 0);
     for ($i=0; $i<$pkItens; $i++){
         // Data de Conclusão do Evento
         if($i==0 || ($itens[$i-1]['DataConclusao'] <> $itens[$i]['DataConclusao'])) {
             $timeline_html3 .= '
                 <!-- Data -->
                 <li class="time-label">
                     <span class="bg-red">
                         '.$itens[$i]['DataConclusao'].'
                     </span>
                 </li>
             ';
         }
         // Imagem do Guerreiro
         $img = $db_filebox->loadImagem(2, $itens[$i]['idUsuario']);
         $timeline_html3 .= '
             <li>
                 <!-- Timeline - Icone -->
                 <img class="img-responsive img-circle" src="'.$img.'" alt="User Image" style="width:50px; height:50px; margin-left: 9px; border: 3px solid #ddd;">
                  
         ';
         // Corpo do Evento
         $timeline_html3 .= '
                 <!-- Timeline - body -->                
                 <div class="timeline-item"  style="margin-top: -45px;">
                     <!-- Tipo de Evento -->                    
                     <!--<h3 class="timeline-header"><b>'.$itens[$i]['Guerreiro'].' - '.$itens[$i]['Exercito'].'</b></h3> -->                    
                     <h3 class="timeline-header"><i class="'.$itens[$i]['Icone'].'"></i> <b>'.$itens[$i]['Categoria'].'</b></h3>
                     <!-- Descrição do Evento -->
                     <div class="timeline-body">
                        <b>'.$itens[$i]['Guerreiro'].'<br></b> Do exército de <b>'.$itens[$i]['Exercito'].' </b> '.strtolower($itens[$i]['Mensagem']).' '.$itens[$i]['Tipo'].'<br>
                        <b>Aliança :</b> '.$itens[$i]['NomeAlianca'].'. <b>| Torre :</b> '.$itens[$i]['Torre'].' <b> | Moedas : </b>'.$itens[$i]['Moedas'].' 
                     </div>
                 </div>
             
         ';
     }

      // Monta a Linha do Tempo de Férias
      $itens = $db_igot->getTimeLine(20, "idCategoria=25");   

      // Motagem da Linha do Tempo de Férias
      $timeline_html4="";
      $pkItens = (is_array($itens) ? count($itens) : 0);
      for ($i=0; $i<$pkItens; $i++){
          // Data de Conclusão do Evento
          if($i==0 || ($itens[$i-1]['DataConclusao'] <> $itens[$i]['DataConclusao'])) {
              $timeline_html4 .= '
                  <!-- Data -->
                  <li class="time-label">
                      <span class="bg-red">
                          '.$itens[$i]['DataConclusao'].'
                      </span>
                  </li>
              ';
          }
          // Imagem do Guerreiro
          $img = $db_filebox->loadImagem(2, $itens[$i]['idUsuario']);
          $timeline_html4 .= '
              <li>
                  <!-- Timeline - Icone -->
                  <img class="img-responsive img-circle" src="'.$img.'" alt="User Image" style="width:50px; height:50px; margin-left: 9px; border: 3px solid #ddd;">
                   
          ';
          // Corpo do Evento
          $timeline_html4 .= '
                  <!-- Timeline - body -->                
                  <div class="timeline-item"  style="margin-top: -45px;">
                      <!-- Tipo de Evento -->                    
                      <!--<h3 class="timeline-header"><b>'.$itens[$i]['Guerreiro'].' - '.$itens[$i]['Exercito'].'</b></h3> -->                    
                      <h3 class="timeline-header"><i class="'.$itens[$i]['Icone'].'"></i> <b>'.$itens[$i]['Categoria'].'</b></h3>
                      <!-- Descrição do Evento -->
                      <div class="timeline-body">
                         <b>'.$itens[$i]['Guerreiro'].'<br></b> Do exército de <b>'.$itens[$i]['Exercito'].' </b> '.strtolower($itens[$i]['Mensagem']).' '.$itens[$i]['Tipo'].'<br>
                         <b>Aliança :</b> '.$itens[$i]['NomeAlianca'].'. <b>| Torre :</b> '.$itens[$i]['Torre'].' <b> | Moedas : </b>'.$itens[$i]['Moedas'].' 
                      </div>
                  </div>
              
          ';
      }

    // Monta o conteúdo do Banner Rotativo com os Próximos Eventos
    $itens = $db_igot->getEventosProximos();
    if($itens !== null) {
        $carousel_html="";
        for ($i=0;$i<count($itens);$i++){
            $img = $db_filebox->loadImagem(3, $itens[$i]['idAlianca']);        
            $carousel_html .= '                
                <div class="item'; if($i==0){$carousel_html .= ' active';} $carousel_html .= '">
                    <img class="d-block w-100" align="center" src='.$img.'>
                    <div class="carousel-caption">
                        <a href="'.$itens[$i]['LinkInscricao'].'" target="_blank" class="banner">
                            '.$itens[$i]['Tipo'].'<br/>
                            '.$itens[$i]['DataInicio'].' a '.$itens[$i]['DataFim'].'
                        </a>
                    </div>
                </div>
            ';        
        }    
    }


//// Ferias///////////

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
	</head>
    
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            <!-- Cabeçalho -->
            <?php include_once "frames/header.php"; ?>

            <!-- Barra Lateral -->
            <?php include_once "frames/sidebar.php"; ?>
            
            <!-- Área de conteúdo da Página -->
            <div class="content-wrapper">
               
                <!-- Conteúdo da Página -->
                <section class="content">
                    <!-- Linha com 3 colunas -->
                    <div class="row">
                        <!-- Coluna à Esquerda -->
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <!-- Cartão do Guerreiro -->
                            <div class="card-container" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div class="card">
                                    <!-- Frente do Cartão -->
                                    <div class="front">
                                        <div class="cover">
                                            <img src="img/rise-up-03.png">
                                        </div>
                                        <div class="user">
                                            <img class="img-circle" src="<?php echo $ImagemPerfil;?>" alt="User Image">
                                        </div>
                                        <div class="content">
                                            <div class="main">
                                                <p class="name"><?php echo $_SESSION['igot']['Guerreiro']['Nome']; ?></p>
                                                <p class="text-center">EXÉRCITO : <?php echo $_SESSION['igot']['Guerreiro']['Exercito']; ?></p>
                                                <div>
                                                    <div class="stats card-atributes" >
                                                        <b><h4><?php echo $QuadroMedalhas['Rank']; ?></h4></b>
                                                        <p>RANKING</P>
                                                    </div>
                                                    <div class="stats card-atributes" >
                                                        <b><h4><?php echo $QuadroMedalhas['TotalMedalhas']; ?></h4></b>
                                                        <p>MEDALHAS</p>
                                                    </div>
                                                    <div class="stats card-atributes">
                                                        <b><h4><?php echo $Card['Moedas'];?></h4></b>
                                                        <p>MOEDAS</p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="stats card-atributes">
                                                        <b><h4><?php echo $Card['Certificacoes'];?></h4></b>
                                                        <p>CERTIFICAÇÕES</P>
                                                    </div>
                                                    <div class="stats card-atributes">
                                                        <b><h4><?php echo $Card['Comandante'];?></h4></b>
                                                        <p>COMANDOS</p>
                                                    </div>
                                                    <div class="stats card-atributes">
                                                        <b><h4><?php echo $Card['Treinamento'];?></h4></b>
                                                        <p>TREINAMENTOS</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="footer">
                                                <i class="fa fa-mail-forward"></i> Aproxime o mouse para girar
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Atrás do Cartão -->
                                    <div class="back">                                       
                                        <div class="content">
                                            <div class="main">
                                    <!-- Profile Image -->
                                    
                                        <div class="teste box-body box-profile">
                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item" style="margin-top:-20px; border: none";>                                                    
                                                    <h4><img width="30px" height="30px" alt="Guerreiro" src="/igot/img/torre-botao-1.png">
                                                    <b>TORRES</b> <a class="titulo"></a></h4>
                                                </li>                                                
                                                <li class="list-group-item">
                                                    <b>Medalhas acumuladas <a class="pull-right" style="color:#dd4b39;"><?php echo $QuadroMedalhas['TotalMedalhas']; ?></a></b>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Posição no quadro <a class="pull-right" style="color:#dd4b39;"><?php echo $QuadroMedalhas['Rank']; ?></a></b>
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
                                                <li class="list-group-item" align="center" style="margin-top:-5px; border: none";>
                                                    <a href="/perfil.php" class="btn btn-sm btn-default">Perfil Completo</a>
                                                </li>
                                            </ul>
                                                    
                                        </div>
                                        <!--<div class="main">
                                                <h4 class="text-center">PERFIL</h4>
                                                <p class="text-center">Na empresa desde ...</p>
                                                <div class="stats-container">
                                                    <div class="stats card-atributes">
                                                        <h4><?php echo $Card['Certificacoes'];?></h4>
                                                        <p>CERTIFICAÇÕES</p>
                                                    </div>
                                                    <div class="stats card-atributes">
                                                        <h4><?php echo $Card['Comandante'];?></h4>
                                                        <p>COMANDOS</p>
                                                    </div>
                                                    <div class="stats card-atributes">
                                                        <h4><?php echo $Card['Treinamento'];?></h4>
                                                        <p>TREINAMENTOS</p>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>                           
                            
                            <!-- MINI-RISE -->
                            <div class="box box-solid" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                
                                <div align="center" class="box-header" style="padding: 2px; background-color:#f7f7f7">                                          
                                     <h5>MINI-RISEs</h5>
                                </div>                                
                                
                                <div class="box-body">
                                    <div class="carousel slide" id="carousel-mini-rise" data-ride="carousel">
                                        
                                        <div class="carousel-inner diminui-img">
                                        <div class="item active">
                                                <img width="100%" alt="Slide 0" src="/img/mini-rise-00.jpg">
                                               
                                            </div>
                                            <div class="item">
                                                <img width="500px" alt="Slide 1" src="/img/mini-rise-01.jpg">
                                            </div>
                                            <div class="item">
                                                <img width="500px" alt="Slide 2" src="/img/mini-rise-02.jpg">
                                            </div>
                                            <div class="item">
                                                <img width="500px" alt="Slide 3" src="/img/mini-rise-03.jpg">
                                            </div>
                                            <div class="item">
                                                <img width="500px" alt="Slide 4" src="/img/mini-rise-04.jpg">
                                            </div>
                                        </div>
                                        <a class="left carousel-control" href="#carousel-mini-rise" data-slide="prev">
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                        <a class="right carousel-control" href="#carousel-mini-rise" data-slide="next">
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                    <br>
                                    <div align="center"> 
                                    | <a href="https://filebox.itone.com.br/owncloud/index.php/s/RJ21jKciiZ4PMhP" target="_blank" style="color:#BE3219"><span class="fa fa-download fa-2x"></span></a>&nbsp;APRESENTAÇÃO | <a href="https://filebox.itone.com.br/owncloud/index.php/s/KkBAo8wSq6UY8CV" target="_blank" style="color:#BE3219"><span class="fa fa-download fa-2x"></span></a> &nbsp;GRAVAÇÃO |
                                    </div>
                                    <hr class="margin-bottom:0; padding-bottom:0">
                                    Novidades do VM World 2019<br>
                                    27|09|19 | Belo Horizonte | Aquarius<br>
                                    <br> <b> Felipe Roque apresentou e compartilhou: </b><br>
                                    <br> - Números do evento
                                    <br> - Por que participar? 
                                    <br> - Lançamentos e novidades
                                    <br> - Novas aquisições
                                    <br> - Conteúdos exclusivos
                                    <br> - Dicas especiais
                                    <br><br>
                                    
                                    

                                </div>
                            </div> 


                            <img style="opacity: 0.5" align="center" width="270%" src="img/rise-logo-01.png">   

                            <!-- Cartão de perfil -->
                        <!--<div class="box box-solid">
                                Frente do Cartão 
                                <div class="box-header">
                                    <i class="ra ra-player ra-2x"></i>&nbsp;<h2 class="box-title">PERFIL</h2>
                                </div>
                                <div class="box-body" id="ListaProgressos">
                                    <?php
                                        if($Guerreiros != "" && $_SESSION['igot']['Guerreiro']['idExercito'] != null){
                                            echo $Guerreiros;
                                        }else{
                                            echo "Você não está em nenhuma torre!";
                                        }
                                    ?>
                                </div>
                            </div>-->
                            <!-- Cartão de certificações -->
                        <!--<div class="box box-solid">
                                 Frente do Cartão 
                                <div class="box-header">
                                    <i class="ra ra-book ra-2x"></i>&nbsp;<h2 class="box-title">CERTIFICAÇÕES</h2>
                                </div>
                                <div class="box-body">
                                    <?php if($Certificacoes != "" && $_SESSION['igot']['Guerreiro']['idExercito'] != null){echo $Certificacoes;}else{echo "Você não possui nenhuma certificação cadastrada!";}?>
                                </div>
                            </div>-->
                            <!-- Cartão de eventos -->
                        <!--<div class="box box-solid">
                                 Frente do Cartão 
                                <div class="box-header">
                                    <i class="ra ra-sword ra-2x"></i>&nbsp;<h2 class="box-title">EVENTOS FUTUROS</h2>
                                </div>
                                <div class="box-body">
                                    <?php if($Eventosfuturos != "" && $_SESSION['igot']['Guerreiro']['idExercito'] != null){echo $Eventosfuturos;}else{echo "Você não possui nenhum evento futuro cadastrado!";}?>
                                </div>
                            </div>-->                           
                        </div>
                        
                        <!-- Coluna Central -->
                        <div class="col-md-6 col-sm-12 col-xs-12" >
                            <div class="box box-solid" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div align="center" class="box-header" style="padding: 5px; background-color:#f7f7f7">                                          
                                     <b><h4>PROFISSIONAIS EM PERÍODO DE FÉRIAS</h4></b>
                                </div>                                
                                <div class="row"  style="padding-top: 10px; padding-left: 15px; padding-right: 15px; padding-buttom: 10px;">                                
                                    <div class="col-md-12">
                                        <div class="col-lg-4 col-xs-6" style="padding-right: 3px; padding-left: 5px; padding-top: 10px;">
                                            <!-- small box -->
                                            <div class="small-box bg-aqua">
                                                <div class="inner">
                                                    <h3><?php echo $totalGuerreirosEmFerias;?></h3>
                                                    <p>Estão de Férias</p>
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
                                        <div class="col-lg-4 col-xs-6" style="padding-right: 3px; padding-left: 5px; padding-top: 10px;">
                                            <!-- small box -->
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                <h3><?php echo  $TotalGuerreiroEmFeriasAgendadas;?></h3>
                                                    <p>Já agendaram</p>
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
                                        <div class="col-lg-4 col-xs-6" style="padding-right: 3px; padding-left: 5px; padding-top: 10px;">
                                            <!-- small box -->
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                <h3><?php echo $totalguerreirosEmFeriasConcluidas;?></h3>
                                                    <p>Já voltaram</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                                <a href="/ferias.php" class="small-box-footer">
                                                    Saiba Mais <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </div>                                                            
                                        </div>
                                    </div>
                                </div>
                            </div>                
                            
                            <!-- Linha do Tempo -->
                            <div class="box box-solid" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div align="center" class="box-header" style="padding: 5px; background-color:#f7f7f7">                                          
                                     <b><h4>LINHA DO TEMPO</h4></b>
                                </div>
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">                                                                                                                                 
                                        <li class="active"><a href="#activity" data-toggle="tab">CERTIFICAÇÕES</a></li>
                                        <li><a href="#Treinamentos" data-toggle="tab">TREINAMENTOS</a></li>
                                        <li><a href="#Ferias" data-toggle="tab">FÉRIAS</a></li>
                                        <li><a href="#Geral" data-toggle="tab">GERAL</a></li>                                            
                                    </ul>
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="activity">                                           
                                            <div class="box-body">
                                                    <b>Categoria do evento :</b> Certificação 
                                                    <br> <b>Guerreiros :</b> Todos de todos os Exércitos 
                                                    <br> <b>Ordem :</b> Cronológica
                                                    <hr>
                                                    <ul class="timeline">
                                                        <!-- Time Line -->
                                                        <?php echo $timeline_html2; ?>
                                                    </ul>
                                                </div>                                               
                                            </div>                                             
                                        <div class="tab-pane" id="Geral">
                                            <div class="box-body">
                                                <b>Categoria do evento :</b> Todos eles 
                                                <br> <b>Guerreiros :</b> Todos de todos os Exércitos 
                                                <br> <b>Ordem :</b> Cronológica
                                                <hr>
                                                <ul class="timeline">
                                                    <!-- Time Line -->
                                                    <?php echo $timeline_html; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="Treinamentos">
                                            <div class="box-body">
                                                <b>Categoria do evento :</b> Treinamento 
                                                <br> <b>Guerreiros :</b> Todos de todos os Exércitos 
                                                <br> <b>Ordem :</b> Cronológica
                                                <hr>
                                                <ul class="timeline">
                                                    <!-- Time Line -->
                                                    <?php echo $timeline_html3; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="Ferias">
                                            <div class="box-body">
                                                <b>Categoria do evento :</b> Férias 
                                                <br> <b>Guerreiros :</b> Todos de todos os Exércitos 
                                                <br> <b>Ordem :</b> Cronológica
                                                <hr>
                                                <ul class="timeline">
                                                    <!-- Time Line -->
                                                    <?php echo $timeline_html4; ?>
                                                </ul>
                                            </div>
                                        </div>              
                                    </div>                                
                                </div> 
                            </div>
                        </div>                                
                                
                                
                        <!-- Coluna à Direita -->
                        <div class="col-md-3 col-sm-12 col-xs-12">                                
                            <!-- Introdução -->
                            <div class="box box-solid" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">                            
                                <div align="center" style="padding: 2px; background-color:#f7f7f7">                                          
                                    <b><h5>RISE</h5></b>
                                </div>
                                <div class="box-body">
                                    <div>
                                        <img class="img-fluid" src="img/rise-home.jpg" alt="RISE" style="max-width:100%; max-height:100%;" >
                                    </div>
                                    <br>
                                    <a href="https://pt.wikipedia.org/wiki/Acr%C3%B3nimo" target="_blank">ACRÔNIMO</a> formado por <b>RI</b>SE-UP <b>SE</b>RVICES.
                                    <hr>
                                    RISE é uma INICIATIVA criada no time de serviços para promover INTEGRAÇÃO, EVOLUÇÃO, SINERGIA e MOTIVAÇÃO entre áreas e pessoas. <br>
                                    <br> Sua estrutura foi dividida em 4 grandes blocos : <br>
                                    <br> 1 - RISE <b>iniciativa</b>;
                                    <br> 2 - RISE <b>comunidade</b>;
                                    <br> 3 - RISE <b>eventos</b> anuais e periódicos;
                                    <br> 4 - RISE <b>plataforma</b>.
                                    <br><br>
                                    <p align="center">
                                        <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#LetraDaMusica">
                                            <i class="fa fa-music"></i> Trilha sonora . . .
                                        </button>
                                    </p>
                                    <!-- Modal Letra da Musica RISE-->
                                    <div class='modal fade' id='LetraDaMusica' style='display:none;'>
                                        <div class='modal-dialog modal-megamenu' align='center' style='height:700px;'>
                                            <div class='modal-content' style='width:800px; !important;'>                                            
                                                <div class='modal-header' align='center' style='height:60px;' >
                                                    <button class='close' aria-label='Close' type='button' data-dismiss='modal'><span class='fa fa-times' aria-hidden='true'></span></button>  
                                                    <h4> VÍDEO E LETRA </h4>
                                                </div>                                                    
                                                <div class='modal-body' align='left'>
                                                    <div class="row">
                                                        <div class="col-sm-6">                                                        
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <iframe width="100%" height="200" src="https://www.youtube.com/embed/fB8TyLTD7EE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                            </div>
                                                            <br>
                                                            <h4><b> LEVANTE-SE | ERGA-SE </b></h4>
                                                            Bem-vindo ao mundo, sem heróis e vilões<br>
                                                            Bem-vindo à guerra que só começamos,<br>
                                                            Então pegue sua arma e enfrente<br>
                                                            Há sangue na coroa, vá e pegue-a<br>
                                                            Você tem uma chance para sair vivo,<br>
                                                            Então mais e mais alto você deve buscá-la<br>
                                                            Está em seus ossos, vá e pegue<br>
                                                            Este é o seu momento, agora é a sua hora, então<br>
                                                            <br>
                                                            <b>Prove a si mesmo e levante-se, levante-se!<br>
                                                            Faça-os lembrar de você! Levante-se!<br>
                                                            Se esforce através da dificuldade e levante-se, levante-se!<br>
                                                            Eles vão lembrar de você! Levante-se!<br></b>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            Bem-vindo à subida, alcance o cume<br>
                                                            Visões dizem que um passo em falso leva ao fim, então<br>
                                                            Mais e mais alto você deve buscar<br>
                                                            Está no seu sangue, vá e pegue<br>
                                                            Este é o seu momento, vá até o céu, vá
                                                            <br><br>

                                                            <b>Prove a si mesmo e levante-se, levante-se!<br>
                                                            Faça-os lembrar de você! Levante-se!<br>
                                                            Se esforce através da dificuldade e levante-se, levante-se!<br>
                                                            Eles vão lembrar de você! Levante-se!<br></b>
                                                            <br>

                                                            Então se acostume, se acostume, vá<br>
                                                            Se acostume, se acostume com o movimento de subida<br>
                                                            Então se acostume, se acostume, vá<br>
                                                            Se acostume, se acostume com o movimento de subida<br>
                                                            E enquanto você luta entre os mortos<br>
                                                            Embrenhado em sujeira, você ainda sabe? Você quer?<br>
                                                            E quando os gigantes chamarem<br>
                                                            Para perguntar qual o seu valor<br>
                                                            Você saberá que vencendo ou morrendo, você vai<br>
                                                            <br>
                                                            
                                                            <b>Prove a si mesmo e levante-se, levante-se!<br>
                                                            Faça-os lembrar de você! Levante-se!<br>
                                                            Se esforce através da dificuldade e levante-se, levante-se!<br>
                                                            Eles vão lembrar de você! Levante-se!</b>
                                                            <br>

                                                            <br>
                                                            Está no seu sangue, vá e pegue, levante-se, levante-se!<br>
                                                            Mais e mais você persegue, levante-se, levante-se!<br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='modal-footer' align='center'>
                                                    <p align="center"> The Glitch Mob, Mako, and The Word Alive | Worlds 2018 - League of Legends.</p>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RISE 19 -->
                            <div class="box box-solid" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                                <div align="center" class="box-header" style="padding: 2px; background-color:#f7f7f7">                                          
                                     <h5>RISE 19</h5>
                                </div>                                
                                <div class="box-body">
                                    <div class="carousel slide" id="carousel-rise-19" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <li class="active" data-slide-to="0" data-target="#carousel-rise-19"></li>
                                            <li data-slide-to="1" data-target="#carousel-rise-19></li>
                                            <li data-slide-to="2" data-target="#carousel-rise-19"></li>
                                            <li data-slide-to="3" data-target="#carousel-rise-19"></li>
                                        </ol>
                                        <div class="carousel-inner diminui-img">
                                            <div class="item active">
                                                <img alt="Slide 1" src="/igot/img/rise19-1.png">
                                                <div class="carousel-caption">
                                                    INTEGRAÇÃO
                                                </div>
                                            </div>
                                            <div class="item">
                                                <img alt="Slide 2" src="/igot/img/rise19-2.png">
                                                <div class="carousel-caption">
                                                    EVOLUÇÃO
                                                </div>
                                            </div>
                                            <div class="item">
                                                <img alt="Slide 3" src="/igot/img/rise19-3.png">
                                                <div class="carousel-caption">
                                                    SINERGIA
                                                </div>
                                            </div>
                                            <div class="item">
                                                <img alt="Slide 4" src="/igot/img/rise19-4.png">
                                                <div class="carousel-caption">
                                                    MOTIVAÇÃO
                                                </div>
                                            </div>
                                        </div>
                                        <a class="left carousel-control" href="#carousel-rise-19" data-slide="prev">
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                        <a class="right carousel-control" href="#carousel-rise-19" data-slide="next">
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                        <br>SEGUNDA edição do RISE, o evento.<br>
                                        27/06/19 e 28/06/19 | Belo Horizonte | Espaço INCASA<br>
                                        <br> - Participações de TODO o time de SERVIÇOS;
                                        <br> - Palestras das lideranças;
                                        <br> - Divulgação de resultados;
                                        <br> - Dinâmicas de grupo com a HR Office;
                                        <br> - Acolhida dos novos profissionais;
                                        <br><br>
                                </div>
                            </div>
                            <!-- RISE 18 -->
                            <div class="box box-solid" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">                                
                                
                                <div align="center" class="box-header" style="padding: 2px; background-color:#f7f7f7">                                          
                                     <h5>RISE 18</h5>
                                </div>

                                <div class="box-body">
                                    <div class="carousel slide" id="carousel-rise-18" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <li class="active" data-slide-to="0" data-target="#carousel-example-generic3"></li>
                                            <li data-slide-to="1" data-target="#carousel-rise-18"></li>
                                            <li data-slide-to="2" data-target="#carousel-rise-18"></li>
                                            <li data-slide-to="3" data-target="#carousel-rise-18"></li>
                                        </ol>
                                        <div class="carousel-inner diminui-img">
                                            <div class="item active">
                                                <img alt="Slide 1" src="/igot/img/rise18-1.jpg">
                                                <div class="carousel-caption">
                                                    INTEGRAÇÃO
                                                </div>
                                            </div>
                                            <div class="item">
                                                <img alt="Slide 2" src="/igot/img/rise18-2.jpg">
                                                <div class="carousel-caption">
                                                    EVOLUÇÃO
                                                </div>
                                            </div>
                                            <div class="item">
                                                <img alt="Slide 3" src="/igot/img/rise18-3.jpg">
                                                <div class="carousel-caption">
                                                    SINERGIA
                                                </div>
                                            </div>
                                            <div class="item">
                                                <img alt="Slide 4" src="/igot/img/rise18-4.jpg">
                                                <div class="carousel-caption">
                                                    MOTIVAÇÃO
                                                </div>
                                            </div>
                                        </div>
                                        <a class="left carousel-control" href="#carousel-rise-18" data-slide="prev">
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                        <a class="right carousel-control" href="#carousel-rise-18" data-slide="next">
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                        
                                        <br>PRIMEIRA edição do RISE, o evento.<br>
                                        07/08/18 e 08/08/18 | Belo Horizonte | Hotel BHB<br>
                                        <br> - Participações de ISE, IPM e ICS;
                                        <br> - Palestras das lideranças;
                                        <br> - Divulgação de resultados;
                                        <br> - Dinâmicas de grupo no Escape 60;
                                        <br> - Acolhida dos novos profissionais;
                                        <br> - Apresentações e depoimentos;
                                        <br> - Premiações.<br><br>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </section>
			</div>

            <!-- Rodapé -->
            <?php include_once "frames/footer.php"; ?>

            <!-- Painel de Controle -->
            <?php include_once "frames/controlpanel.php"; ?>

            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal" style="display:none;">
            <div class="modal-dialog modal-megamenu">
                <div class="modal-content">
                    <form role="form">
                        <div class="modal-header" id="modalHeader"></div>
                        <div class="modal-body" id="modalBody"></div>
                        <div class="modal-footer" id="modalFooter"></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Page Custom script -->
        <script>
            // Modal - Popula e exibe o modal com os Próximos Eventos
            function showModalEventosProximos(){
                var pagPart = $('#modalBody'); // Restringe os objetos em uma parte específica da página
                // Carrega o Evento selecionado
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    content = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    content = new ActiveXObject("Microsoft.XMLHTTP");
                }
                // Popula o Body do Modal
                content.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("modalBody").innerHTML = this.responseText;
                    }
                };
                content.open("GET", '/igot/config/results.php?return=EventosTipos&proximos=true&type=Table&sort=DataInicio_YMD DESC, Tipo', true);
                content.send();

                // Popula a barra de Título
                document.getElementById("modalHeader").innerHTML =
                    '<button class="close" aria-label="Close" type="button" data-dismiss="modal"><span class="fa fa-times" aria-hidden="true"></span></button>'
                    +'<h4 class="modal-title" align="center">Próximos Eventos</h4>';
                
                // Exibe o Modal
                $('#modal').modal('show');
            }
        </script>
    </body>
</html>
 