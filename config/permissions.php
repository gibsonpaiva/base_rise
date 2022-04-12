<?php
    class Permission {
        // Verifica se o usuário está autorizado por ser Administrador do Território
        function admin($territorio){
            $territorio = strToLower($territorio); // Padroniza o Território em caracteres minúsculos
            // Inicia uma Sessão se ainda não tiver iniciado
            if(session_id()==''){ session_start(); }
            // Retorna Verdadeiro se membro do grupo Admin do Território solicitado
            if(isset($_SESSION[$territorio]['groups']['admin'])){
                return $_SESSION[$territorio]['groups']['admin'];
            } else {
                return false;
            }
        }

        // Verifica se o usuário está autorizado por ser Proprietário
        function proprietario($idOwner){
            // Inicia uma Sessão se ainda não tiver iniciado
            if(session_id()==''){ session_start(); }
            // Retorna Verdadeiro se for o Proprietário
            if($_SESSION['user']['id']==$idOwner){
                return true;
            } else {
                return false;
            }
        }

        // IGOT - Verifica se o usuário está autorizado por ser General do Exército
        function general($idExercito=null){            
            // Inicia uma Sessão se ainda não tiver iniciado
            if(session_id()==''){ session_start(); }
            // Retorna Verdadeiro se membro do grupo General do Exército solicitado
            if($_SESSION['igot']['groups']['general'] && ($idExercito==null || $_SESSION['igot']['Guerreiro']['idExercito']==$idExercito)){
                return true;
            } else {
                return false;
            }
        }

        // IGOT - Verifica se o Guerreiro está autorizado por ser Proprietário
        function guerreiro($idGuerreiro, $idOwner=null){
            // Inicia uma Sessão se ainda não tiver iniciado
            if(session_id()==''){ session_start(); }
            // Retorna Verdadeiro se o Guerreiro é o Proprietário
            if(($_SESSION['igot']['Guerreiro']['id']==$idGuerreiro) && ($idOwner==null || $_SESSION['user']['id']==$idOwner)){
                return true;
            } else {
                return false;
            }
        }
    }
?>