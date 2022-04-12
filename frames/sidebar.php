<?php
    /*** HTML dinâmico do conteúdo da Página ***/
    // Inclue objetos referentes a bases de dados
    include_once "{$_SERVER['DOCUMENT_ROOT']}/config/db.php";
    // Instancia os objetos referente a base de dados Menu
    $db_rise = new RISE();
    // Listagem dos itens do Menu Principal
    $menu_itens = $db_rise->getMenu(0);
    // Montagem do Menu
    $menu_html="";
    for ($i=0; $i<count($menu_itens); $i++){
        // Listagem dos itens do Submenu
        $submenu_itens = $db_rise->getMenu($menu_itens[$i]['idMenu']);        
        if(isset($submenu_itens)){ // Se houver itens de submenu
            $menu_html .= '
            <li class="treeview">
                <a href="'.$menu_itens[$i]['Link'].'">
                    <i class="'.$menu_itens[$i]['Icone'].'"></i><span>'.$menu_itens[$i]['Titulo'].'</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">';
            for ($si=0; $si<count($submenu_itens); $si++){
                $menu_html .= '
                <li><a href="'.$submenu_itens[$si]['Link'].'"><i class="'.$submenu_itens[$si]['Icone'].'"></i> '.$submenu_itens[$si]['Titulo'].'</a></li>';
            }
            $menu_html .= '
                </ul>
            </li>';
        } else { // Se não houver itens de submenu
            $menu_html .= '
            <li>
                <a href="'.$menu_itens[$i]['Link'].'">
                    <i class="'.$menu_itens[$i]['Icone'].'"></i> <span>'.$menu_itens[$i]['Titulo'].'</span>
                </a>
            </li>';
        }
    }
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Menu Lateral -->
        <ul class="sidebar-menu" data-widget="tree">
            <!-- Itens de Menu -->
            <?php echo $menu_html ?>
        </ul>
    </section>
</aside>