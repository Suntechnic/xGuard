<?php

// считываем список заблокированных IP
$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!is_array($lstBlockedIPs)) {
    $lstBlockedIPs = [];
}
// проверяем что IP еще нет в этом списке
$IP = $dctBlock['IP'];
if (!in_array($IP, $lstBlockedIPs)) {

    $lstBlockedIPs[] = $IP;
    // сортируем
    sort($lstBlockedIPs);
    // сохраняем обратно в файл
    file_put_contents($BlockFile, implode("\n", $lstBlockedIPs)."\n");

    $LogBlockEntry = time()."\t".$IP."\tЗаблокирован".PHP_EOL;
    file_put_contents($LogBlockFile, $LogBlockEntry, FILE_APPEND);
    
    
}
// применим блокировку
include_once __DIR__.'/applyblock.php';
die('Давай, до свидания!');