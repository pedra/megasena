MegaSena
==

**View and data mining in Megasena game of Caixa Economica Federal - Brazil**

Help me to develop this little software with codes, ideas and ways of calculating probabilities.

prbr@ymail.com | http://zumbi.tk/megasena

Como Funciona
==

A classe Mega [/php/lib/megasena/mega.php] tem as seguintes funções:

* **CreateDb** - Cria o banco de dados Mysql (futuramente em Sqlite e Oracle)
* **Update** - Pega os dados do site da Caixa Econômica, descompacta, pega o conteúdo (html) e insere no banco de dados
* **Result** - Retorna um array com o concurso indicado ($array = $mega->result($id);). Se $id for null ou não indicado, esta função retorna o último sorteio registrado no banco de dados.
* **Test** - Gera uma partição compatível HTML para teste (echo $mega->test();)
 
Os parâmetros da classe Mega podem ser configurados ou obtidos através das funções setPAR(valor) e getPAR(), respectivamente. 'PAR' deve ser trocado pelo nome do parâmetro.

Para conhecer a lista completa de parâmetros consulte o arquivo da classe.

Exemplo:
 
    $mega = new Mega;
    
    //setup
    $mega->setUser('megasena');
    $mega->setTmpDir(__DIR__.'/php/lib/megasena/temp/');
  
    //test
    echo $mega->test();
  
    //display errors (if)
    echo $mega->getError();
  

