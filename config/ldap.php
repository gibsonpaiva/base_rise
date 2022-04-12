<?php
    class LDAP {
        // Parâmetros para conexão com o AD
        private $ad;
                
        /* Função para abrir uma conexão com o AD utilizando usuário e senha informados */
        function openADConnect($username, $password) {
            // Parâmetros para conexão com o AD
            $server = 'itonebh.local';
            $domain = 'itonebh.local';

            try {
                // Conecta ao servidor LDAP
                $this->ad = ldap_connect("ldap://{$server}"); //or die('Impossível conectar ao servidor LDAP.');
                
                ldap_set_option($this->ad, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($this->ad, LDAP_OPT_REFERRALS, 0);
                
                // Autentica no AD utilizando usuário e senha informados
                return ldap_bind($this->ad, "{$username}@{$domain}", $password); //or die('Impossível conectar ao AD.');

            } catch(Exception $e) {
                echo 'Falha na conexão com AD: '.$e->getMessage();
                return false;
            }            
        }

        /* Consulta Propriedades do Usuário via LDAP */
        function getObjectProperties($object, $properties=array("cn", "displayname", "mail", "department", "memberof")) {
            // Parâmetros para conexão com o AD
            $dn = 'DC=itonebh,DC=local';

            try {
                // Efetua a consulta LDAP
                $result = ldap_search($this->ad, $dn, "(samaccountname=$object)", $properties); //or die ("Erro na pesquisa LDAP: ". ldap_error($this->ad));
                $entries = ldap_get_entries($this->ad, $result);

                // Exibe todas as propriedades com seus respectivos valores
                //echo "<pre>"; print_r($entries); echo "</pre>";

                // Retornar as propriedades com seus respectivos valores
                return $entries;
            } catch(Exception $e) {
                echo 'Erro na pesquisa LDAP: '.$e->getMessage();
                return false;
            }            
        }

        /* Verifica de forma se recursividade se o usuário é membro de um determinado grupo */
        function checkGroup($user, $group) {
            // Obtém o DN do usuário e grupo de segurança
            $userdn = $this->getObjectProperties($user, array('dn'))[0]['dn'];
            $groupdn = $this->getObjectProperties($group, array('dn'))[0]['dn'];

            // Efetua a consulta LDAP
            $result = ldap_read($this->ad, $userdn, "(memberof={$groupdn})", array('members'));
            if (!$result) return false;
            
            $entries = ldap_get_entries($this->ad, $result);
            
            // Verifica se o usuário é membro do grupo
            if ($entries['count'] <= 0) return false;
            else return true;
        }

        /* Verifica de forma recursiva se o usuário é membro de um determinado grupo */
        function checkGroupEx($userdn, $groupdn) {            
            // Efetua a consulta LDAP
            $result = ldap_read($this->ad, $userdn, '(objectclass=*)', array('memberof'));
            if (!$result) return false;
            
            $entries = ldap_get_entries($this->ad, $result);
                        
            // Certifica de que o usuário é membro de algum grupo
            if ($entries['count'] <= 0) return false;
            if (empty($entries[0]['memberof'])) return false;

            // Verifica se o usuário é membro do grupo
            for ($i=0; $i<$entries[0]['memberof']['count']; $i++) {
                if ($entries[0]['memberof'][$i] == $groupdn) return true;
                elseif (checkGroupEx($entries[0]['memberof'][$i], $groupdn)) return true;
            }
        }

        /* Função para encerrar a conexão com o AD */
        function closeADConnect() {
            // Encerra a conexão
            ldap_unbind($this->ad);
        }
    }
?>