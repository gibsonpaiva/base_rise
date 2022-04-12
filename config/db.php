<?php
    // Inclui objeto para conexão com as bases de dados
    require_once "dbconnect.php";
    // Inclui Constante com os Nomes das Tabelas
    require_once "tables.php";

    class RISE {

        // Obtém as Respostas de um Tópico do Fórum
        function getOptCategoriaTopicos(){
            // SQL Query
            $sql = "SELECT idTipoTopico, TipoTopico FROM ".TBL_FORUMTOPICOS_TOPICOS."";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $itens = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $itens[$i]['idTipoTopico'] = $row['idTipoTopico'];
                    $itens[$i]['TipoTopico'] = $row['TipoTopico'];                   
                    $i++;
                }
                // Retorna o resultado da query
                return $itens;
            }
        } 

        // Obtém a os topicos do forum
        function getTopicos($idTopico=0){
            // SQL Query
            $sql = "SELECT id, Assunto, Postagem, idTipoTopico, TipoTopico,  IF (qtdeRespostas IS NULL, 0, qtdeRespostas) AS qtdeRespostas, StatusAprovacao, idRegistradoPor, RegistradoPor, Departamento, RegistradoEm FROM ".VIEW_FORUMTOPICOS." AS Topicos LEFT JOIN (SELECT idTopico, COUNT(idTopico) AS qtdeRespostas FROM ".VIEW_FORUMRESPOSTAS." GROUP BY idTopico) AS Respostas ON Topicos.id = Respostas.idTopico";
            if($idTopico != 0) { $sql .= " WHERE id = {$idTopico}"; }
            $sql .= " ORDER BY id DESC";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $itens = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $itens[$i]['id'] = $row['id'];
                    $itens[$i]['Assunto'] = $row['Assunto'];
                    $itens[$i]['Postagem'] = $row['Postagem'];
                    $itens[$i]['qtdeRespostas'] = $row['qtdeRespostas'];
                    $itens[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                    $itens[$i]['idRegistradoPor'] = $row['idRegistradoPor'];
                    $itens[$i]['RegistradoPor'] = $row['RegistradoPor'];
                    $itens[$i]['Departamento'] = $row['Departamento'];
                    $itens[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $itens[$i]['idTipoTopico'] = $row['idTipoTopico'];
                    $itens[$i]['TipoTopico'] = $row['TipoTopico'];

                    $i++;
                }
                // Retorna o resultado da query
                return $itens;
            }
        }
        
        // Obtém as Respostas de um Tópico do Fórum
        function getRespostas($idTopico){
            // SQL Query
            $sql = "SELECT idTopico, idResposta, Resposta, StatusAprovacao, idRegistradoPor,  RegistradoPor, RegistradoEm FROM ".VIEW_FORUMRESPOSTAS." WHERE idTopico={$idTopico}";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $itens = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $itens[$i]['idTopico'] = $row['idTopico'];
                    $itens[$i]['id'] = $row['idResposta'];
                    $itens[$i]['Resposta'] = $row['Resposta'];
                    $itens[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                    $itens[$i]['idRegistradoPor'] = $row['idRegistradoPor'];
                    $itens[$i]['RegistradoPor'] = $row['RegistradoPor'];
                    $itens[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $i++;
                }
                // Retorna o resultado da query
                return $itens;
            }
        } 

        // Obtém os itens de Menu
        function getMenu($idParent){
            // SQL Query
            if(!is_null($idParent)){
                $sql = 
                "SELECT menu.idMenu AS 'idMenu',
                        menu.Icone AS 'Icone',
                        menu.Titulo AS 'Titulo',
                        menu.Link AS 'Link',
                        menu.MenuPai AS 'idMenuPai',
                        parent.Titulo AS 'MenuPai',
                        menu.Ordem AS 'Ordem'
                FROM ".TBL_MENU." AS menu
                    LEFT JOIN ".TBL_MENU." AS parent ON parent.idMenu = menu.MenuPai
                WHERE menu.MenuPai={$idParent}
                ORDER BY menu.Ordem";            
            } else { // Se não informado o nível do menu será retornado todos os itens da tabela
                $sql = 
                "SELECT menu.idMenu AS 'idMenu',
                        menu.Icone AS 'Icone',
                        menu.Titulo AS 'Titulo',
                        menu.Link AS 'Link',
                        menu.MenuPai AS 'idMenuPai',
                        parent.Titulo AS 'MenuPai',
                        menu.Ordem AS 'Ordem'
                FROM ".TBL_MENU." AS menu
                    LEFT JOIN ".TBL_MENU." AS parent ON parent.idMenu = menu.MenuPai
                ORDER BY menu.Ordem";
            }

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $itens = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $itens[$i]['idMenu'] = $row['idMenu'];
                    $itens[$i]['Icone'] = $row['Icone'];
                    $itens[$i]['Titulo'] = $row['Titulo'];
                    $itens[$i]['Link'] = $row['Link'];
                    $itens[$i]['idMenuPai'] = $row['idMenuPai'];
                    $itens[$i]['MenuPai'] = $row['MenuPai'];
                    $i++;
                }

                // Retorna o resultado da query
                return $itens;
            }
        }

        // Obtém os usuários
        function getUsers(){
            // SQL Query
            $sql = "SELECT idUsuario, NomeUsuario, NomeExibicao, Departamento FROM ".TBL_USUARIOS."";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $users = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $users[$i]['idUsuario'] = $row['idUsuario'];
                    $users[$i]['NomeUsuario'] = $row['NomeUsuario'];
                    $users[$i]['NomeExibicao'] = $row['NomeExibicao'];
                    $users[$i]['Departamento'] = $row['Departamento'];
                    $i++;
                }
                // Retorna o resultado da query
                return $users;
            }
        }

        // Obtém as propriedades do usuário a partir do username
        function getUserByName($username){
            // SQL Query
            $sql = "SELECT idUsuario, NomeUsuario, NomeExibicao, Departamento FROM ".TBL_USUARIOS." WHERE NomeUsuario='{$username}'";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            
            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                // Formata o resultado da query
                $row = $result->fetch_assoc();
                // Retorna o resultado da query
                return $row;
            }
        }

        // Obtém os Grupos que o Usuário é membro
        function getUserGroups($userid, $territorio){
            // Declara a variável para armazenamento dos grupos e identificação se o usuário é membro
            $memberof = array();
            
            // Lista os Grupos disponíveis no Território
            // SQL Query
            $sql = "SELECT NomeGrupo AS Grupo FROM {$territorio}.".TBL_GRUPOS;
            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Formata o resultado da query
            while($row = $result->fetch_assoc()){
                $memberof[strToLower($row['Grupo'])] = false;
            }

            // SQL Query
            $sql = "SELECT idGrupo, Grupo FROM {$territorio}.".VIEW_PERMISSOES." WHERE idUsuario={$userid}";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $memberof[strToLower($row['Grupo'])] = true;
                }
            }

            // Retorna o resultado da query
            return $memberof;
        }
    
    }

    class FileBox {
        // Carrega as Propriedades do(s) Arquivo(s)
        function getArquivo($idArquivo=0, $idArea=0, $idItem=0){
            // SQL Query
            $sql = "SELECT idArquivo AS id, Nome, Tamanho, Descricao, idTipo, Tipo, idArea, Area, idItem FROM ".VIEW_ARQUIVOS." WHERE 1";
            if($idArquivo!=0) { $sql .= " AND idArquivo={$idArquivo}"; }
            if($idArea!=0) { $sql .= " AND idArea={$idArea}"; }
            if($idItem!=0) { $sql .= " AND idItem={$idItem}"; }

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['Nome'] = $row['Nome'];
                    $data[$i]['Tamanho'] = $row['Tamanho'];
                    $data[$i]['Descricao'] = $row['Descricao'];
                    $data[$i]['idTipo'] = $row['idTipo'];
                    $data[$i]['Tipo'] = $row['Tipo'];
                    $data[$i]['idArea'] = $row['idArea'];
                    $data[$i]['Area'] = $row['Area'];
                    $data[$i]['idItem'] = $row['idItem'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Carrega uma Imagem
        function loadImagem($idArea, $idItem){
            switch ($idArea) {
                case 1: $table = TBL_ARQUIVOSEVENTOS; break;
                case 2: $table = TBL_ARQUIVOSUSUARIOS; break;               
                case 3: $table = TBL_ARQUIVOSALIANCAS; break;
                case 4: $table = TBL_ARQUIVOSEXERCITOS; break;                
            }

            // SQL Query
            $sql = "SELECT Formato, Arquivo FROM {$table} WHERE idItem={$idItem}";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                // Formata o resultado da query
                $row = $result->fetch_assoc();
                //$img = '<img width="20%" src="data:image/'.$row['Formato'].';base64,base64_encode('.$row['Arquivo'].')"/>'
                //return $row;
                $img = 'data:'.$row['Formato'].';base64,'.base64_encode($row['Arquivo']);
            }else{
                switch ($idArea) {
                    case 2: $img = '/img/img-perfil.jpg'; break;
                    case 3: $img = '/img/BannerPadrao.png'; break;
                    default: $img = '/img/img-perfil.jpg'; break;
                }   
            }
            return $img;
        }    
        
        // Obtém os Tipos de Arquivos
        function getTipos(){
            // SQL Query
            $sql = "SELECT idTipo, Tipo FROM ".TBL_TIPOSDEARQUIVO." ORDER BY Tipo";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idTipo'];
                    $data[$i]['Nome'] = $row['Tipo'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Carrega o Arquivo
        function loadFile($idArquivo){
            // SQL Query
            $sql = "SELECT Formato, Tamanho, Nome, Arquivo FROM ".VIEW_ARQUIVOS." WHERE idArquivo={$idArquivo}";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                // Formata o resultado da query
                $row = $result->fetch_assoc();
                // Retorna o resultado da query
                return $row;
            }
        }

        // Obtém as Áreas dos Arquivos
        function getAreas(){
            // SQL Query
            $sql = "SELECT idArea, Area FROM ".TBL_AREAS." ORDER BY Area";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idArea'];
                    $data[$i]['Nome'] = $row['Area'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }
    }

    class IGOT {
        // obtem guerreiros adm

        function getPermissaoADM(){

            $sql = "SELECT idUsuario FROM ".VIEW_PERMISSOES."";

             // Instancia o objeto referente a base de dados
             $database = new Database();
             // Executa a Query
             $result = $database->run($sql);
             // Remove o objeto referente a base de dados
             unset($database);
 
             // Certifica de que há resultado
             if(mysqli_num_rows($result)>0) {
                 $data = array();
                 $i=0;
                 // Formata o resultado da query
                 while($row = $result->fetch_assoc()){
                     $data[$i]['idUsuario'] = $row['idUsuario'];
                     
                     $i++;
                 }
                 // Retorna o resultado da query
                 return $data;
             }


        }

        // Obtém os Guerreiros nas Torres
        function getTorresComGuerreiros($idGuerreiro, $idPosicao){
            // SQL Query
            $sql = "SELECT id, idGuerreiro, Guerreiro, idExercitoGuerreiro, ExercitoGuerreiro, idReino, Reino, idCastelo, Castelo, idTorre, Torre, idPosicao, Posicao FROM ".VIEW_GUERREIROSNASTORRES." WHERE idGuerreiro = $idGuerreiro";
            if($idPosicao==1) {
                $sql .= " AND idPosicao = 1";
            }    
            
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];
                    $data[$i]['idExercito'] = $row['idExercitoGuerreiro'];
                    $data[$i]['Exercito'] = $row['ExercitoGuerreiro'];
                    $data[$i]['idReino'] = $row['idReino'];
                    $data[$i]['Reino'] = $row['Reino'];
                    $data[$i]['idCastelo'] = $row['idCastelo'];
                    $data[$i]['Castelo'] = $row['Castelo'];
                    $data[$i]['idTorre'] = $row['idTorre'];
                    $data[$i]['Torre'] = $row['Torre'];
                    $data[$i]['idPosicao'] = $row['idPosicao'];
                    $data[$i]['Posicao'] = $row['Posicao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }


        // Obtém as solicitações dos guerreiros para os administradores
        function getSolicitacaoFeriasParaAdm(){
            // SQL Query            
            $sql = "SELECT id, idCategoria, Categoria, idtipo, Tipo, idGuerreiro, Guerreiro, RegistradoEm, DataInicioEvento, DataInicioEvento_YMD, DataConclusao, DataConclusao_YMD, StatusAprovacao FROM ".VIEW_EVENTOS." WHERE idCategoria = 25 AND idStatusAprovacao = 1 OR idStatusAprovacao = 2";
        
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['idCategoria'] = $row['idCategoria'];
                    $data[$i]['Categoria'] = $row['Categoria'];
                    $data[$i]['idTipo'] = $row['idTipo'];
                    $data[$i]['Tipo'] = $row['Tipo'];                 
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];                   
                    $data[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $data[$i]['DataInicioEvento'] = $row['DataInicioEvento']; 
                    $data[$i]['DataInicioEvento_YMD'] = $row['DataInicioEvento_YMD'];  
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['DataConclusao_YMD'] = $row['DataConclusao_YMD'];
                    $data[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as solicitações do guerreiro
        function getSolicitacaoDoGuerreiro($idGuerreiro=0){
            // SQL Query            
            $sql = "SELECT id, idCategoria, Categoria, idtipo, Tipo, idGuerreiro, Guerreiro, RegistradoEm, DataInicioEvento, DataInicioEvento_YMD, DataConclusao, DataConclusao_YMD, StatusAprovacao FROM ".VIEW_EVENTOS." WHERE idCategoria = 25 AND idGuerreiro =$idGuerreiro";
        
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['idCategoria'] = $row['idCategoria'];
                    $data[$i]['Categoria'] = $row['Categoria'];
                    $data[$i]['idTipo'] = $row['idTipo'];
                    $data[$i]['Tipo'] = $row['Tipo'];                 
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];                   
                    $data[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $data[$i]['DataInicioEvento'] = $row['DataInicioEvento']; 
                    $data[$i]['DataInicioEvento_YMD'] = $row['DataInicioEvento_YMD'];  
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['DataConclusao_YMD'] = $row['DataConclusao_YMD'];
                    $data[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }
        
        // Obtém os ultimos periodos de ferias do guerreiro logado
        function getFeriasGuerreiroLogado($idGuerreiro=0){
            // SQL Query            
            $sql = "SELECT  idCategoria, Categoria, idtipo, Tipo, idGuerreiro, Guerreiro, RegistradoEm, DataInicioEvento, DataInicioEvento_YMD, DataConclusao, DataConclusao_YMD, StatusAprovacao FROM ".VIEW_EVENTOS." WHERE idCategoria = 25 AND idGuerreiro =$idGuerreiro AND DataConclusao_YMD < now()";
        
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['idCategoria'] = $row['idCategoria'];
                    $data[$i]['Categoria'] = $row['Categoria'];
                    $data[$i]['idTipo'] = $row['idTipo'];
                    $data[$i]['Tipo'] = $row['Tipo'];                 
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];                   
                    $data[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $data[$i]['DataInicioEvento'] = $row['DataInicioEvento']; 
                    $data[$i]['DataInicioEvento_YMD'] = $row['DataInicioEvento_YMD'];  
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['DataConclusao_YMD'] = $row['DataConclusao_YMD'];
                    $data[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém o Total de Guerreiros em férias
        function getTotalGuerreirosEmFerias(){
            // SQL Query            
            $sql = "SELECT idCategoria, idGuerreiro, Guerreiro, RegistradoEm, DataInicioEvento, DataInicioEvento_YMD, DataConclusao, DataConclusao_YMD, StatusAprovacao FROM ".VIEW_EVENTOS." WHERE idCategoria = 25 AND DataInicioEvento_YMD < now() AND DataConclusao_YMD > now() AND idStatusAprovacao = 3";
            
              // Instancia o objeto referente a base de dados
              $database = new Database();
              // Executa a Query
              $result = $database->run_multi($sql);
              // Remove o objeto referente a base de dados
              unset($database);
  
              // Certifica de que há resultado
              if(mysqli_num_rows($result)>0) {
                  $data = array();
                  $i=0;
                  // Formata o resultado da query
                  while($row = $result->fetch_assoc()){
                      $data[$i]['idCategoria'] = $row['idCategoria'];               
                      $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                      $data[$i]['Guerreiro'] = $row['Guerreiro'];                   
                      $data[$i]['RegistradoEm'] = $row['RegistradoEm'];
                      $data[$i]['DataInicioEvento'] = $row['DataInicioEvento']; 
                      $data[$i]['DataInicioEvento_YMD'] = $row['DataInicioEvento_YMD'];  
                      $data[$i]['DataConclusao'] = $row['DataConclusao'];
                      $data[$i]['DataConclusao_YMD'] = $row['DataConclusao_YMD'];
                      $data[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                      $i++;
                  }
                  // Retorna o resultado da query
                  return $data;
              }
        }

        // Obtém o Total de Guerreiros em férias Agendadas
        function getTotalGuerreirosEmFeriasAgendadas(){
            // SQL Query            
            $sql = "SELECT idCategoria, idGuerreiro, Guerreiro, RegistradoEm, DataInicioEvento, DataInicioEvento_YMD, DataConclusao, DataConclusao_YMD, StatusAprovacao FROM ".VIEW_EVENTOS."  WHERE idCategoria = 25 AND DataConclusao_YMD > now() AND DataInicioEvento_YMD > now() AND idStatusAprovacao = 3 ORDER by DataInicioEvento_YMD ASC";

            
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['idCategoria'] = $row['idCategoria'];               
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];                   
                    $data[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $data[$i]['DataInicioEvento'] = $row['DataInicioEvento']; 
                    $data[$i]['DataInicioEvento_YMD'] = $row['DataInicioEvento_YMD'];  
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['DataConclusao_YMD'] = $row['DataConclusao_YMD'];
                    $data[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém o Total de Guerreiros em férias Concluidas
        function getTotalGuerreirosEmFeriasConcluidas(){
            // SQL Query            
            $sql = "SELECT idCategoria, idGuerreiro, Guerreiro, RegistradoEm, DataInicioEvento, DataInicioEvento_YMD, DataConclusao, DataConclusao_YMD, StatusAprovacao FROM ".VIEW_EVENTOS." WHERE idCategoria = 25 AND DataConclusao_YMD < now() AND idStatusAprovacao = 3 ORDER by DataConclusao_YMD DESC ";
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['idCategoria'] = $row['idCategoria'];               
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];                   
                    $data[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $data[$i]['DataInicioEvento'] = $row['DataInicioEvento']; 
                    $data[$i]['DataInicioEvento_YMD'] = $row['DataInicioEvento_YMD'];  
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['DataConclusao_YMD'] = $row['DataConclusao_YMD'];
                    $data[$i]['StatusAprovacao'] = $row['StatusAprovacao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém o Total de Posição da torre
        function getPosicaoDaTorre($idTorre=0){
            // SQL Query            
            $sql = "SELECT COUNT(idPosicao) AS QtdePosicao, Posicao, Reino, Castelo, Torre, Medalhas FROM ".VIEW_GUERREIROSNASTORRES." WHERE idTorre=$idTorre GROUP By Posicao ORDER BY Medalhas DESC";
            
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){               
                    $data[$i]['QtdePosicao'] = $row['QtdePosicao'];
                    $data[$i]['Posicao'] = $row['Posicao'];
                    $data[$i]['Reino'] = $row['Reino'];
                    $data[$i]['Castelo'] = $row['Castelo'];
                    $data[$i]['Torre'] = $row['Torre'];               
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém o Total de Torre no reino
        function getTotalTorreDoReino($idReino=0){
            // SQL Query            
            $sql = "SELECT COUNT(idTorre) AS QtdeTorre FROM ".VIEW_TORRES." WHERE idReino=$idReino";
          
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Formata o resultado da query
            $row = $result->fetch_assoc();
            // Retorna a Quantidade de Guerreiros
            return $row['QtdeTorre'];
        }

        // Obtém o Total de Guerreiros
        function getTotalGuerreiros($idReino=0){
            // SQL Query            
            if($idReino==0){ // Prepara Query para Retornar o Total de Guerreiros
                $sql = "SELECT COUNT(idGuerreiro) AS QtdeGuerreiros FROM ".TBL_GUERREIROS." WHERE Ativo=1";
            } else { // Prepara Query para Retornar o Total de Guerreiros de um Reino específico
                $sql = "SELECT COUNT(idGuerreiro) AS QtdeGuerreiros FROM (SELECT DISTINCT idGuerreiro FROM ".VIEW_GUERREIROSNASTORRES." WHERE idReino=$idReino) AS GuerreirosNoReino";
            }

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Formata o resultado da query
            $row = $result->fetch_assoc();
            // Retorna a Quantidade de Guerreiros
            return $row['QtdeGuerreiros'];
        }

         // ObtÃ©m os Guerreiros
        function getGuerreiros($idUsuario=0){
            // SQL Query
            $sql = "SELECT id, Guerreiro, idUsuario, Ativo, Exercito, idExercito FROM ".VIEW_GUERREIROS."";
            if($idUsuario!=0) {
                $sql .= " WHERE idUsuario = {$idUsuario}";
            } else {
                $sql .= " ORDER BY Guerreiro";
            }

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que hÃ¡ resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['Nome'] = $row['Guerreiro'];
                    $data[$i]['idExercito'] = $row['idExercito'];
                    $data[$i]['Exercito'] = $row['Exercito'];
                    $data[$i]['Ativo'] = $row['Ativo'];
                    if($row['Ativo']){
                        $data[$i]['Status'] = "Ativo";
                    } else {
                        $data[$i]['Status'] = "Inativo";
                    }
                    $data[$i]['idUsuario'] = $row['idUsuario'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém O Quadro de Medalhas
        function getQuadroMedalhas($idExercito){
            //SQL Query
            $sql  = "SET @m=0;";
            $sql .= "SET @e=0;";
            $sql .= "SET @r=0;";
            $sql .= 
            "   SELECT
                    CASE
                        WHEN @m = TotalMedalhas THEN @r
                        WHEN @e = idExercito THEN (@r := @r + 1)
                        ELSE @r := 1
                    END AS Rank,
                    (@e := idExercito) AS idExercito,
                    Exercito,
                    idGuerreiro,
                    Guerreiro,
                    (@m := TotalMedalhas) AS TotalMedalhas
                FROM (  SELECT
                            idExercitoGuerreiro AS idExercito,
                            ExercitoGuerreiro AS Exercito,
                            idGuerreiro,
                            Guerreiro,
                            SUM(Medalhas) AS TotalMedalhas
                        FROM ".VIEW_GUERREIROSNASTORRES."
                        GROUP BY Guerreiro
                        ORDER BY FIELD(idExercito, {$idExercito}) DESC, Exercito, TotalMedalhas DESC, Guerreiro
                ) AS Quadro
            ";

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['Rank'] = $row['Rank'];
                    $data[$i]['idExercito'] = $row['Exercito'];
                    $data[$i]['Exercito'] = $row['Exercito'];
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];
                    $data[$i]['TotalMedalhas'] = $row['TotalMedalhas'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém o Total de Medalhas de um Guerreiro
        function getMedalhas($idExercito, $idGuerreiro){
            //SQL Query
            $sql  = "SET @m=0;";
            $sql .= "SET @r=0;";
            $sql .= 
            "   SELECT
                    Rank,
                    Exercito,
                    Guerreiro,
                    TotalMedalhas
                FROM (
                    SELECT
                        CASE
                            WHEN @m = TotalMedalhas THEN @r
                            ELSE (@r := @r + 1)
                        END AS Rank,
                        idExercito,
                        Exercito,
                        idGuerreiro,
                        Guerreiro,
                        (@m := TotalMedalhas) AS TotalMedalhas
                    FROM (  SELECT
                                idExercitoGuerreiro AS idExercito,
                                ExercitoGuerreiro AS Exercito,
                                idGuerreiro,
                                Guerreiro,
                                SUM(Medalhas) AS TotalMedalhas
                            FROM ".VIEW_GUERREIROSNASTORRES."
                            GROUP BY Guerreiro
                            ORDER BY TotalMedalhas DESC
                    ) AS Quadro
                    WHERE idExercito = {$idExercito}
                ) AS Posicao
                WHERE idGuerreiro = {$idGuerreiro}
            ";

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                // Formata o resultado da query
                $row = $result->fetch_assoc();
                $data['Rank'] = $row['Rank']."º";
                $data['Exercito'] = $row['Exercito'];
                $data['Guerreiro'] = $row['Guerreiro'];
                if($row['TotalMedalhas'] > 0){ $data['TotalMedalhas'] = $row['TotalMedalhas']; }
                else { $data['TotalMedalhas'] = 0; }
            } else {
                $data['Rank'] = "-";
                $data['Exercito'] = null;
                $data['Guerreiro'] = null;
                $data['TotalMedalhas'] = "-";
            }
            // Retorna o resultado da query
            return $data;
        }

        // Obtém os Guerreiros nas Torres
        function getGuerreirosNasTorres($filtro=1, $GroupBy=null){
            // SQL Query
            $sql = "SELECT idGuerreiro, Guerreiro, idExercitoGuerreiro, ExercitoGuerreiro, idReino, Reino, idCastelo, Castelo, idTorre, Torre, idPosicao, Posicao FROM ".VIEW_GUERREIROSNASTORRES." WHERE {$filtro}";
            if($GroupBy!=null){ $sql .= " GROUP BY {$GroupBy}"; }
            
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];
                    $data[$i]['idExercito'] = $row['idExercitoGuerreiro'];
                    $data[$i]['Exercito'] = $row['ExercitoGuerreiro'];
                    $data[$i]['idReino'] = $row['idReino'];
                    $data[$i]['Reino'] = $row['Reino'];
                    $data[$i]['idCastelo'] = $row['idCastelo'];
                    $data[$i]['Castelo'] = $row['Castelo'];
                    $data[$i]['idTorre'] = $row['idTorre'];
                    $data[$i]['Torre'] = $row['Torre'];
                    $data[$i]['idPosicao'] = $row['idPosicao'];
                    $data[$i]['Posicao'] = $row['Posicao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Conselheiros do Rei
        function getConselheirosReinos(){
            // SQL Query
            $sql = "SELECT id, idExercito, Exercito, idConselheiro, Conselheiro, idReino, Reino FROM ".VIEW_CONSELHEIROSDOSREINOS;

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['idExercito'] = $row['idExercito'];
                    $data[$i]['Exercito'] = $row['Exercito'];
                    $data[$i]['idConselheiro'] = $row['idConselheiro'];
                    $data[$i]['Conselheiro'] = $row['Conselheiro'];
                    $data[$i]['idReino'] = $row['idReino'];
                    $data[$i]['Reino'] = $row['Reino'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Exércitos
        function getExercitos($Exercito=""){
            // SQL Query
            $sql = "SELECT idExercito, NomeExercito FROM ".TBL_EXERCITOS;
            if($Exercito == ""){ 
                $sql .= " ORDER BY NomeExercito";
            } else {
                $sql .= " ORDER BY FIELD(NomeExercito,'{$Exercito}') DESC";
            }

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idExercito'];
                    $data[$i]['Nome'] = $row['NomeExercito'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Reinos
        function getReinos($idExercito=0){
            // SQL Query
            $sql = 
            "   SELECT
                    Reino.idReino AS id,
                    Reino.NomeReino AS Nome,
                    Reino.idExercito AS idExercito,
                    Exercito.NomeExercito AS Exercito
                FROM ".TBL_REINOS." AS Reino
                    INNER JOIN ".TBL_EXERCITOS." AS Exercito ON Exercito.idExercito = Reino.idExercito
                WHERE 1
            ";
            
            if($idExercito!=0){ $sql .= " AND Reino.idExercito={$idExercito}";}
            $sql .= " ORDER BY Reino.NomeReino";

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['Nome'] = $row['Nome'];
                    $data[$i]['idExercito'] = $row['idExercito'];
                    $data[$i]['Exercito'] = $row['Exercito'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Castelos
        function getCastelos($idReino=0){
            // SQL Query
            $sql = 
            "   SELECT
                    Castelo.idCastelo AS 'idCastelo',
                    Castelo.NomeCastelo AS 'NomeCastelo',
                    Castelo.idReino AS 'idReino',
                    Reino.NomeReino AS 'NomeReino'
                FROM ".TBL_CASTELOS." AS Castelo
                    INNER JOIN ".TBL_REINOS." AS Reino ON Castelo.idReino = Reino.idReino
                WHERE 1
            ";
            if($idReino!=0){ $sql .= " AND Castelo.idReino={$idReino}"; }
            $sql .= " ORDER BY Reino.NomeReino, Castelo.NomeCastelo";

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idCastelo'];
                    $data[$i]['Nome'] = $row['NomeCastelo'];
                    $data[$i]['idReino'] = $row['idReino'];
                    $data[$i]['Reino'] = $row['NomeReino'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as Alianças
        function getAliancas(){
            // SQL Query
            $sql = "SELECT idAlianca, NomeAlianca FROM ".TBL_ALIANCAS." ORDER BY NomeAlianca";

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idAlianca'];
                    $data[$i]['Nome'] = $row['NomeAlianca'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as Torres
        function getTorres($idCastelo=0){
            // SQL Query
            $sql = "SELECT idTorre, Torre, Alianca FROM ".VIEW_TORRES." WHERE 1";
            if($idCastelo!=0){ $sql .= " AND idCastelo={$idCastelo}"; }
            $sql .= " ORDER BY Torre";

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idTorre'];
                    $data[$i]['Nome'] = $row['Torre'];
                    $data[$i]['Alianca'] = $row['Alianca'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as Patentes
        function getPatentes($idPeca){
            // SQL Query
            $sql = "SELECT idPatente, Patente, Descricao, idPeca FROM ".TBL_PATENTES."";
            if($idPeca=4){ $sql .= " WHERE idPeca={$idPeca}"; }
            $sql .= " ORDER BY Patente";
        

            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);
            // Remove o objeto referente a base de dados
            unset($database);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idPatente'];
                    $data[$i]['Nome'] = $row['Patente'];
                    $data[$i]['Descricao'] = $row['Descricao'];
                    $data[$i]['idPeca'] = $row['idPeca'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Andares
        function getAndares($qtde=false){
            if($qtde){ // Retorna a quantidade de Andares
                // SQL Query
                $sql = "SELECT COUNT(Andar) FROM ".TBL_ANDARES." WHERE Andar <> 0 ";

                // Instancia o objeto referente a base de dados
                $database = new Database();
                // Executa a Query
                $result = $database->run_multi($sql);
                // Remove o objeto referente a base de dados
                unset($database);

                // Certifica de que há resultado
                if(mysqli_num_rows($result)>0) {
                    $data = array();
                    // Formata o resultado da query
                    $row = $result->fetch_assoc();
                    $data['Andares'] = $row['COUNT(Andar)'];
                    // Retorna o resultado da query
                    return $data;
                }
            } else { // Retorna os Andares
                // SQL Query
                $sql = "SELECT idAndar, Andar, TipoGuerreiro, MedalhasConceder, MedalhasAcumuladas FROM ".TBL_ANDARES." ORDER BY Andar ";

                // Instancia o objeto referente a base de dados
                $database = new Database();
                // Executa a Query
                $result = $database->run($sql);
                // Remove o objeto referente a base de dados
                unset($database);

                // Certifica de que há resultado
                if(mysqli_num_rows($result)>0) {
                    $data = array();
                    $i=0;
                    // Formata o resultado da query
                    while($row = $result->fetch_assoc()){
                        $data[$i]['id'] = $row['idAndar'];
                        $data[$i]['Nome'] = $row['TipoGuerreiro'];
                        $data[$i]['Andar'] = $row['Andar'];
                        $data[$i]['MedalhasConceder'] = $row['MedalhasConceder'];
                        $data[$i]['MedalhasAcumuladas'] = $row['MedalhasAcumuladas'];
                        $i++;
                    }
                    // Retorna o resultado da query
                    return $data;
                }
            }
        }

        // Obtém os usuários do RISE
        function getUsers(){
            // SQL Query
            $sql = "SELECT idUsuario, NomeExibicao FROM ".TBL_USUARIOS." ORDER BY NomeExibicao";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idUsuario'];
                    $data[$i]['Nome'] = $row['NomeExibicao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém a lista de proximos eventos
        function getEventosProximos(){
            // SQL Query
            $sql = "SELECT id, idAlianca, idCategoria, Categoria, Alianca, Tipo, DataInicio, DataFim, LinkInscricao FROM ".VIEW_EVENTOSTIPOS." WHERE DataInicio_YMD >= DATE(NOW())  ORDER BY DataInicio_YMD";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['idAlianca'] = $row['idAlianca'];
                    $data[$i]['Alianca'] = $row['Alianca'];
                    $data[$i]['idCategoria'] = $row['idCategoria'];
                    $data[$i]['Categoria'] = $row['Categoria'];
                    $data[$i]['Tipo'] = $row['Tipo'];
                    $data[$i]['DataInicio'] = $row['DataInicio'];
                    $data[$i]['DataFim'] = $row['DataFim'];
                    $data[$i]['LinkInscricao'] = $row['LinkInscricao'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        function getContagemEventos(){
            // SQL Query
            $sql = "SELECT COUNT(idTipo) AS qtdeEventos FROM ".TBL_EVENTOSTIPOS." WHERE DataInicio >= date(now())";
            // Instancia os objetos referente a base de dados
           
             // Instancia o objeto referente a base de dados
             $database = new Database();
             // Executa a Query
             $result = $database->run_multi($sql);
             // Remove o objeto referente a base de dados
             unset($database);
 
             // Certifica de que há resultado
             if(mysqli_num_rows($result)>0) {
                 $data = array();
                 $i=0;
                 // Formata o resultado da query
                 while($row = $result->fetch_assoc()){
                     
                    $data[$i]['qtdeEventos'] = $row['qtdeEventos'];

                     $i++;
                 }
                 // Retorna o resultado da query
                 return $data;
             }
        }

        // Obtém os Eventos Registrados
        function getEventos($idGuerreiro=0, $idEvento=0, $EventosFuturos=false){
            // SQL Query
            $sql = 
            "   SELECT
                    id,
                    idTipo, Tipo,
                    idCategoria,
                    Categoria,
                    idExercito, Exercito,
                    idGuerreiro, Guerreiro,
                    NomeAlianca,
                    idTorre, Torre,
                    idProprietario, Proprietario,
                    DataInicioEvento, DataInicioEvento_YMD,
                    DataConclusao, DataConclusao_YMD,
                    DataExpiracaoEvento, DataExpiracaoEvento_YMD,
                    idStatus, Status,
                    RegistradoEm, RegistradoEm_YMD
                FROM ".VIEW_EVENTOS."
                WHERE 1
            ";
            if($idGuerreiro!=0) { $sql .= " AND idGuerreiro={$idGuerreiro} "; } // Restringe a Eventos de um determinado Guerreiro
            if($idEvento!=0) { $sql .= " AND id={$idEvento}"; } // Restring a um Evento específico
            if($EventosFuturos) {
                $sql .= " AND DataInicioEvento_YMD >= DATE(NOW()) ORDER BY DataInicioEvento_YMD"; // Restringe a Eventos com Data Futura e ordena por Data de Início
            } else {
                $sql .= " ORDER BY RegistradoEm_YMD DESC"; // Ordena os Eventos por Data de Registro
            }

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['idTipo'] = $row['idTipo'];
                    $data[$i]['Tipo'] = $row['Tipo'];
                    $data[$i]['idCategoria'] = $row['idCategoria'];
                    $data[$i]['Categoria'] = $row['Categoria'];
                    $data[$i]['idExercito'] = $row['idExercito'];
                    $data[$i]['Exercito'] = $row['Exercito'];
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];
                    $data[$i]['NomeAlianca'] = $row['NomeAlianca'];
                    $data[$i]['idTorre'] = $row['idTorre'];
                    $data[$i]['Torre'] = $row['Torre'];
                    $data[$i]['idProprietario'] = $row['idProprietario'];
                    $data[$i]['Proprietario'] = $row['Proprietario'];
                    $data[$i]['RegistradoEm'] = $row['RegistradoEm'];
                    $data[$i]['RegistradoEm_YMD'] = $row['RegistradoEm_YMD'];
                    $data[$i]['DataInicio'] = $row['DataInicioEvento'];
                    $data[$i]['DataInicio_YMD'] = $row['DataInicioEvento_YMD'];
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['DataConclusao_YMD'] = $row['DataConclusao_YMD'];
                    $data[$i]['DataExpiracaoEvento'] = $row['DataExpiracaoEvento'];
                    $data[$i]['DataExpiracaoEvento_YMD'] = $row['DataExpiracaoEvento_YMD'];
                    $data[$i]['idStatus'] = $row['idStatus'];
                    $data[$i]['Status'] = $row['Status'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as opções de Tipos de Eventos
        function getEventosTipos($idCategoria=0){
            // SQL Query
            $sql = "SELECT id, Tipo FROM ".VIEW_EVENTOSTIPOS;
            if($idCategoria!=0){$sql .= " WHERE idCategoria={$idCategoria}";}
            $sql .= " ORDER BY Tipo";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['Nome'] = $row['Tipo'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as opções de Categorias de Eventos
        function getEventosCategorias(){
            // SQL Query
            $sql = "SELECT idCategoria, Categoria FROM ".TBL_EVENTOSCATEGORIAS." ORDER BY Categoria";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idCategoria'];
                    $data[$i]['Nome'] = $row['Categoria'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as opções de Status de Eventos
        function getEventosStatus(){
            // SQL Query
            $sql = "SELECT idStatus, Status FROM ".TBL_EVENTOSSTATUS." ORDER BY Status";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idStatus'];
                    $data[$i]['Nome'] = $row['Status'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Eventos para a Linha do Tempo
        function getTimeLine($limit=20, $filtro=null){
            // SQL Query
            $sql = "SELECT id, Icone, DataConclusao, Categoria, idUsuario, Guerreiro, Mensagem, Tipo, Moedas, NomeAlianca, Torre, Exercito, Custo FROM ".VIEW_EVENTOS." WHERE idStatus=4";
            if($filtro!=null){$sql .= " AND ({$filtro})";}
            $sql .= " ORDER BY DataConclusao_YMD DESC LIMIT {$limit}";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['Icone'] = $row['Icone'];
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['Categoria'] = $row['Categoria'];
                    $data[$i]['idUsuario'] = $row['idUsuario'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];
                    $data[$i]['Mensagem'] = $row['Mensagem'];
                    $data[$i]['Tipo'] = $row['Tipo'];
                    $data[$i]['Moedas'] = $row['Moedas'];
                    $data[$i]['NomeAlianca'] = $row['NomeAlianca'];
                    $data[$i]['Torre'] = $row['Torre'];
                    $data[$i]['Exercito'] = $row['Exercito'];
                    $data[$i]['Custo'] = $row['Custo'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Tipos de Conformidades
        function getConformidadesTipos(){
            // SQL Query
            $sql = "SELECT idTipoConformidade, TipoConformidade FROM ".TBL_CONFORMIDADESTIPOS." ORDER BY TipoConformidade";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idTipoConformidade'];
                    $data[$i]['Nome'] = $row['TipoConformidade'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém as Regras das Conformidades
        function getConformidadesRegras($idConformidade){
            // SQL Query
            $sql = "SELECT idRegra, Regra, Alianca, TipoConformidade, Conformidade, QtdeRequerida FROM ".VIEW_CONFORMIDADESREGRAS." WHERE idConformidade={$idConformidade} ORDER BY Alianca, TipoConformidade, Conformidade, Regra";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    // Contabiliza o grupo de eventos concluídos do requisito
                    $idTipoEvento = ""; // Prepara variável para conferência dos Requisitos Atendidos
                    // Identifica os Requisitos da Regra
                    $requisitos = $this->getConformidadesRequisitos($row['idRegra']); // Obtém a lista de Requisitos da Regra
                    foreach($requisitos as $requisito){
                        $idTipoEvento .= "{$requisito['idTipoEvento']}, "; // Concatena o ID do Tipo de Evento para filtro
                    }
                    $idTipoEvento = substr($idTipoEvento, 0, -2); // Ajusta a variável para filtro por Tipos de Eventos
                    // Prepara o resultado para retorno
                    $data[$i]['id'] = $row['idRegra'];
                    $data[$i]['Nome'] = $row['Regra'];
                    $data[$i]['Alianca'] = $row['Alianca'];
                    $data[$i]['Tipo'] = $row['TipoConformidade'];
                    $data[$i]['Conformidade'] = $row['Conformidade'];
                    $data[$i]['Requisitos'] = $requisitos;
                    $data[$i]['QtdeRequerida'] = $row['QtdeRequerida'];
                    $data[$i]['QtdeRequisitos'] = strval(count($requisitos));
                    $data[$i]['QtdeAtendidos'] = $this->getConformidadesRequisitosAtendidos(count($requisitos), $idTipoEvento);
                    //$data[$i]['EventosExpirando'] = strval(count($this->getEventosExpirando(0, $idTipoEvento)));
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Requisitos das Regras de Conformidades
        function getConformidadesRequisitos($idRegra, $EventoPrincipal=false){
            // SQL Query
            $sql = "SELECT idRegra, Regra, Ordem, idTipoEvento, TipoEvento, QtdeRequerida FROM ".VIEW_CONFORMIDADESREQUISITOS." WHERE idRegra={$idRegra}";
            if($EventoPrincipal){$sql .= " AND EventoPrincipal=1";}
            $sql .= " ORDER BY Ordem";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idRegra'];
                    $data[$i]['Regra'] = $row['Regra'];
                    $data[$i]['Ordem'] = $row['Ordem'];
                    $data[$i]['idTipoEvento'] = $row['idTipoEvento'];
                    $data[$i]['TipoEvento'] = $row['TipoEvento'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém a Quantidade de Guerreiros que atendem aos Requisitos
        function getConformidadesRequisitosAtendidos($QtdeRequisitos, $filtro){
            // SQL Query
            $sql = 
            "   SELECT
                    COUNT(idGuerreiro) AS Qtde
                FROM (
                    SELECT
                        idGuerreiro,
                        Guerreiro,
                        COUNT(idTipo) AS QtdeConcluida
                    FROM
                        ".VIEW_EVENTOS."
                    WHERE
                        idStatus=4
                        AND idTipo IN ({$filtro})
                    GROUP BY idGuerreiro
                    ORDER BY Guerreiro
                ) AS QtdeByGuerreiro
                WHERE QtdeConcluida={$QtdeRequisitos}
            ";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Formata o resultado da query
            $row = $result->fetch_assoc();
            // Retorna o resultado da query
            return $row['Qtde'];
        }

        // Obtém os Guerreiros Envolvidos com o Principal Requisito da Conformidade
        function getConformidadesGuerreiros($idRegra){
            $idTipoEvento = ""; // Prepara variável para filtrar pelos Tipos de Eventos Requeridos
            // Identifica os Requisitos da Regra
            $requisitos = $this->getConformidadesRequisitos($idRegra); // Obtém a lista de Requisitos da Regra
            foreach($requisitos as $requisito){
                $idTipoEvento .= "{$requisito['idTipoEvento']}, "; // Concatena o ID do Tipo de Evento para filtro
            }
            $idTipoEvento = substr($idTipoEvento, 0, -2); // Ajusta a variável para filtro por Tipos de Eventos Requeridos

            // SQL Query
            $sql = 
            "   SELECT
                    idUsuario,
                    idGuerreiro,
                    Guerreiro,
                    Exercito
                FROM ".VIEW_EVENTOS."
                    INNER JOIN ".VIEW_CONFORMIDADESREQUISITOS." AS Requisitos ON idTipoEvento = idTipo
                WHERE
                    idRegra={$idRegra}
                    AND EventoPrincipal=1
                    AND idStatus IN (1, 2, 4, 8, 9, 10)
                GROUP BY idGuerreiro
                ORDER BY FIELD(idStatus, 10, 9, 8, 2, 1, 4)  DESC, Guerreiro
            ";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idGuerreiro'];
                    $data[$i]['Nome'] = $row['Guerreiro'];
                    $data[$i]['Exercito'] = $row['Exercito'];
                    $data[$i]['idUsuario'] = $row['idUsuario'];
                    $data[$i]['QtdeConcluidos'] = $this->getConformidadesEventosConcluidos($row['idGuerreiro'], $idTipoEvento);
                    $data[$i]['EventosExpirando'] = $this->getEventosExpirando($row['idGuerreiro'], $idTipoEvento);
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém a Quantidade de Eventos, Requisitos Concluídos que o Guerreiro atende
        function getConformidadesEventosConcluidos($idGuerreiro=0, $idTipoEvento=0){
            // SQL Query
            $sql = "SELECT COUNT(idGuerreiro) AS Qtde FROM ".VIEW_EVENTOS." WHERE idStatus=4 AND idTipo IN ({$idTipoEvento}) AND idGuerreiro={$idGuerreiro}";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Formata o resultado da query
            $row = $result->fetch_assoc();
            // Retorna o resultado da query
            return $row['Qtde'];
        }

        // Obtém os Eventos Guerreiro envolvido em uma Regra da Conformidade
        function getConformidadesEventos($idRegra, $idGuerreiro){
            // SQL Query
            $sql = 
            "   SELECT
                    id,
                    Tipo,
                    DataInicioEvento,
                    DataConclusao,
                    DataExpiracaoEvento,
                    CASE
                        WHEN DataExpiracaoEvento_YMD < (NOW() + INTERVAL 90 DAY) THEN true
                        ELSE false
                    END AS Expirando,
                    idStatus,
                    Status
                FROM ".VIEW_EVENTOS." AS Eventos
                    INNER JOIN ".VIEW_CONFORMIDADESREQUISITOS." AS Requisitos ON idTipoEvento = idTipo
                WHERE
                    idRegra={$idRegra}
                    AND idGuerreiro={$idGuerreiro}
                    AND idStatus IN (1, 2, 4, 8, 9, 10)
                ORDER BY Ordem";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['id'];
                    $data[$i]['Nome'] = $row['Tipo'];
                    $data[$i]['DataInicio'] = $row['DataInicioEvento'];
                    $data[$i]['DataConclusao'] = $row['DataConclusao'];
                    $data[$i]['DataExpiracao'] = $row['DataExpiracaoEvento'];
                    $data[$i]['Expirando'] = $row['Expirando'];
                    $data[$i]['idStatus'] = $row['idStatus'];
                    $data[$i]['Status'] = $row['Status'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Eventos próximos a expirar
        function getEventosExpirando($idGuerreiro=0, $idTipoEvento=0){
            // SQL Query
            $sql = "SELECT id AS idEvento, idAlianca, NomeAlianca AS Alianca, idTipo, Tipo, DataExpiracaoEvento, idGuerreiro, Guerreiro FROM ".VIEW_EVENTOS." WHERE idStatus=4 AND DataExpiracaoEvento_YMD < (NOW() + INTERVAL 90 DAY)";
            if($idTipoEvento != 0){ $sql .= " AND idTipo IN ({$idTipoEvento})"; }
            if($idGuerreiro != 0) { $sql .= " AND idGuerreiro={$idGuerreiro}"; }
            $sql .= " ORDER BY DataExpiracaoEvento_YMD DESC";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['idEvento'] = $row['idEvento'];
                    $data[$i]['idAlianca'] = $row['idAlianca'];
                    $data[$i]['Alianca'] = $row['Alianca'];
                    $data[$i]['idTipo'] = $row['idTipo'];
                    $data[$i]['DataExpiracao'] = $row['DataExpiracaoEvento'];
                    $data[$i]['idGuerreiro'] = $row['idGuerreiro'];
                    $data[$i]['Guerreiro'] = $row['Guerreiro'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }

        // Obtém os Status de Aprovação
        function getAprovacaoStatus(){
            // SQL Query
            $sql = "SELECT idStatus, Status FROM ".TBL_APROVACAOSTATUS." ORDER BY Status";

            // Instancia os objetos referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run($sql);

            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                $i=0;
                // Formata o resultado da query
                while($row = $result->fetch_assoc()){
                    $data[$i]['id'] = $row['idStatus'];
                    $data[$i]['Nome'] = $row['Status'];
                    $i++;
                }
                // Retorna o resultado da query
                return $data;
            }
        }
 
        // Obtém o Totais do Card de um Guerreiro
        function getCard($idGuerreiro){
            //SQL Query
            $sql = 
            "   SELECT
                    (SELECT COUNT(Categoria) FROM ".VIEW_EVENTOS." WHERE idCategoria=6 AND idGuerreiro={$idGuerreiro}) AS 'Certificacoes',
                    (SELECT COUNT(Categoria) FROM ".VIEW_EVENTOS." WHERE idCategoria=1 AND idGuerreiro={$idGuerreiro}) AS 'Treinamento',
                    (SELECT COUNT(Posicao) FROM ".VIEW_GUERREIROSNASTORRES." WHERE idPosicao=4 AND idGuerreiro={$idGuerreiro}) AS 'Comandante',
                    (SELECT SUM(Moedas) FROM ".VIEW_EVENTOS." WHERE idGuerreiro={$idGuerreiro}) AS 'Moedas'
                FROM ".VIEW_EVENTOS." LIMIT 1
            ";
      
            // Instancia o objeto referente a base de dados
            $database = new Database();
            // Executa a Query
            $result = $database->run_multi($sql);
            // Remove o objeto referente a base de dados
            unset($database);
    
            // Certifica de que há resultado
            if(mysqli_num_rows($result)>0) {
                $data = array();
                // Formata o resultado da query
                $row = $result->fetch_assoc();
                $data['Certificacoes'] = $row['Certificacoes'];
                $data['Treinamento'] = $row['Treinamento'];
                $data['Comandante'] = $row['Comandante'];
                if($row['Moedas'] <> null){$data['Moedas'] = $row['Moedas'];}else{$data['Moedas']=0;}
                return $data;
            }
            
        }
    }
?>