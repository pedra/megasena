<?php 
/*include 'php/lib/neos/core.php';
Main::run();*/


include 'php/lib/neos/megasena/mega.php';
$mega = new Mega();
$mega->settmpdir(__DIR__.'/php/lib/neos/megasena/temp');
$mega->setdbtype('mysql');
$mega->setdatabase('megasena');

//update
//$mega->update();
if($mega->geterror() != null) exit('<p style="color:#F00">Error: '.$mega->geterror().'</p>');

//exit($mega->getprintall());

echo $mega->getCont();
echo $mega->geterror();

exit();

define('TPATH', __DIR__.'/php/temp/');

include 'php/view/head.html';
include 'php/view/home.php';
include 'php/view/footer.html';



// ===================================
function p($v, $x = false){
	$v = '<pre>'.print_r($v, true).'</pre>';
	if($x) exit($v);
	else echo $v;
}