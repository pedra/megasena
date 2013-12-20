<?php //getCont();

echo '<pre>Fazedo download dos resultados
http://www1.caixa.gov.br/loterias/_arquivos/loterias/D_megase.zip

';

//Apagando o arquivo antigo (TODO: guardar backup ?)
if(file_exists('mega.zip')) unlink('mega.zip');
//download
@file_put_contents('mega.zip', file_get_contents('http://www1.caixa.gov.br/loterias/_arquivos/loterias/D_megase.zip'));

if(!file_exists('mega.zip')) exit('Não consegui fazer o download dos resultado</pre>');

//=====================================================================================
echo 'Extraindo o arquivo HTM
';
$zip = new ZipArchive; 
$zip->open('mega.zip');
$zip->extractTo('./');
$zip->close();
if(file_exists('mega.zip')) unlink('mega.zip');
if(file_exists('T2.GIF')) unlink('T2.GIF');
if(!file_exists('D_MEGA.HTM')) exit('Não consegui extrair o arquivo HTM</pre>'); 

//=====================================================================================
echo 'Formatando arquivo HTM
';
//pegando o arquivo e extraindo dados . . . 
$file = file_get_contents('D_MEGA.HTM');
$outFile = '';
$pointer = $i = 0;
$fileSize = strlen($file);
$data = array();

//=====================================================================================
//Loop para leitura dos dados do arquivo HTML
while($i <= $fileSize){
	$i = strpos($file, '<tr', $pointer);
	if($i !== false) {
		$in = strpos($file, '</tr', $i);
		if($in === false) {
			trigger_error('Erro na formatação do HTM');
			exit();
		}
		$data[] = getTd(substr($file, $i, ($in - $i + 5)));
		//substr($file, $i, ($in - $i + 5));
		$pointer = $in;
	} else break;
}

if(file_exists('D_MEGA.HTM')) unlink('D_MEGA.HTM');
unset($data[0]);
echo 'Atualizando banco de dados
';
dbInsert($data);
echo 'Finalizado.
</pre>';

//=====================================================================================

function getTd($str){
	$pointer = $i = 0;
	$outFile = '';
	$data = array();
	while($i <= strlen($str)){
		$i = strpos($str, '<td>', $pointer);
		if($i !== false) {
			$in = strpos($str, '</td>', $i);
			if($in === false) {
				trigger_error('Erro na formatação do HTM (TD)');
				exit();
			}
			$data[] = substr($str, $i, ($in - $i + 5));
			$pointer = $in;
		} else break;
	}
	if(isset($data[0])){
		$d[0] = int($data[0]); 
		$d[1] = int($data[9]);
		$d[2] = int($data[2]);
		$d[3] = int($data[3]);
		$d[4] = int($data[4]);
		$d[5] = int($data[5]);
		$d[6] = int($data[6]);
		$d[7] = int($data[7]);
		
		return $d;
	} else return array();
}

//String para Inteiro
function int($s){
	return (int)preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$s);
}

//Salvando os dados em SQLite
function dbInsert($data){

	$pdo = Connect::hdl();

	//apagando os dados antigos
	$pdo->exec('DELETE FROM megasena.resultados');
	
	//inserindo os novos dados
	foreach($data as $v){		
		$pdo->exec('INSERT INTO megasena.resultados (ID, ACERTO, D1, D2, D3, D4, D5, D6) VALUES ('.$v[0].','.$v[1].','.$v[2].','.$v[3].','.$v[4].','.$v[5].','.$v[6].','.$v[7].')');
	}
}

function getCont(){

	$pdo = Connect::hdl();

	echo '<h2>Frequência de Sorteio</h2>';
	$a = array();
	for($i = 1; $i <= 60; $i ++){
		$sth = $pdo->prepare('SELECT COUNT(*)SIZE 
								FROM megasena.resultados 
								WHERE D1='.$i.' 
								   OR D2='.$i.'
								   OR D3='.$i.'
								   OR D4='.$i.'
								   OR D5='.$i.'
								   OR D6='.$i);
		$sth->execute();
		$result = $sth->fetch();
		$a[$i] = $result['SIZE'];
	}
	arsort($a);
	echo '
	<style>
	table {border:1px solid #DDD; padding:0 3px;}
	table tr th {padding:6px 10px; color:#000; border-bottom:1px solid #000}
	table tr td {padding:6px 10px; color:#BBD; border: none; border-bottom:1px solid #DDD}
	table tr:hover td { background:#DDD; color:#000;}
	</style>';
	echo '<table cellspacing="0">
	<tr><th>Posição</th><th>Número</th><th>Frequência</th></tr>';
	$ct = 0;
	foreach($a as $k=>$v){
		$ct ++;
		echo '<tr><td>'.$ct.'º</td><td>'.$k.'</td><td>'.$v.'</td></tr>';
	}
	echo '</table>';

	exit();

}