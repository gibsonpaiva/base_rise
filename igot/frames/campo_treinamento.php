<style>
    table {
    font-family: EXO;
    border-collapse: collapse;
    width: 100%;
    }

    td {
    border: 0.5px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    th {
    border: 0.5px solid #dddddd;
    text-align: center;
    text-color: #000000;
    padding: 10px;
    }

    tr:nth-child(even) {
    background-color: #auto;
    }
</style>

<div class="tab-pane" id="CampoDeTreinamento">

    <img width="100px" align="left" height="auto" alt="Guerreiro" src="/igot/img/guerreiro5.png">
    <br><h4><p class="titulo">CAMPO DE TREINAMENTO</p></h4>
          
    <p class="conteudo"> É um ambiente construído para permitir ao guerreiro, criar máquinas virtuais para LABORATÓRIOS e aumentar seus conhecimentos.
    
    <hr class='featurette-divider'>
    <div class="box-body">
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <h3> REGRAS </h3>
                <hr>
                
                <table width="100%"> 
                    
                    <tr><td><b> 1</b></td><td> O Campo de Treinamento está sob responsabilidade do time de Serviços. </td></tr>
                    <tr><td><b> 2</b></td><td> Está disponível por tempo integral (24x7);</td></tr>
                    <tr><td><b> 3</b></td><td> TODO o conteúdo é classificado como LABORATÓRIO, portanto sem garantias de DISPONIBILIDADE e PROTEÇÃO DE DADOS;</td></tr>
                    <tr><td><b> 4</b></td><td> O uso correto do ambiente requer conhecimentos básicos de VMware vSphere. Se você não se encontra confiante em utilizá-lo peça auxílio à colegas com mais experiência;</td></tr>
                    <tr><td><b> 5</b></td><td> O vCenter não está disponível para alterações. Caso precise, crie no seu ambiente, sua própria estrutura de ESXi com vCenter;</td></tr>
                    <tr><td><b> 6</b></td><td> Toda mudança no vCenter do CT deve ser requisitada e autorizada;</td></tr>
                    <tr><td><b> 7</b></td><td> Foi criado um RESOURCE POOL com recursos "ilimitados" e dedicado a você com permissões de administrador;</td></tr>
                    <tr><td><b> 8</b></td><td> Utilize APENAS os Datastores nomeados como DINÂMICOS para hospedar suas VM's;</td></tr>
                    <tr><td><b> 9</b></td><td> Os Datastores nomeados como FIXOS estarão dedicados para laboratórios compartilhados com outros colegas e/ou para apresentação para clientes. Não utilize sem permissão;</td></tr>
                    <tr><td><b>10</b></td><td> Utilize os Datastores nomeados como ISOS & TEMPLATES para hospedar APENAS os tais e NUNCA suas VMs para laboratórios dinâmicos;</td></tr>
                    <tr><td><b>11</b></td><td> Dentro dos Datastores nomeados como ISO & TEMPLATES, procure seguir os padrões de pastas para cada função de VMs e/ou Sistemas Operacionais;</td></tr>
                    <tr><td><b>12</b></td><td> Lembre-se que outros colegas também estarão compartilhando o mesmo ambiente;</td></tr>
                    <tr><td><b>13</b></td><td> Use apenas os recursos concedidos a você, de maneira responsável e controlada;</td></tr>
                    <tr><td><b>14</b></td><td> Não utilize mais endereços IP que os já concedidos à você para evitarmos conflitos com os de outros colegas;</td></tr>
                    <tr><td><b>15</b></td><td> Na necessidade de utilizar mais endereços, entre em contato com a coordenação do CT;</td></tr>
                    <tr><td><b>16</b></td><td> Desligue VMs e remova discos (vmdks) não utilizados;</td></tr>
                    <tr><td><b>17</b></td><td> Compartilhe seus treinamentos e soluções com os colegas;</td></tr>
                    <tr><td><b>18</b></td><td> Se o objetivo do treinamento/solução foi alcançado e não for mais necessário para você e/ou seus colegas, remova-o;</td></tr>
                    <tr><td><b>19</b></td><td> Armazene suas VMs dentro das pastas criadas em SEU NOME;</td></tr>
                    <tr><td><b>20</b></td><td> Para melhor utilização de recursos de dados do CT, crie SEMPRE seus discos virtuais ( VMDKs ) no formato THIN. Mude do padrão THICK na criação da VM para THIN;</td></tr>
                    <tr><td><b>21</b></td><td> Não conceda recursos exagerados à sua VM para não sobrecarregar o ambiente e demais laboratórios de outros colegas.</td></tr>

                </table>
                
                <hr>
                <h3> ACESSOS </h3>
                
                Não tem ainda um ambiente criado para você? <h3> <a href="mailto:ricardo.paiva@itone.com.br?subject=CAMPO DE TREINAMENTO : Solicitação de criação do meu ambiente"><b>> > > Peça o seu aqui < < <</b></a></h3>               
                <table> 
                    
                    <tr><td><b>vCenter </b></td><td> <b><a href="https://itonect.itone.com.br"> HTTPS://ITONECT.ITONE.COM.BR ou 172.16.16.5 </a> </b></td></tr>
                    <tr><td><b>User </b></td><td> e-mail @itone.com.br </td></tr>
                    <tr><td><b>Netmask </b></td><td> 255.255.252.0 </td></tr>
                    <tr><td><b>Gateway </b></td><td> 172.16.16.1 </td></tr>
                    <tr><td><b>AD|DNS </b></td><td> itonelabdc01 | 172.16.16.6 </td></tr>	
                    <tr><td><b>Domínio </b></td><td> lab.local </td></tr>
                    <br>

                </table>
                
                <hr>
                <h3> Assim que fizer o primeiro acesso :</h3>
                
                IMPORTANTE : Não começe a usar o CT antes de executar os passos abaixo.<br><br>
                
                                   
                    <h4>1 : Instale o certificado em sua estação</h4>
                    - Entre no vCenter como detalhado em ACESSOS;<br>
                    - Clique no link "Download trusted root CA certificates";<br>
                    - Se o arquivo for aberto dentro do navegador, clique com o botão direito + "salvar como" e depois execute;<br>
                    - Extraia a pasta e execute o arquivo com extensão crt dentro de certs\win;<br>
                    - Na tela "Certificate", clique em "Install Certificate";<br><br>
                    - OBS : Uploads de arquivos dentro de datastores podem não funcionar caso esse passo não seja seguido.<br><br>
                    
                    <h4>2 : Instale o PLUGIN de autenticação avançada</h4>
                    - Entre no vCenter como detalhado em ACESSOS;<br>
                    - Na tela de "Getting Started", clique em "LAUNCH VSPHERE CLIENT (HTML5)";<br>
                    - Clique no link "Download Enhanced Authentication Plugin" na parte inferior do navegador e execute a instalação;<br>
                    - Caso o plugin já esteja instalado, o link acima pode não estar visível e você pode desconsiderar esse passo;<br>
                    - Caso você esteja acessando de um segundo navegador será pedido uma nova instalação e uma remoção do anterior;<br>
                    - Você deve remover uma instalação e depois instalar novamente toda vez que for mudar de navegador; <br>
                    - Para evitar que o passo acima aconteça, escolha o seu navegador de preferência e não mude;<br>
                    - Será solicitado que abra o "vmware-cpi-launcher.exe". Marque a opção para sempre abrir;<br><br>
                    - OBS : Uploads de arquivos dentro de datastores podem não funcionar caso esse passo não seja seguido.<br>

                    <br>
               

                <hr>
                <h3> INFRAESTRUTURA </h3>
                
                <br><img align="center" src="/igot/img/ct-2.png"><br><br>

                <hr>
               
                <p align="center"><br><span align="left" class="glyphicon glyphicon-signal fa-3x"><font face="Exo">&nbsp;ENDEREÇOS DE REDE</span><br><br></p>
                
                <table width="100%">

                    <tr><th>	REDE	    </th><th> INICIO	</th><th> QTDE	</th><th>   FIM	</th><th>	GUERREIRO	    </th><th>	DIVISÃO	</th></tr>
                    
                    <tr><th>	172.16.18.	</th><td><b>	01	</b></td><td>	09	</td><td><b>	9	</b></td><td>	Ricardo Paiva	</td><td>	SQUID	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	10	</b></td><td>	10	</td><td><b>	19	</b></td><td>	Peterson Pires	</td><td>	ICS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	20	</b></td><td>	10	</td><td><b>	29	</b></td><td>	Darlan Baquer	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	30	</b></td><td>	10	</td><td><b>	39	</b></td><td>	Cristiano Cabral & DBAs </td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	40	</b></td><td>	10	</td><td><b>	49	</b></td><td>	Rafael Teixeira	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	50	</b></td><td>	10	</td><td><b>	59	</b></td><td>	Fernando Muniz	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	60	</b></td><td>	10	</td><td><b>	69	</b></td><td>	Pedro Silva	    </td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	70	</b></td><td>	10	</td><td><b>	79	</b></td><td>	Thiago Neres	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	80	</b></td><td>	10	</td><td><b>	89	</b></td><td>	Gustavo Drumond	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	90	</b></td><td>	10	</td><td><b>	99	</b></td><td>	Auria Alves	    </td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	100	</b></td><td>	10	</td><td><b>	109	</b></td><td>	Julio Fernandes	</td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	110	</b></td><td>	10	</td><td><b>	119	</b></td><td>	Rodrigo Celestino</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	120	</b></td><td>	10	</td><td><b>	129	</b></td><td>	Carlos Barroso	</td><td>	BDM	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	130	</b></td><td>	10	</td><td><b>	139	</b></td><td>	Gustavo Santos	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	140	</b></td><td>	10	</td><td><b>	149	</b></td><td>	Denis Vieira	</td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	150	</b></td><td>	10	</td><td><b>	159	</b></td><td>	Priscilla Rega	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	160	</b></td><td>	10	</td><td><b>	169	</b></td><td>	Felipe Baeta	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	170	</b></td><td>	10	</td><td><b>	179	</b></td><td>	Felipe Roque	</td><td>	ISE	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	180	</b></td><td>	05	</td><td><b>	184	</b></td><td>	Carlos Papalardo</td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	185	</b></td><td>	05	</td><td><b>	189	</b></td><td>	Auria Alves     </td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	190	</b></td><td>	05	</td><td><b>	194	</b></td><td>	Auria Alves     </td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	195	</b></td><td>	05	</td><td><b>	199	</b></td><td>	Thales Menezes  </td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	200	</b></td><td>	05	</td><td><b>	204	</b></td><td>	Fábio Nogueira  </td><td>	IMS	</td></tr>
                    <tr><th>	172.16.18.	</th><td><b>	205	</b></td><td>	10	</td><td><b>	214	</b></td><td>	Cristiano Cabral & DBAs   </td><td>	ISE	</td></tr>
                </table>

                <p align="center"><br><span align="center" class="fa fa-database fa-3x"><font face="Exo">&nbsp;DATASTORES</span><br><br></p>

                <table>

                    <tr><th> DATASTORE	                                </th>    <th> TAMANHO	    </th><th>FUNÇÃO	                            </th></tr>
                    <tr><td> VNX5300-0883-L02-1TB-DS01-ISOS&TEMPLATES    </td>   <td><b>	1 TB	</b></td><td>D01 : ISOS E TEMPLATES   	    </td></tr>
                    <tr><td> VNX5300-0883-L01-1TB-DS01-LAB-DINAMICOS     </td>   <td><b>	1 TB	</b></td><td>D01 : LABORATÓRIOS DINÂMICOS	</td></tr>
                    <tr><td> VNX5300-0883-L03-1TB-DS03-LAB-DINAMICOS	 </td>   <td><b>	1 TB    </b></td><td>D02 : LABORATÓRIOS DINÂMICOS	</td></tr>
                    <tr><td> VNX5300-0883-L04-1TB-DS04-LAB-DINAMICOS	 </td>   <td><b>	1 TB    </b></td><td>D02 : LABORATÓRIOS DINÂMICOS	</td></tr>
                    
                </table>
            
            </div>
        </div>
    </div>
</div>
