<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }
    
    // Inclue objetos referentes a bases de dados
    include_once "config/db.php";

    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
    $db_filebox = new filebox(); 

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
               
               case "Rejeitado":
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
                       <td>
                        <div class="tooltip edit"> 
                            <button type="button" id="btn'.$itens[$i]['id'].'" class="btn btn-warning btn-sm edit" data-toggle="modal" data-target="#modalEditar"><span class="glyphicon glyphicon-edit"></span></button>
                            <span class="tooltiptext">Editar Férias</span>
                        </div>
                        <td>
                    </tr>
                   
                   ';
               break;
           }
           
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
                
                case "Rejeitado":
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
                
                case "Rejeitado":
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
                
                case "Rejeitado":
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
                
                case "Rejeitado":
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
    
    
     // Obtém as Solicitações para adm

    $html_solicitaparaadm ="";
    $itens = $db_igot->getSolicitacaoFeriasParaAdm();
    if($itens == null){
       $html_solicitaparaadm = "<tr>Você não possui férias para aprovar</tr>";       
    } else {        
    $html_solicitaparaadm = "";
       for ($i=0; $i<count($itens); $i++){                     
           $contador = $i +1;         
           $html_solicitaparaadm .= '
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
               
               case "Rejeitado":
                    $html_solicitaparaadm .= '
                       <td><span class="badge bg-red">Reprovado</span></td>
                       
                    </tr>
                   
                   '; 
               break;
               case "Aprovado":
                    $html_solicitaparaadm.= '
                       <td><span class="badge bg-green">Aprovado</span></td>                   
                   ';
               break;
               case "Pendente":
                    $html_solicitaparaadm .= '
                       <td><span class="badge bg-yellow">Pendente</span></td>
                       <td>
                            <div class="tooltip recusa">
                                <button type="button" onclick="recusa('.$itens[$i]['id'].')" id="recusa'.$itens[$i]['id'].'" class="btn btn-danger btn-sm edit"><span class="fa fa-remove"></span></button>
                                <span class="tooltiptext">Recusar</span>
                            </div>
                            <div class="tooltip aceita">                            
                                <button type="button" onclick="aprova('.$itens[$i]['id'].')"  id="aprova'.$itens[$i]['id'].'" class="btn btn-success btn-sm edit"><span class="glyphicon glyphicon-ok"></span></button>
                                <span class="tooltiptext">Aprovar</span>
                            <div> 
                        <td>
                    </tr>                   
                   ';
               break;
           }
           
       }
   }
   $html_admin="";
   // permissao para adm
   if($_SESSION['igot']['groups']['admin'] == true){
    $html_admin .='

    <div class="box">
    <div class="box-header">
        <h3 class="box-title ">Aprovação de Férias</h3>
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
                    <th>Ações</th>
                </tr>';
                
                $html_admin .= $html_solicitaparaadm;

                $html_admin .='   
            </tbody>
        </table>
    </div>                                                               
</div>';
                                        
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
        
        <script src="/stylesheet/AdminLTE/2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>

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
            <?php include_once "frames/header.php";?>
            <!-- Barra Lateral -->
            <?php include_once "frames/sidebar.php";?>
            <div class="content-wrapper">                
                <section class="content-header">                    
                    <h4>PROFISSIONAIS EM PERÍODO DE FÉRIAS</h4>              
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> HOME</a></li>
                        <li class="active">FÉRIAS</li>
                    </ol>
                </section>        
                <section class="content">                   
                    <div class="box" style="box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
                        <section class="content">
                            <div class="row">                                
                                <div class="col-md-12">
                                    <br>
                                    <div class="col-lg-4 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-aqua">
                                            <div class="inner">
                                                <h3><?php echo $totalGuerreirosEmFerias;?></h3>
                                                <p>Estão de férias</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-camera"></i>
                                            </div>
                                            <a href="#GuerreirosEmPeriodoDeFerias" class="small-box-footer">
                                                Ver quais <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-4 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-green">                                        
                                            <div class="inner">                                                
                                            <h3><?php echo  $TotalGuerreiroEmFeriasAgendadas;?></h3>
                                                <p>Já agendaram</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-stats-bars"></i>
                                            </div>
                                            <a href="#GuerreiroEmPeridoAgendado" class="small-box-footer">
                                                Ver quais <i class="fa fa-arrow-circle-right"></i>
                                            </a>                                          
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-4 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-yellow">
                                            <div class="inner">
                                            <h3><?php echo $totalguerreirosEmFeriasConcluidas;?></h3>
                                                <p>Já voltaram</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-users"></i>
                                            </div>
                                            <a href="#GuerreiroComPeriodoConcluido" class="small-box-footer">
                                                Ver quais <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>                                                            
                                    </div>
                                    <div class="col-sm-12">
                                        <?php echo $html_admin; ?>                                        
                                        <div class="box">
                                            <div class="box-header">
                                                <h3 class="box-title ">Minhas Solicitações</h3>
                                                <button type="button" class="btn btn-sm btn-default btn-flat"  data-toggle="modal" data-target="#ferias_modal" for='ferias'style="margin-left:10px;">Solicitar Férias</button>
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
                                                            <th>Ações</th>
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
                                        <div class="box">
                                            <div class="box-header">
                                                <a name="GuerreirosEmPeriodoDeFerias"></a>
                                                <h3 class="box-title">Guerreiros em período de férias</h3>                                                                   
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body no-padding">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width: 10px">#</th>
                                                            <th>Guerreiro</th>
                                                            <th>Data da solictação</th>
                                                            <th>Inicio das férias</th> 
                                                            <th>Fim das férias</th>
                                                            <th>Status</th>
                                                        </tr>
                                                        <?php echo $html_guerreiroEmFerias;?>                                                        
                                                    </tbody>
                                                </table>
                                            </div>                                                               
                                        </div>                                                            
                                        <div class="box">
                                            <a name="GuerreiroEmPeridoAgendado"></a>
                                            <div class="box-header">
                                                <h3 class="box-title">Guerreiros com períodos de férias agendados</h3>                                                                   
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body no-padding">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width: 10px">#</th>
                                                            <th>Guerreiro</th>
                                                            <th>Data da solictação</th>
                                                            <th>Inicio das férias</th> 
                                                            <th>Fim das férias</th>
                                                            <th>Status</th>
                                                        </tr>
                                                        <?php echo  $html_guerreiroEmFeriasAgendadas;?>                                                                  
                                                    </tbody>
                                                </table>
                                            </div>                                                               
                                        </div>                                                            
                                        <div class="box">
                                            <a name="GuerreiroComPeriodoConcluido"></a>
                                            <div class="box-header">
                                                <h3 class="box-title">Guerreiros com períodos de férias concluídos</h3>                                                                    
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body no-padding">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width: 10px">#</th>
                                                            <th>Guerreiro</th>
                                                            <th>Data da solictação</th>
                                                            <th>Inicio das férias</th> 
                                                            <th>Fim das férias</th>
                                                            <th>Status</th>
                                                        </tr>
                                                        <?php echo  $html_guerreirosEmFeriasConcluidas;?> 
                                                    </tbody>
                                                </table>
                                            </div>                                                               
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>      
                    </div>
                </section>
            </div>
        </section>

        <!-- modal de ferias-->
        <div class='modal fade' id='ferias_modal' style='display:none;'>
            <div class='modal-dialog modal-megamenu' align='center' style='height:700px;'>
                <div class='modal-content'>                                        
                    <div class='modal-header' align='center'>
                        <button class='close' aria-label='Close' type='button' data-dismiss='modal'><span class='fa fa-times' aria-hidden='true'></span></button>  
                        <h4> SOLICITAR FÉRIAS </h4>
                    </div>                                                
                    <div class='modal-body' align='left' id="ferias_modalBody">
                        <div class="row">
                            <div class="col-md-6">                                           
                                <div class="form-group">
                                    <div class="form-group" id="yesQuestion" >
                                        <label>Tipo de férias</label>
                                        <select class="editInput form-control select2 Tipo" name="idTipo">
                                            <?php echo $optEventosTipos; ?>                
                                        </select>
                                    </div>
                                </div>                                                            
                                <div class="form-group">
                                    <div class="form-group" id="yesQuestion" >
                                        <label>Data de inicio das férias</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="editInput form-control form-field" name="DataInicioEvento" id="DataInicioEvento" type="date" min="<?php echo date("Y-m-d"); ?>" value="">
                                        </div>
                                    </div>
                                </div>                                                                                                             
                            </div>
                            <div class="col-md-6">                                                            
                                <div class="form-group">
                                    <div class="form-group" id="yesQuestion" >
                                        <label>Status de aprovação</label>
                                        <select class="form-control">
                                            <option>Pendente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group" id="yesQuestion" >
                                        <label>Data de fim das férias</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="editInput form-control form-field" name="DataConclusao" id="DataConclusao" type="date" min="<?php echo date("Y-m-d"); ?>" value="">
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                    </div>   
                    <div class='modal-footer' align='center'>
                        <button data-dismiss="modal" type="button" class="btn btn-default btnCancel"><span class="fa fa-undo"></span> Cancelar</button>
                        <button onclick="SaveFerias()" id="btnSave" type="button" class="btn btn-success btnSave"><span class="fa fa-save"></span> Salvar</button>
                    </div>                                            
                </div>
            </div>
        </div>                            
        <div class="modal fade" id="modalEditar">
            <div class='modal-dialog modal-megamenu' align='center' style='height:700px;'>
                <div class='modal-content'>                                        
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Editar Registro </h4>
                    </div>
                    <div class="modal-body">                                        
                        <div class="row">
                            <div class="col-md-6">                                           
                                <div class="form-group">
                                <input class="editInput form-control Tipo editarEvento" id="idEvento" type="text" value="" name="id" style="display:none;">
                                    <div class="form-group" id="yesQuestion">
                                        <label>Tipo de férias</label>
                                        <input class="editInput form-control form-field editarEvento"  disabled id="TipoFerias" type="text" value="">
                                        <select class="editInput form-control select2 Tipo editarEvento" name="idTipo" id="idtipo">                                                                
                                            <?php echo $optEventosTipos; ?>                
                                        </select>
                                    </div>
                                </div>                                                            
                                <div class="form-group">
                                    <div class="form-group" id="yesQuestion" >
                                        <label>Data de inicio das férias</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="editInput form-control form-field editarEvento" disabled id="InicioFerias" type="text" value="">
                                            <input class="editInput form-control form-field editarEvento"  name="DataInicioEvento" id="DataInicioEvento1" type="date"  min="<?php echo date("Y-m-d"); ?>" value="">
                                        </div>
                                    </div>
                                </div>                                                                                                             
                            </div>
                            <div class="col-md-6">                                                            
                                <div class="form-group">
                                    <div class="form-group" id="yesQuestion" >
                                        <label>Status de aprovação</label>
                                        <select class="form-control">
                                            <option> Pendente</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <div class="form-group" id="yesQuestion" >
                                        <label>Data de fim das férias</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="editInput form-control form-field editarEvento" disabled id="FimFerias" type="text" value="">
                                            <input  class="editInput form-control form-field editarEvento" name="DataConclusao" type="date"  id="DataConclusao1" min="<?php echo date("Y-m-d"); ?>" value="">
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button onclick="UpdateEventos(this.id)" id="btnUpd'+ID+'" type="button" class="btn btn-success btnUpdate"><span class="fa fa-save"></span> Salvar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
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

<script>
    // Salva a Solicitação de Férias
    function SaveFerias() {
        var frObj = $('#ferias_modalBody'); // Variável para resumo na identificação do Corpo do Modal
        var inputData = frObj.find('.editInput').serialize(); // Variável para os valores informados no Formulário do Modal

        dataInicio = document.getElementById('DataInicioEvento');
        dataFim = document.getElementById('DataConclusao'); 
        if(dataInicio.value == ""){
        alert('O campo Data de inicio das férias não pode ficar vazio!');
        }
        else if(dataFim.value == ""){
        alert('O campo Data de fim das férias não pode ficar vazio!');
        }
        else{
            $.ajax({
                url: 'igot/admin/ajax/updEventos.php',
                type:'POST',
                dataType: "json",
                data: 'action=insert&'+inputData+'&idGuerreiro='+<?php echo $_SESSION['igot']['Guerreiro']['id']; ?>+'&idTorre=195&idStatus=10',
                success:function(response){
                    alert(response.msg);
                    // Fecha o Modal
                    $('#ferias_modal').modal('hide');
                    location.reload();
                }
            });
        }
    }    

    //pega valores da tabela e joga no modal de editar eventos
    $('body').on("click", ".edit", function() {
        $('#idEvento').val($(this).parents('tr').find('td').eq(1).text());
        $('#TipoFerias').val($(this).parents('tr').find('td').eq(4).text());
        $('#InicioFerias').val($(this).parents('tr').find('td').eq(5).text());
        $('#FimFerias').val($(this).parents('tr').find('td').eq(6).text());
        $(".btnUpdate").attr("idUpdate", idUpdate);
    });

    function UpdateEventos(idUpdate) {        
        var frObj = $('#modalEditar'); // Variável para resumo na identificação do Corpo do Modal
        var inputData = frObj.find('.editarEvento').serialize(); // Variável para os valores informados no Formulário do Modal

        dataInicio = document.getElementById('DataInicioEvento1');
        dataFim = document.getElementById('DataConclusao1'); 
        if(dataInicio.value == ""){
        alert('O campo Data de inicio das férias não pode ficar vazio!');
        }
        else if(dataFim.value == ""){
        alert('O campo Data de fim das férias não pode ficar vazio!');
        }

        else{
            $.ajax({
                url: 'igot/admin/ajax/updEventos.php',
                type:'POST',
                dataType: "json",
                data: 'action=update&'+inputData+'&idGuerreiro='+<?php echo $_SESSION['igot']['Guerreiro']['id']; ?>+'&idTorre=195&idStatus=10',
                success:function(response){
                    alert(response.msg);
                    // Fecha o Modal
                    $('#modalEditar').modal('hide')
                    location.reload();
                }
            });
        }
    }

    function recusa(id) {
        $.ajax({
            url: 'igot/admin/ajax/updFerias.php',
            type:'POST',
            dataType: "json",
            data: 'action=updateRecusa&idEvento='+id,
            success:function(response){
                alert("Ferias recusada com sucesso!!");                    
                location.reload();                
            }
        });        
    }

    function aprova(id) {        
        $.ajax({
            url: 'igot/admin/ajax/updFerias.php',
            type:'POST',
            dataType: "json",
            data: 'action=updateAprova&idEvento='+id,
            success:function(response){
                alert("Ferias aceita com sucesso!!");                    
                location.reload();                
            }
        });        
    }        

</script>