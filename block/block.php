<?php

// считываем список заблокированных IP
$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!is_array($lstBlockedIPs)) {
    $lstBlockedIPs = [];
}

// проверяем что IP еще нет в этом списке
if ($BlockIP === null || $BlockIP === '') return;

if (!in_array($BlockIP, $lstBlockedIPs)) {

    $lstBlockedIPs[] = $BlockIP;
    // сортируем
    sort($lstBlockedIPs);

    // сохраняем обратно в файл
    file_put_contents($BlockFile, implode("\n", $lstBlockedIPs)."\n");

    $LogBlockEntry = time()."\t".$BlockIP."\t".$BlockIPReason;
    file_put_contents($LogBlockFile, $LogBlockEntry.PHP_EOL, FILE_APPEND);

    $IncidentID = md5($LogBlockEntry);
    $LogIncidentFile = $LogFilesIncidentsDir.$IncidentID.'.log';
    $LogIncidentEntry = date('Y-m-d H:i:s').PHP_EOL.print_r($xGuardEvent,true).PHP_EOL.print_r($_SERVER,true);
    file_put_contents($LogIncidentFile, $LogIncidentEntry.PHP_EOL);
}
// применим блокировку
include_once __DIR__.'/applyblock.php';
//