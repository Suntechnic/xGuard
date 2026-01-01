<?php
include __DIR__ . '/.prolog.php';

$UID = 'php'; // тест установки соединения с базой данных


// Пример кода для тестирования скорости
$Sum = 0;
$ar = [];
$Iterations = $ini[$UID]['iterations'] ?? 200000;
for ($I = 0; $I < $Iterations; $I++) {
    if ($I % 2 == 0) {
        $Sum += $I;
    } else {
        $Sum += 1;
    }
    $ar[] = $I;

    if (count($ar) >= 2000) {
        sort($ar);
        $ars = $ar;
        rsort($ar);

        $ar[] = array_sum($ar);
        $ar[] = count($ar);
        $ar[] = $Iterations/count($ar);
        $ar[] = $Iterations;
        
        $ars[] = $Sum;

        $ar = array_merge($ar, $ars);

        sort($ar);
        rsort($ar);

        $ar = [];
    } else {
        $Sum = ceil($Sum/2) + count($ar);
        if ($Sum % 4 == 0) {
            $Sum = $Sum - floor($I/2);
        } else {
            $Sum = $Sum + 2;
        }
    }

    
}

include __DIR__ . '/.epilog.php';