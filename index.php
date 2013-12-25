<?php 
/*include 'php/lib/neos/core.php';
Main::run();*/


include 'php/lib/megasena/mega.php';
$mega = new Mega();
$mega->setTmpDir(__DIR__.'/php/lib/megasena/temp');
$mega->setDbType('mysql');
$mega->setDataBase('megasena');
$mega->setPassword('xmegasena123');

//$mega->createDb(); exit('criando DB - fim');

//update
//$mega->update();

if($mega->getError() != null) exit('<p style="color:#F00">Error: '.$mega->getError().'</p>');

//exit($mega->getprintall());


//echo '<pre>'.print_r($mega->result(), true).'</pre>';



define('TPATH', __DIR__.'/php/lib/megasena/temp/');

include 'php/view/head.html';
//include 'php/view/home.php';
echo $mega->getCont();
include 'php/view/footer.html';



// ===================================
function p($v, $x = false){
	$v = '<pre>'.print_r($v, true).'</pre>';
	if($x) exit($v);
	else echo $v;
} 