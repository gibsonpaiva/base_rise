<?php
    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";

    // Instancia os objetos referente a base de dados
    $db_filebox = new FileBox();

    // Listagem dos Tipos de Arquivo
    $itens = $db_filebox->getTipos(); // Obtenção dos Exércitos para montagem das opções do ComboBox 
    $optTipos = "";
    for ($i=0; $i<count($itens); $i++){
        $optTipos .= '<option value="'.$itens[$i]['id'].'">'.$itens[$i]['Nome'].'</option>'; // Incremento nas opções do ComboBox
    }
?>

<!-- GUIA ARQUIVOS : INICIO -->
<div class="tab-pane" id="Arquivos"> 

    <!-- Cabeçalho da GUIA : INICIO -->
    <img width="100px" align="left" height="auto" alt="Arquivos" src="/igot/img/arquivos-1.png">
    <br><h4><p class="titulo">ARQUIVOS</p></h4>
    <p class="conteudo"> 
        Base de arquivos armazenados na plataforma.
        <?php echo $optTipos; ?>
    </p>
    <hr class='featurette-divider'>
   
</div> 
<!-- GUIA ARQUIVOS : FIM -->

