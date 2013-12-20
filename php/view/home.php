<?php 
include 'php/lib/neos/megasena/connect.php';

$pdo = Connect::hdl();
$sth = $pdo->prepare('SELECT D1, D2, D3, D4, D5, D6 FROM megasena.resultados');
$sth->execute();
$db = $sth->fetchAll();

$concursos = count($db);

//Configuração
$concInicial = 1;
$concFinal = $concursos;
$numInicial = 17;
$numFinal = 17;

?>
<div class="grafico">
	<h2>Concursos: <?=$concursos?> </h2>
	<svg width="<?php echo 40 + ($concursos * 10);?>" id="calendar-graph">
		<g transform="translate(20, 20)">

			<?php

			//Texto vertical com os números
			$dy = 9;
			for($i = $numInicial; $i <= $numFinal ; $i ++){
				echo '
				<text text-anchor="middle" class="wday" dx="-10" dy="'.$dy.'" >'.$i.'</text>';
				$dy+= 10;
			}

			//comcursos
			$t1 = 0;
			$color = true;

			for($i = $concInicial; $i <= $concFinal; $i ++){
				echo '<g transform="translate('.$t1.', 0)">';
				$t1 += 10;

				//cada número
				$y = 0;				
				for($n = $numInicial; $n <= $numFinal ; $n ++){
					echo '<rect class="day" width="8" height="8" y="'.$y.'" style="fill: #'.((in_array($n, $db[($i - 1)])) ? '678' : 'DDD').';"></rect>';
					$y += 10;
				}

				//fechando
				echo '</g>';

			}
			?>

		</g>
	</svg>
</div>