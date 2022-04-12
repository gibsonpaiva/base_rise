
<?php
    // Inclue objetos referentes a bases de dados
    include_once "config/db.php";
   
    // Instancia os objetos referente a base de dados
    $db_igot = new IGOT();
    $db_filebox = new FileBox();

    // Obtém os Próximos Eventos
    $itens = $db_igot->getEventosProximos();

    // Verifica se há Eventos Próximos
    if($itens !== null){
        // Monta o módulo de Eventos Próximos
        $html = '
            <div class="box box-solid">
                                
                <div align="center" class="box-header" style="padding: 2px; background-color:#F7F7F7">                                          
                        <h5>PRÓXIMOS EVENTOS</h5>
                </div>
                
                <div class="box-body">
                    <div class="carousel slide" id="carousel-example-generic2" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-slide-to="0" data-target="#carousel-example-generic2"></li>
                            <li class="active" data-slide-to="1" data-target="#carousel-example-generic2"></li>
                            <li data-slide-to="2" data-target="#carousel-example-generic2"></li>
                        </ol>
                        <div class="carousel-inner">    
        ';
        // Popula o Carrocel
        for ($i=0;$i<count($itens);$i++){
            $img = $db_filebox->loadImagem(3, $itens[$i]['idAlianca']);        
            $html .= '                
                            <div class="item'; if($i==0){$html .= ' active';} $html .= '">
                                <div class="carousel-caption carousel-caption-title">
                                    <a target="_blank" class="banner" style="color: #000; font-size: 15px;">
                                        '.$itens[$i]['Categoria'].'                                         
                                    </a>
                                </div>
                                <img class="d-block w-100" align="center" src='.$img.'>
                                <div class="carousel-caption">
                                    <a href="'.$itens[$i]['LinkInscricao'].'" target="_blank" class="banner" style="color: #000;">
                                        '.$itens[$i]['Tipo'].'<br/>
                                        '.$itens[$i]['DataInicio'].' a '.$itens[$i]['DataFim'].'
                                    </a>
                                </div>
                            </div>
            ';        
        }
        // Fecha o módulo de Eventos Próximos
        $html .= '
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic2" data-slide="prev">
                            <span class="fa fa-angle-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic2" data-slide="next">
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                    <p align="center"><br>
                    <button onclick="showModalEventosProximos()" class="btn btn-sm btn-default" type="button"><i class="ra ra-health ra-1x"></i> Ver todos os Eventos</button>
                </p>
                </div>
            </div>
            
        ';
        // Imprime o módulo de Eventos Próximos
        echo $html;
    }


    










?>
