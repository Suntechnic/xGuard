<?php
/* пример вызова из места нарушениея
$dctBlock = [
    'IP' => $_SERVER['REMOTE_ADDR'],
    'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
    'URI' => $_SERVER['REQUEST_URI']
];
include($_SERVER["DOCUMENT_ROOT"]."/x-guard/block/main.php");
*/
include_once __DIR__.'/.defined.php';

$MinuteLimited = (int)$ini['settings']['MinuteLimited']?:5;
$TenSecondLimited = (int)$ini['settings']['TenSecondLimited']?:3;
$TTL = (int)$ini['settings']['TTL']?:3600;
if (isset($ini['settings']['UserAgentsExclude'])) {
    $lstUserAgentsExclude = explode(',', $ini['settings']['UserAgentsExclude']);
    foreach ($lstUserAgentsExclude as $key => $value) {
        $lstUserAgentsExclude[$key] = trim($value);
    }
} else {
    $lstUserAgentsExclude = [];
}



if (!isset($dctBlock['IP']) || !isset($_SERVER['DOCUMENT_ROOT'])) return;

$IP = $dctBlock['IP'];

if (isset($dctBlock['USER_AGENT']) && !empty($dctBlock['USER_AGENT'])) {
    $UserAgent = $dctBlock['USER_AGENT'];
    if (is_array($lstUserAgentsExclude) && count($lstUserAgentsExclude) > 0) {
        foreach ($lstUserAgentsExclude as $Agent) {
            if (stripos($UserAgent, $Agent) !== false) return;
        }
    }
}

// логируем нарушение в отдельный общий файл
$LogEntry = time()."\t".$IP."\t".$dctBlock['REASON']."\t".$dctBlock['URI']."\t".$dctBlock['USER_AGENT'].PHP_EOL;
file_put_contents($LogViolationsFile, $LogEntry, FILE_APPEND);

$FilePathIP = $LogFilesIPDir.$IP.'.txt';

// если файла есть
if (file_exists($FilePathIP)) {

    // прочитаем из него данные и узнаем сколько раз данный IP нарушал за последний минуту и за последние 10 секунд
    $data = file($FilePathIP, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $TotalViolations = count($data);


    $RecentMinuteViolations = 0;
    $RecentTenSecondViolations = 0;
    $OneMinuteAgo = time() - 60;
    $TenSecondLimited = time() - 10;

    foreach ($data as $Timestamp) {
        if ((int)$Timestamp >= $OneMinuteAgo) {
            if ((int)$Timestamp >= $TenSecondLimited) {
                $RecentTenSecondViolations++;
                if ($RecentTenSecondViolations > $TenSecondLimited) break;
            }
            $RecentMinuteViolations++;
            if ($RecentMinuteViolations > $MinuteLimited) break;
        }
    }


    // если нарушений за последнюю минуту больше 5, то блокируем
    if ($RecentMinuteViolations >= $MinuteLimited
            || $RecentTenSecondViolations >= $TenSecondLimited
        ) {
        include(__DIR__.'/block.php');
    }
}


// удаляем созданные более часа назад файлы логов
$lstLogFiles = glob($LogFilesIPDir.'*.txt');
$TimeBarier = time() - $TTL;
foreach ($lstLogFiles as $LogFile) {
    if (filemtime($LogFile) < $TimeBarier) {
        unlink($LogFile);
    }
}

// просто добавим время нарушения в файлы
file_put_contents($FilePathIP, time().PHP_EOL, FILE_APPEND);

