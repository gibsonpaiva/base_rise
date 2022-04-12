<?php
    /* RISE - Tables and Views */
        // RISE - Tables
        define("TBL_APROVACAOSTATUS", "rise.aprovacao_status");
        define("TBL_FORUMRESPOSTAS", "rise.forum_respostas");
        define("TBL_FORUMTOPICOS", "rise.forum_topicos");
        define("TBL_FORUMTOPICOS_TOPICOS", "rise.forum_topicos_tipos");
        define("TBL_MENU", "rise.menu");
        define("TBL_USUARIOS", "rise.usuarios");
        define("TBL_GRUPOS", "grupos"); // Grupos de segurança. Necessário concatenar o nome da base/schema (território)
        // RISE - Views
        define("VIEW_FORUMRESPOSTAS", "rise.view_forumrespostas");
        define("VIEW_FORUMTOPICOS", "rise.view_forumtopicos");
        define("VIEW_PERMISSOES", "view_permissoes"); // Permissões dos usuários. Necessário concatenar o nome da base/schema (território)
        define("VIEW_ARQUIVOS_EVENTOS", "filebox.view_arquivoseventos");


    /* IGOT - Tables and Views */
        // iGOT - Tables
        define("TBL_ALIANCAS", "igot.aliancas");
        define("TBL_ANDARES", "igot.andares");
        define("TBL_CASTELOS", "igot.castelos");
        define("TBL_CONFORMIDADES", "igot.conformidades");
        define("TBL_CONFORMIDADESREGRAS", "igot.conformidades_regras");
        define("TBL_CONFORMIDADESREQUISITOS", "igot.conformidades_requisitos");
        define("TBL_CONFORMIDADESTIPOS", "igot.conformidades_tipos");
        define("TBL_CONSELHEIROSDOSREINOS", "igot.conselheiros_reinos");
        define("TBL_EVENTOS", "igot.eventos");
        define("TBL_EVENTOSCATEGORIAS", "igot.eventos_categorias");
        define("TBL_EVENTOSSTATUS", "igot.eventos_status");
        define("TBL_EVENTOSTIPOS", "igot.eventos_tipos");
        define("TBL_EXERCITOS", "igot.exercitos");
        define("TBL_GUERREIROS", "igot.guerreiros");
        define("TBL_GUERREIROSNASTORRES", "igot.guerreiros_torres");
        define("TBL_PATENTES", "igot.patentes");
        define("TBL_REINOS", "igot.reinos");
        define("TBL_TORRES", "igot.torres");
        // iGOT - Views
        define("VIEW_CASTELOS", "igot.view_castelos");
        define("VIEW_CONFORMIDADES", "igot.view_conformidades");
        define("VIEW_CONFORMIDADESREGRAS", "igot.view_conformidadesregras");
        define("VIEW_CONFORMIDADESREQUISITOS", "igot.view_conformidadesrequisitos");
        define("VIEW_CONSELHEIROSDOSREINOS", "igot.view_conselheirosdosreinos");
        define("VIEW_EVENTOS", "igot.view_eventos");
        define("VIEW_EVENTOSTIPOS", "igot.view_eventostipos");
        define("VIEW_GUERREIROS", "igot.view_guerreiros");
        define("VIEW_GUERREIROSNASTORRES", "igot.view_guerreironastorres");
        define("VIEW_REINOS", "igot.view_reinos");
        define("VIEW_TORRES", "igot.view_torres");
        // iGOT - Eventos
        define("FERIAS", 25);

    /* IBOT - Tables and Views */
        // iBOT - Tables
        // iBOT - Views
    
    /* FILEBOX - Tables, Views and Areas */
        // FILEBOX - Tables
        define("TBL_AREAS", "filebox.area");
        define("TBL_TIPOSDEARQUIVO", "filebox.arquivos_tipo");
        define("TBL_ARQUIVOSALIANCAS", "filebox.igot_aliancas");
        define("TBL_ARQUIVOSEVENTOS", "filebox.igot_eventos");
        define("TBL_ARQUIVOSEXERCITOS", "filebox.igot_exercitos");
        define("TBL_ARQUIVOSUSUARIOS", "filebox.rise_usuarios");
        // FILEBOX - Views
        define("VIEW_ARQUIVOS", "filebox.view_arquivos");
        // FILEBOX - Areas
        define("IGOT_EVENTOS", 1);
        define("RISE_USUARIOS", 2);
?>