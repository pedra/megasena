<?php
$db = $mega->getAll('D1, D2, D3, D4, D5, D6, ACERTO');

$concursos = count($db);

//Configuração
$concInicial = 1;
$concFinal = $concursos;
$numInicial = 1;
$numFinal = 60;

//tamanho dos quadrados
$retW = 15;
?>
<div class="grafico">
    <h2>Mapeamento de Resultados</h2>
    <p>As dezenas em vermelho são de concursos com ACERTO</p>
    <svg height="<?php echo 40 + ($concursos * $retW); ?>" id="calendar-graph">
        <g transform="translate(40, 20)">

        <?php
        //Texto Horizontal com os números
        $dx = 4;
        $dy = -5;
        for ($i = $numInicial; $i <= $numFinal; $i ++) {
            echo '<text text-anchor="middle" class="wday" dx="' . $dx . '" dy="' . $dy . '" >' . $i . '</text>';
            $dx += $retW;
        }

        //Texto vertical com os concursos
        $dy = 9;
        for ($i = $concInicial; $i <= $concFinal; $i ++) {
            echo '<text text-anchor="end" text-align="right" class="vday" dx="-20" dy="' . $dy . '" >' . $i . '</text>';
            $dy += $retW;
        }

        //comcursos
        $t1 = 0;

        //cada número						
        for ($n = $numInicial; $n <= $numFinal; $n ++) {
            echo '<g transform="translate(' . $t1 . ', 0)">';
            $t1 += $retW;

            $y = 0;
            //cada concurso
            for ($i = $concInicial; $i <= $concFinal; $i ++) {
                $cor = ($db[($i - 1)][6] >= 1) ? 'F00' : 'BBB';
                if ($db[($i - 1)][0] != $n &&
                    $db[($i - 1)][1] != $n &&
                    $db[($i - 1)][2] != $n &&
                    $db[($i - 1)][3] != $n &&
                    $db[($i - 1)][4] != $n &&
                    $db[($i - 1)][5] != $n) $cor = 'EEE';

                echo '<rect class="day" width="' . ($retW - 2) . '" height="' . ($retW - 2) . '" y="' . $y . '" style="fill: #' . $cor . ';"></rect>';
                $y += $retW;
            }

            //fechando cada número
            echo '</g>';
        }
        ?>
        </g>
    </svg>
</div>
