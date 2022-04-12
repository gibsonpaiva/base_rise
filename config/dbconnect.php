<?php
    // Desabilita mensagens de erro
    error_reporting(0);
    ini_set('display_errors', FALSE);

    class Database {
        // Conecta com a DB
        function open(){
            // Conecta à base de dados do RISE
            return new mysqli("localhost", "rise", "1T0n3@2oi8", "rise");
        }

        // Encerra a conexão com a DB
        function close($connection){
            // Encerra a conexão com a base de dados
            mysqli_close($connection);
        }

        // Executa uma query - Conecta à base de dados, executa a query e encerra a conexão
        function run($query){
            // Conecta à base de dados
            $db = $this->open();
            // Define o Collation como UTF8
            //mysqli_set_charset($db,"utf8");
            // Executa a Query
            $result = $db->query($query);
            // Encerra a conexão com a base de dados
            $this->close($db);
            // Retorna o resultado da Query
            return $result;
        }

        // Executa mais de uma query - Conecta à base de dados, executa a query e encerra a conexão
        function run_multi($query){
            // Conecta à base de dados
            $db = $this->open();
            // Executa a Query
            $result = mysqli_multi_query($db, $query);
            if ($result) {
                do {
                    if (($result = mysqli_store_result($db)) === false && mysqli_error($db) != "") {
                        echo "Query failed: ".mysqli_error($db);
                    }
                } while (mysqli_more_results($db) && mysqli_next_result($db));
            } else {
                echo "First Query failed...".mysqli_error($db);
            }
            // Encerra a conexão com a base de dados
            $this->close($db);
            // Retorna o resultado da Query
            return $result;
        }
    }
?>
