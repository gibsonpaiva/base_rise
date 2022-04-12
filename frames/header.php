<?php
    // Inicia uma Sessão se ainda não tiver iniciado
    if(session_id() == '') { session_start(); }

    // Verifica se o usuário está autenticado
	if(isset($_SESSION['user']['id'])) {
        // Inclue objetos referentes a bases de dados
        include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
        //Obtém Imagem do Perfil
        $db_filebox = new filebox();
        $ImagemPerfil = $db_filebox->loadImagem(2, $_SESSION['user']['id']);
    } else {
        // Caso não esteja conectado, redireciona para a página inicial
        header("Location: /index.php");   } 
        
        // Instancia os objetos referente a base de dados
        $db_igot = new IGOT();
        $db_filebox = new FileBox();

        // Obtém os Próximos Eventos e a contagem de eventos respectivamente
        $html ="";
        
        $itens = $db_igot->getEventosProximos();
        $itens2 = $db_igot->getContagemEventos();    

        // se existir eventos 
        if($itens !== null){

            for ($i=0;$i<count($itens2);$i++){
                $itens2 = $db_igot->getContagemEventos();
                $html .= '
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa  fa-clock-o fa-lg"></i>
                    <span class="label label-success"> '.$itens2[$i]['qtdeEventos'].'</span>
                </a>
                <ul class="dropdown-menu" style="width:auto;">               
                <li class="header" align="center" style="font-size:12px"> <strong><b>'.$itens2[$i]['qtdeEventos'].'</b></strong> evento(s) próximo(s) para se registrar. </li>
                '; 
            } 
         
            for ($i=0;$i<count($itens);$i++){
               
                $img = $db_filebox->loadImagem(3, $itens[$i]['idAlianca']);        
                $html .= '               
                 
                <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <li><!-- start message -->
                            <a href="'.$itens[$i]['LinkInscricao'].'" target="_blank">
                                <div class="pull-left" style="width:auto">
                                    <!-- <img " src='.$img.' class="img-circle" alt="User Image"> -->
                                    <img src="/igot/img/eventos-2.png" alt="Eventos">
                                </div>
                                <h4>
                                    <b>'.$itens[$i]['Categoria'].' '.$itens[$i]['Alianca'].'</b>
                                    <br><small style="position:relative;"> <b> '. $itens[$i] [ 'DataInicio'].' à '. $itens[$i] [ 'DataFim'].' </b></small>
                                </h4>
                                <p>'.$itens[$i]['Tipo'].'</p>
                            </a>
                        </li>                
                    </ul>
                </li>        
                ';        
            }
            // Fecha o módulo de eventos 
            $html .= '
                <li class="footer"><a  onclick="showModalEventosProximos()" class="btn btn-sm btn-default" type="button" style="border: none; padding: 5px;"> + Ver todos os Eventos</a></li>
            </ul>      
                
            ';   
        }
        // se nao existir eventos
    else {
    
        $html .= '
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-clock-o fa-lg"></i>                    
        </a>
        <ul class="dropdown-menu">               
            <li class="header"> Não há evento(s) próximo(s)! </li>
        </ul>
        '; 

    }    
?>

<header class="main-header">
    <!-- Título ou Logo do Site -->
    <a href="/index.php" class="logo">
        <!-- Título ou Logo minimizado (50x50 pixels) -->
        <span class="logo-mini">RISE</span>
        <!-- Título ou Logo em tamanho normal -->
        <span class="logo-lg"><b>RISE</b></span>                    
    </a>
    <!-- Barra de Navegação superior -->
    <nav class="navbar navbar-static-top">
        
        <!-- Botão para minimizar o menu lateral (toggle button)  -->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">|||</span>
        </a>
        
        <!-- Menu à direita -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                
                <!-- Logo da ITOne -->
                <a id="imagem" href="/index.php">
                    <img align="left" class="img-logo" src="/img/logo_itone_fundoescuro.png" alt="IT-One">
                </a>

                <!-- Usuário corrente -->
                <li class="dropdown user user-menu">
                    <a href="/perfil.php" class="dropdown-toggle">
                      <img src="<?php echo $ImagemPerfil;?>" class="user-image" alt="User Image">
                      <span class="hidden-xs"><?php echo $_SESSION['user']['name']; ?></span>
                    </a>
                </li>

                <!-- Eventos Proximos -->
                <li class="dropdown messages-menu" style="width:auto;">
                    <?php echo $html ?>
                </li>

                <!-- CRÉDITOS -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown"><span class="glyphicon glyphicon-star fa-lg"></span></a> 

                    <div class="box box-widget dropdown-menu widget-user-2" style="width:800px;">
                           
                        <div class="col-md-12" align="center" style="padding: 2px; background-color:#F7F7F7">                                          
                            <h5><b>CRÉDITOS DO RISE | PLATAFORMA</b></h5>
                        </div>
                            
                        <div class="col-md-12">
                            <div class="col-md-4">                            
                                <div class="box-body box-profile" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">
                                    <h5 class="text-muted text-center" style="font-size:14px;">Desenvolvimento</h5>
                                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $db_filebox->loadImagem(2,63);?>" style='border: 0px solid #dddd; width:70px;'>
                                    <h4 class="profile-username text-center" style="font-size:18px;">Felipe Baeta</h4>
                                </div> 
                            </div>
                            <div class="col-md-4">                            
                                <div class="box-body box-profile" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">
                                    <h5 class="text-muted text-center" style="font-size:14px;">Líder de desenvolvimento</h5>
                                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $db_filebox->loadImagem(2,1);?>" style='border: 0px solid #dddd; width:70px;'>
                                    <h4 class="profile-username text-center" style="font-size:18px;">Rafael Teixeira</h4>
                                </div>                                      
                            </div>
                            <div class="col-md-4">                            
                                <div class="box-body box-profile">
                                    <h5 class="text-muted text-center" style="font-size:14px;">Criador da iniciativa</h5>
                                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $db_filebox->loadImagem(2,2);?>" style='border: 0px solid #dddd; width:70px;'>
                                    <h4 class="profile-username text-center" style="font-size:18px;">Ricardo Paiva</h4>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-12" align="center" style="padding: 2px; background-color:#F7F7F7">                                          
                            <h5><b>TERRITÓRIOS ATIVOS</b></h5>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="box-body box-profile" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">
                                    <h5 class="profile-username text-center" style="font-size:18px;"><b>RISE</b></h5>             
                                </div>          
                            </div>
                            <div class="col-md-6">                            
                                <div class="box-body box-profile">
                                    <h5 class="profile-username text-center" style="font-size:18px;"><b>GAME OF TOWERS</b></h5>             
                                </div>                                      
                            </div>
                        </div>
                        <div class="col-md-12" align="center" style="padding: 2px; background-color:#F7F7F7">                                          
                            <h5><b>AMBIENTES DISPONÍVEIS</b></h5>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="box-body box-profile" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">
                                    <h5 class="text-muted text-center" style="font-size:18;"><b>3 : DEV</b></h5>              
                                </div>          
                            </div>
                            <div class="col-md-4">                            
                                <div class="box-body box-profile" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">
                                    <h5 class="text-muted text-center" style="font-size:18;"><b>1 : HML</b></h5>             
                                </div>                                      
                            </div>
                            <div class="col-md-4">                            
                                <div class="box-body box-profile">
                                    <h5 class="text-muted text-center" style="font-size:18;"><b>1 : PRD</b></h5>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-12" align="center" style="padding: 2px; background-color:#F7F7F7;">                                          
                            <h5><b>TECNOLOGIAS EMPREGADAS</b></h5>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-3" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">
                                <div class="box-body box-profile">
                                    <h4 class="profile-username text-center" style="font-size:18px;" >+ LINUX</h4>
                                    <h5 class="text-muted text-center" style="font-size:14px;">+ Shell Script</h5>              
                                </div>          
                            </div>
                            <div class="col-md-3" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">                            
                                <div class="box-body box-profile">
                                    <h4 class="profile-username text-center" style="font-size:18px;">+ APACHE</h4>
                                    <h5 class="text-muted text-center" style="font-size:14px;"> + HTML + CSS</h5>              
                                </div>                                      
                            </div>
                            <div class="col-md-3" style="border-width: thin; border-style: ridge; border-left-style: none; border-bottom-style: none; border-top-style: none;">                            
                                <div class="box-body box-profile">
                                    <h4 class="profile-username text-center" style="font-size:18px;">+ PHP</h4>
                                    <h5 class="text-muted text-center" style="font-size:14px;">+ Bootstrap + Ajax</h5>              
                                </div>                                      
                            </div>
                            <div class="col-md-3">                            
                                <div class="box-body box-profile">
                                    <h4 class="profile-username text-center" style="font-size:18px;">+ MYSQL</h4>
                                    <h5 class="text-muted text-center" style="font-size:14px;"> + Java Script + JSON</h5>
                                </div> 
                            </div>
                        </div>
                        
                        <div class="col-md-12" align="center" style="padding: 10px; border-width: thin; border-style: ridge; border-left-style: none; border-right-style: none;">                                          
                            +|+ <a class="fa fa-lock fa-lg" style="color:green;"></a>  |  Você está navegando em um site seguro, com certificado digital assinado pela GlobalSign Root CA  +|+
                        </div>
                    </div> 
                </li>
                
                <?php
                    // Identifica o território
                    if(substr_count($_SERVER['PHP_SELF'], '/')>1){
                        $territorio = substr($_SERVER['PHP_SELF'], 1, strpos(substr($_SERVER['PHP_SELF'],1), '/'));
                        if($territorio == "admin"){ $territorio = "rise"; } // Atribui o território RISE caso trata-se de pág. Administrativa
                    } else {
                        $territorio = "rise";
                    }
                    if($_SESSION[$territorio]['groups']['admin']) { echo '
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="glyphicon glyphicon-cog fa-lg"></i></a>
                        </li>';
                    }
                ?>
                <li>
                    <a href="#" data-toggle="modal" data-target="#Sair"><span class="fa fa-sign-out fa-lg"></span></a>                        
                </li>
            </ul>
        </div>                   
    </nav>                
</header>

<div class="modal fade" id="Sair" tabindex="-1" role="dialog" aria-labelledby="Sair" aria-hidden="true">
  <div class="modalPart1" role="document">
    <div class="modalPart2">
        <div class="col-1 col-md-1">
        
        </div>
        <div class="col-7 col-md-7">
            <h5 class="modalPart4" id="TituloModalCentralizado"><span class="fa fa-sign-out"></span> LOGOUT</h5>
            <div class="modalPart5">          
                Obrigado pela visita! Deseja sair agora? <br><br>
            </div>       
        
            <div class="pull-right" style="width: 100%; float: left; padding: 10px 0px; margin-top:-85px;">
                <button type="button" class="btn btn-success btn-lg pull-right" data-dismiss="modal">Não</button>
                <a href="/config/logout.php" type="button" class="btn btn-danger btn-lg pull-right" style="margin-right: 5px;">Sim</a>               
            </div>
        </div>
        <div class="col-4 col-md-4">
        


        </div>  
       

        <!--<h5 class="modalPart4" id="TituloModalCentralizado"><span class="fa fa-sign-out"></span> LOGOUT</h5>
        </button>
      </div>
      <div class="modalPart5">          
        <strong> Tem certeza que deseja sair? <br><br></strong>
      </div>
      <div class="pull-right" style="width: 100%; float: left; padding: 10px 0px;">
        <button type="button" class="btn btn-success btnSave pull-right" data-dismiss="modal">Fechar</button>
        <a href="/config/logout.php" type="button" class="btn btn-default btn pull-right" style="margin-right: 5px;">Sim</a>    -->           
      
    </div>
  </div>
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
        content.open("GET", '/igot/config/results.php?return=EventosTipos&proximos=true&type=Table&sort=DataInicio_YMD ASC, Tipo', true);
        content.send();

        // Popula a barra de Título
        document.getElementById("modalHeader").innerHTML =
            '<button class="close" aria-label="Close" type="button" data-dismiss="modal"><span class="fa fa-times" aria-hidden="true"></span></button>'
            +'<h4 class="modal-title" align="center">Próximos Eventos</h4>';
        
        // Exibe o Modal
        $('#modal').modal('show');
    }
</script>

<script>
    // Aplica o ultimo estado do menu
    (function () {
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            var body = document.getElementsByTagName('body')[0];
            body.className = body.className + ' sidebar-collapse';
        }
    })();

   // Guarda o Estado do Menu em memória
    $('.sidebar-toggle').click(function(event) {
        event.preventDefault();
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
        } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
        }
    });
    
    function UpdatePerfil(){
        var postURL = "/admin/ajax/upload.php";
        var pagPart = $('#perfil_modalBody'); // Restringe os objetos em uma parte específica da página
        var inputData = new FormData(); // Variável para armazenamento dos dados do Formulário
        inputData.append('action', 'insert'); // Concatena a ação a ser realizada
        inputData.append('idArea', <?php echo RISE_USUARIOS; ?>); // Concatena o ID para Identificação da Área
        inputData.append('idTipo', '6'); // Concatena o Tipo de Arquivo
        inputData.append('Descricao', 'Imagem de Perfil'); // Concatena a descrição do arquivo
        inputData.append('fileToUpload', $('#perfil_Arquivo')[0].files[0]); // Concatena o arquivo selecionado
        $.ajax({
            url: postURL,
            type:'POST',
            dataType: 'json',
            data: inputData,
            cache: false,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            beforeSend: function(){
                // Prepara animação de atualização -> pagPart.find(".Uploading").html('Uploading...');
                // Exibe animação de atualização -> pagPart.find(".Uploading").show();
            },
            success:function(response){
                // Atualiza as imagens do perfil que possuirem o parâmetro alt="User Image"
                $("[alt='User Image']").attr("src", "data:"+response.data.Formato+";base64,"+response.data.ArquivoB64);
                //pagPart.find().html();
                $('#perfil_modal').modal('hide'); // Fecha o Modal
            }
        });
    }    
</script>