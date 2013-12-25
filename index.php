<?php 
include 'php/lib/megasena/mega.php';

$mega = new Mega();
$mega->setTmpDir(__DIR__.'/php/lib/megasena/temp');
$mega->setDbType('mysql');

//Create DBase
//$mega->createDb(); exit('criando DB - fim');

//update
//$mega->update();

if($mega->getError() != null) exit('<p style="color:#F00">Error: '.$mega->getError().'</p>');

include 'php/view/head.html';
echo $mega->test();
include 'php/view/footer.html';
