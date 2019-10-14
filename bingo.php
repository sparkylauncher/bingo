<html>
<head>
<title>Online PHP Script Execution</title>
<style>
    
    table,th,td{
        border: 3px solid black;
    }

    table{
        table-layout: fixed;
        width:20em;
        text-align: center;
    }

    .negro{
        background-color: black;
    }
</style>
</head>
<body>
<?php
   $arrayNumeros = array(
        range(1,9),
        range(10,19),
        range(20,29),
        range(30,39),
        range(40,49),
        range(50,59),
        range(60,69),
        range(70,79),
        range(80,90));

   $arraypos = array(
       array_rand($arrayNumeros[0],3),
       array_rand($arrayNumeros[1],3),
       array_rand($arrayNumeros[2],3),
       array_rand($arrayNumeros[3],3),
       array_rand($arrayNumeros[4],3),
       array_rand($arrayNumeros[5],3),
       array_rand($arrayNumeros[6],3),
       array_rand($arrayNumeros[7],3),
       array_rand($arrayNumeros[8],3));
   
   $arraynum = array();
    
    //posibilidades para las columnas con un elemento
    $columnas1 = array(array_fill(0,3,0),array_fill(0,3,0),array_fill(0,3,0));
    $unos = array(random_int(0,2),random_int(0,2),random_int(0,2));
    
    //creo 3 colunas de 1 elemento
    $columnas1[0][$unos[0]] = 1;
    $columnas1[1][$unos[1]] = 1;
    $columnas1[2][$unos[2]] = 1;
    
    //reduzco el número de elementos de las columnas afectadas
    $ncols = array(5,5,5);
    $ncols[$unos[0]]--;
    $ncols[$unos[1]]--;
    $ncols[$unos[2]]--;

    $bingo = $columnas1;

    //caso 4-4-4, 1 columna de 1 elemento por fila. tiene 2 elementos 110, 2 de 101 y 2 de 011
    if($ncols[0] == $ncols[1] && $ncols[1] == $ncols[2]){
    	$bingo[]= array(1,1,0);
    	$bingo[]= array(1,1,0);
    	$bingo[]= array(0,1,1);
    	$bingo[]= array(0,1,1);
    	$bingo[]= array(1,0,1);
    	$bingo[]= array(1,0,1);
    }
    //caso 3-4-5 en cualquier variante. El opuesto (por ej de 001 es 110) del 3 repite 3 veces, la pareja (3-5) 2v y la pareja (3-4) 1.
    else if($ncols[0] == 3 || $ncols[1] == 3 || $ncols[2] == 3){
    	$pos3 = array_search(3, $ncols);
    	$array0 = array(1,1,1);
    	$array0[$pos3] = 0;
    	for($i = 0; $i < 3;$i++){
    		$bingo[] = $array0;
    	}
    	$pos5 = array_search(5, $ncols);
    	$array0 = array(0,0,0);
    	$array0[$pos3] = 1;
    	$array0[$pos5] = 1;
    	for($i = 0; $i < 2;$i++){
    		$bingo[] = $array0;
    	}
    	$pos4 = array_search(4, $ncols);
    	$array0 = array(0,0,0);
    	$array0[$pos3] = 1;
    	$array0[$pos4] = 1;
    	$bingo[] = $array0;
    }
    //caso 5-5-2 en sus 3 variantes.
    else{
    	$pos2 = array_search(2, $ncols);
    	$array0 = array(1,1,1);
    	$array0[$pos2] = 0;
    	for($i = 0; $i < 4;$i++){
    		$bingo[] = $array0;
    	}
    	for($i = 0; $i < 3;$i++){
    		if($i != $pos2){
    			$array0 = array(0,0,0);
    			$array0[$pos2] = 1;
    			$array0[$i] = 1;
    			$bingo[] = $array0;
    		}
    	}    	
    }

    //pasamos el bingo de binario a decimal
    $arraycuenta = array();
    for($i = 0; $i < 9; $i++){
        $arraycuenta[$i] = $bingo[$i][0]*4 + $bingo[$i][1]*2 + $bingo[$i][2];

    }

    //ordeno para cumplir la condicion de dos columnas iguales no pueden estar consecutivas(shuffle para anyadir aleatoriedad)
    shuffle($arraycuenta);
    $comp = $arraycuenta[0];
    $ultimo = 0;
    $iterador = 1;
    while($ultimo != $iterador){
        if($comp == $arraycuenta[$iterador]){
            for($i = $iterador + 1; ;$i++){
                if($arraycuenta[$iterador] != $arraycuenta[$i % 9]){
                    $aux = $arraycuenta[$iterador];
                    $arraycuenta[$iterador]  = $arraycuenta[$i % 9];
                    $arraycuenta[$i % 9] = $aux;
                    $ultimo = $iterador;
                    break;
                }
            }
        }
        $comp = $arraycuenta[$iterador];
        $iterador = ($iterador + 1) % 9;
    }
    
    //vuelvo a transformar los numeros a binario y los devuelvo a bingo ya colocados
    for ($i=0; $i < 9; $i++) { 
        $var = str_pad(decbin($arraycuenta[$i]),3,0,STR_PAD_LEFT);
        for($j = 0; $j < 3; $j++){
            $bingo[$i][$j] = $var[$j];
        }
    }

    //transformo los 0 y 1 en los numeros del bingo que ya hemos sacado antes (0 es casilla tapada)
    for($i = 0; $i < 9;$i++){
        for($j = 0; $j < 3;$j++){
            if($bingo[$i][$j] == 1){
                $pos = $arraypos[$i][$j];
                $bingo[$i][$j] = $arrayNumeros[$i][$pos];
            }
            
        }
    }
?>

<h1> !BINGO!: </h1>
<table>
    <?php 
        for($i = 0; $i < 3;$i++){
            echo "<tr>";
            for($j = 0; $j < 9; $j++){
                if($bingo[$j][$i] == 0){
                    echo "<td class=\"negro\"></td>";
                }else{
                    echo "<td>".$bingo[$j][$i]."</td>";
                }
            }
            echo "</tr>";
        }
    ?>
</table>
</body>
</html>