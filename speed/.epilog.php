<?php

$ExecutionTime = microtime(true) - $StartTime;
echo $ExecutionTime;
//echo "Время выполнения: ".$ExecutionTime." секунд \n";
// запишим время в лог

if (!is_dir(__DIR__.'/logs/')) {
    mkdir(__DIR__.'/logs/', 0777, true);
}

$LogFile = __DIR__.'/logs/'.$UID.'.log.txt';
$LogEntry = date('Y-m-d H:i:s')." ".$ExecutionTime."\n";
file_put_contents($LogFile, $LogEntry, FILE_APPEND);

// удалим старые записи, чтобы лог не разрастался
$MaxLogSize = 5 * 1024 * 1024; // 5 МБ
if (file_exists($LogFile) && filesize($LogFile) > $MaxLogSize) {
    // скопируем фай в db.log.old.txt
    copy($LogFile, $LogFile.'.old');
    // очистим текущий лог
    file_put_contents($LogFile, '');
}