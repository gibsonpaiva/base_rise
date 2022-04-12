<?php
    // Inicia uma Sessão se ainda não tiver iniciado para acesso às variáveis
    if(session_id() == '') { session_start(); }

    // Inclue objetos referentes as Permissões
    include_once "../../config/permissions.php";
    $EditableBy = new Permission();
    $autorized = false;

    // Inclue objetos referentes a bases de dados
    include_once "../../config/db.php";

    // Identifica o Item se o arquivo já existir para conferência da permissão
    if($_POST['action'] != "insert"){
        $db_filebox = new FileBox;
        $idItem = $db_filebox->getArquivo($_POST['id'], $_POST['idArea'])[0]['idItem']; // Identifica o ID do Item relacionado ao Arquivo
    }
    // Define a tabela a ser manipulada com base no ID da Área assim como os valores padrões além de verificar a permissão de acesso para continuação do Upload
    switch($_POST['idArea']){
        // IGOT->Eventos
        case IGOT_EVENTOS:
            // Nome da Tabela
            $table = TBL_ARQUIVOSEVENTOS;

            // Certifica de que o usuário possui permissão de edição no Evento
            if($EditableBy->admin('igot')) {
                $autorized = true;
            } else {
                // Instancia os objetos referente a base de dados
                $db_igot = new IGOT();
                // Obtém informações do evento para conferência de permissão
                $evento = $db_igot->getEventos(0, $idItem, false);
                if($EditableBy->general($evento[0]['idExercito']) || $EditableBy->proprietario($evento[0]['idProprietario'])){
                    $autorized = true;
                }
            }
        break;

        // RISE->Usuarios
        case RISE_USUARIOS:
            // Nome da Tabela
            $table = TBL_ARQUIVOSUSUARIOS;

            // Define os valores padrões da imagem do Perfil
            $_POST['idItem'] = $_SESSION['user']['id']; // ID do Usuário
            // Certifica de que o usuário ainda não possui uma Imagem de Perfil
            if($_POST['action'] == 'insert'){
                // Verifica se o usuário já possui uma imagem, se já existir atualiza
                $sql = "SELECT idArquivo FROM {$table} WHERE idItem={$_POST['idItem']}";
                // Instancia o objeto referente a base de dados
                $database = new Database();
                // Conecta à base de dados
                $db = $database->open();
                // Executa a Query
                $result = $db->query($sql);
                // Verifica o resultado
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    // Define os valores padrões da imagem do Perfil
                    $_POST['action'] = "update"; // Atualiza a ação a ser realizada
                    $_POST['idArquivo'] = $row['idArquivo']; // ID do Arquivo
                }            
            }

            // Autoriza o usuário a manipular seu perfil
            $autorized = true;
        break;
    }

    if($autorized){ // Se acesso autorizado
        // Instancia o objeto referente a base de dados
        $database = new Database();
        // Conecta à base de dados
        $db = $database->open();

        // Diretório de Uploads (Linux)
        //$upfolder = "/d01/rise/www/uploads";

        // Identifica o Tipo de ação a ser executada
        switch($_POST['action']){
            // Registra o Arquivo
            case 'insert':
                /* Prepara o Arquivo */
                $file_type = $_FILES['fileToUpload']['type'];
                $file_name = $_FILES['fileToUpload']['name'];
                $file_size = $_FILES['fileToUpload']['size'];
                /* Prepara o Arquivo (Windows) */
                // $uploaded_file = str_replace("\\", "/", $_FILES['fileToUpload']['tmp_name']); // Ajusta o caminho do arquivo em ambiente (WINDOWS)
                /* Prepara o Arquivo (Linux) */
                // $uploaded_file = "{$upfolder}/{$_FILES['fileToUpload']['name']}"; // Ajusta o caminho do arquivo em ambiente (LINUX)
                // move_uploaded_file($file_path, $uploaded_file); // Move o Arquivo para uma pasta em que o MySQL tenha acesso (LINUX)
                // $file_path = $_FILES['fileToUpload']['tmp_name'];
                /* SQL Query (Windows / Linux) */
                // $sql = "INSERT INTO {$table} (Arquivo, idTipo, idArea, idItem, Descricao, Nome, Formato, Tamanho, RegistradoPor) VALUES (LOAD_FILE('{$uploaded_file}'), {$_POST['idTipo']}, {$_POST['idArea']}, {$_POST['idItem']}, '{$_POST['Descricao']}', '{$file_name}', '{$file_type}', {$file_size}, {$_SESSION['user']['id']})";

                /* Prepara o Arquivo (Memoria) */
                $file_hexa = addslashes(file_get_contents($_FILES['fileToUpload']['tmp_name'])); // Carrega o arquivo em memória para INSERT ou UPDATE
                /* SQL Query (Memoria) */
                $sql = "INSERT INTO {$table} (idTipo, idArea, idItem, Descricao, Nome, Formato, Tamanho, RegistradoPor, Arquivo) VALUES ({$_POST['idTipo']}, {$_POST['idArea']}, {$_POST['idItem']}, '{$_POST['Descricao']}', '{$file_name}', '{$file_type}', {$file_size}, {$_SESSION['user']['id']}, '{$file_hexa}')";
                
                // Executa a Query
                $db->query($sql);

                // Aguarda os segundo(s) dentro dos parêntesis
                sleep(1);

                // SQL Query
                $sql = 'SELECT idArquivo AS id, Tipo FROM '.VIEW_ARQUIVOS.' WHERE Nome="'.$file_name.'" AND Tamanho='.$file_size.' AND idArea='.$_POST['idArea'].' AND idItem='.$_POST['idItem'].' ORDER BY id DESC LIMIT 1';

                // Executa a Query
                $result = $db->query($sql);
                // Verifica o resultado
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    $id = $row['id'];
                    $Tipo = $row['Tipo'];
                }

				/* Exclui o arquivo temporário após gravá-lo no banco (LINUX) */
				// unlink($uploaded_file); // Exclui o arquivo temporário (LINUX)

                // Prepara os valores a serem retornados
                $data = array(
                    'id' => $id,
                    'Tipo' => $Tipo
                );
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Upload registrado com sucesso',
                    'data' => $data
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            case 'update':
                /* Prepara o Arquivo */
                $file_type = $_FILES['fileToUpload']['type'];
                $file_name = $_FILES['fileToUpload']['name'];
                $file_size = $_FILES['fileToUpload']['size'];
                $file_hexa = addslashes(file_get_contents($_FILES['fileToUpload']['tmp_name'])); // Carrega o arquivo em memória para INSERT ou UPDATE
                /* SQL Query (Memoria) */
                $sql = "UPDATE {$table} SET idTipo={$_POST['idTipo']}, idArea={$_POST['idArea']}, idItem={$_POST['idItem']}, Descricao='{$_POST['Descricao']}', Nome='{$file_name}', Formato='{$file_type}', Tamanho={$file_size}, ModificadoPor={$_SESSION['user']['id']}, Arquivo='{$file_hexa}' WHERE idArquivo={$_POST['idArquivo']}";
                // Executa a Query
                $db->query($sql);

                // Aguarda os segundo(s) dentro dos parêntesis
                sleep(1);

                // SQL Query
                $sql = 'SELECT idArquivo AS id, Tipo, Formato, Arquivo FROM '.VIEW_ARQUIVOS.' WHERE Nome="'.$file_name.'" AND Tamanho='.$file_size.' AND idArea='.$_POST['idArea'].' AND idItem='.$_POST['idItem'].' ORDER BY id DESC LIMIT 1';

                // Executa a Query
                $result = $db->query($sql);
                // Verifica o resultado
                if(mysqli_num_rows($result)>0) {
                    $row = $result->fetch_assoc();
                    // Prepara os valores a serem retornados
                    $data = array(
                        'id' => $row['id'],
                        'Tipo' => $row['Tipo'],
                        'Formato' => $row['Formato'],
                        'ArquivoB64' => base64_encode($row['Arquivo'])
                    );
                    $returnData = array(
                        'status' => 'ok',
                        'msg' => 'Upload registrado com sucesso',
                        'data' => $data
                    );
                }
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;

            // Exclui o Arquivo
            case 'delete':
                // SQL Query
                $sql = "DELETE FROM {$table} WHERE idArquivo={$_POST['id']}";
                // Executa a Query
                $db->query($sql);

                // Prepara os valores a serem retornados
                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'Arquivo excluído com sucesso',
                );
                // Retorna os novos valores para atualização do DataGrid
                echo json_encode($returnData);
            break;
        }

        // Encerra a conexão com a base de dados
        $database->close($db);
    } else { // Se acesso negado
        // Prepara mensagem de retorno
        $returnData = array(
            'status' => '',
            'msg' => 'Não autorizado!'
        );
        // Retorna os novos valores para atualização do DataGrid
        echo json_encode($returnData);
    }
?>