<?php
//testando o cache
$me = TPATH.'view/home_v_sort.cache.html';
if(file_exists($me) && (time() - filemtime($me)) < 50 ) 
	echo file_get_contents($me).'<p class="status">incache: '.date('Y.m.d H:i:s', filemtime($me)).'</p>';
else {

//Configuração
$concInicial = 1;
$concFinal = null;
$numInicial = 1;
$numFinal = 60;

//tamanho dos quadrados
$retW =9;


//BANCO DE DADOS ---------------------------------------------------
include 'php/lib/neos/megasena/connect.php';

$pdo = Connect::hdl();
$sth = $pdo->prepare('SELECT COUNT(*)SIZE 
							FROM megasena.resultados');
$sth->execute();
$result = $sth->fetch();
$concFinal = ($concFinal != null) ? $concFinal : $result['SIZE'];



$sth = $pdo->prepare('SELECT D1, D2, D3, D4, D5, D6 
						FROM megasena.resultados
						WHERE ID >= '.$concInicial.' 
						  AND ID <= '.$concFinal);
$sth->execute();

$ct = $concInicial - 1;
foreach($sth->fetchAll() as $v){	
	$db[$ct] = $v;
	$ct ++;	
}


 

$sdb = array();
for($i = 1; $i <= 60; $i ++){
	$sth = $pdo->prepare('SELECT COUNT(*)SIZE 
							FROM megasena.resultados 
							WHERE D1='.$i.' 
							   OR D2='.$i.'
							   OR D3='.$i.'
							   OR D4='.$i.'
							   OR D5='.$i.'
							   OR D6='.$i.'
							  AND (ID >= '.$concInicial.' AND ID <= '.$concFinal.')');
	$sth->execute();
	$result = $sth->fetch();
	$sdb[$i] = $result['SIZE'];
}
arsort($sdb); 
//p($db, true);

$concursos = count($db);

//BANCO DE DADOS --------------------------------------------------- [fim]


//Capturando a saída para o cache
ob_start();
?>
<div class="grafico">
	<h2>Gráficos dos Concursos</h2>
	<h3>Números mais freqüentes primeiro
		<br /><small>Concurso <b><?=$concInicial.'</b> ao <b>'.$concFinal.'</b> ('.$concursos.' no total)'?></small></h3>
	<svg height="<?php echo 40 + ($concursos * $retW);?>" id="calendar-graph">
		<g transform="translate(40, 20)">

			<?php

			//Texto Horizontal com os números
			$dx = 4;
			$dy = -5;
			//for($i = $numInicial; $i <= $numFinal ; $i ++){
			foreach($sdb as $i=>$v){
				echo '
				<text text-anchor="middle" class="wday" dx="'.$dx.'" dy="'.$dy.'" >'.$i.'</text>';
				$dx += $retW;
			}

			//Texto vertical com os concursos
			$dy = 9;
			for($i = $concInicial; $i <= $concFinal ; $i ++){
				echo '
				<text text-anchor="end" text-align="right" class="vday" dx="-20" dy="'.$dy.'" >'.$i.'</text>';
				$dy += $retW;
			}

			//comcursos
			$t1 = 0;

			//cada número						
			//for($n = $numInicial; $n <= $numFinal ; $n ++){
			foreach($sdb as $n=>$v){
				echo '<g transform="translate('.$t1.', 0)">';
				$t1 += $retW;

				$y = 0;
				for($i = $concInicial; $i <= $concFinal; $i ++){
					echo '<rect class="day" width="'.($retW - 2).'" height="'.($retW - 2).'" y="'.$y.'" style="fill: #'.
					((in_array($n, $db[($i - 1)])) ? '678' : 'DDD').';"></rect>';
					$y += $retW;
				}

				//fechando
				echo '</g>';
			}
			?>
		</g>
	</svg>
</div>
<?php
	$cache = ob_get_contents();
	file_put_contents($me, $cache);
}
?>