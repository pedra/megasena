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
$numInicial = 1;
$numFinal = 60;

//tamanho dos quadrados
$retW = 13;

?>
<div class="grafico">
	<h2>Concursos: <?=$concursos?> </h2>
	<svg height="<?php echo 40 + ($concursos * $retW);?>" id="calendar-graph">
		<g transform="translate(40, 20)">

			<?php

			//Texto Horizontal com os números
			$dx = 4;
			$dy = -5;
			for($i = $numInicial; $i <= $numFinal ; $i ++){
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
			for($n = $numInicial; $n <= $numFinal ; $n ++){
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