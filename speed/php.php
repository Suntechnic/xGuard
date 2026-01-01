<?php
// тестирует скорость выполнения PHP-кода на сервере
$StartTime = microtime(true);
// Пример кода для тестирования скорости
$Sum = 0;
$ar = [];
for ($I = 0; $I < 2000000; $I++) {
    if ($I % 2 == 0) {
        $Sum += $I;
    } else {
        $Sum += 1;
    }
    $ar[] = $I;

    if (count($ar) >= 2000) {
        sort($ar);
        rsort($ar);
        $ar = [];
    } else {
        $Sum = ceil($Sum/2) + count($ar);
    }

    
}

$ExecutionTime = microtime(true) - $StartTime;

// запишим время в лог
$LogFile = __DIR__.'/logs/php.log.txt';
$LogEntry = date('Y-m-d H:i:s')." ".$ExecutionTime."\n";
file_put_contents($LogFile, $LogEntry, FILE_APPEND);