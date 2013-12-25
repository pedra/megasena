<?php 
include 'php/lib/megasena/mega.php';

$mega = new Mega();
$mega->setTmpDir(__DIR__.'/php/lib/megasena/temp');
$mega->setDbType('mysql');

//create DataBase
//$mega->createDb(); exit('criando DB - fim');

//update
//$mega->update();

//Display errors
if($mega->getError() != null) exit('<p style="color:#F00">Error: '.$mega->getError().'</p>');

//HTML Views ---------------------------------------------------------------------------------
//Cache
$cache = __DIR__.'/php/view/temp.cache.html';
if(file_exists($cache) && (time() - filemtime($cache)) < 50 ) 
	echo file_get_contents($cache).'<p class="status">in cache: '.date('Y.m.d H:i:s', filemtime($cache)).'</p>';
else {
    //start output
    ob_start();

    include 'php/view/head.html';
    echo $mega->test();
    include 'php/view/home_v.php';
    include 'php/view/footer.html';

    //Create new cache
    file_put_contents($cache, ob_get_contents());
}
