<?php
/* пример вызова из места нарушениея
$IP = trim($_SERVER['HTTP_DDG_CONNECTING_IP']?$_SERVER['HTTP_DDG_CONNECTING_IP']:$_SERVER['HTTP_X_FORWARDED_FOR']);

if ($IP) {
    $dctBlock = [
            'IP' => $IP,
            'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
            'URI' => $_SERVER['HTTP_REFERER'],
            'REASON' => '404:'.$_SERVER['REQUEST_URI'],
        ];
    include($_SERVER["DOCUMENT_ROOT"]."/xGuard/block/main.php");
}
*/
include_once __DIR__.'/.defined.php';

if (!isset($dctBlock['IP']) || !isset($_SERVER['DOCUMENT_ROOT'])) return;
$IP = $dctBlock['IP'];


// логируем нарушение в отдельный общий файл
$LogEntry = time()."\t".$IP."\t".$dctBlock['REASON']."\t".$dctBlock['URI']."\t".$dctBlock['USER_AGENT'].PHP_EOL;
file_put_contents($LogViolationsFile, $LogEntry, FILE_APPEND);

// Пропуск по UserAgent исключениям //////////////////////////////////////////////////////
if (isset($ini['settings']['UserAgentExclude'])) {
    $lstUserAgentExclude = explode(',', $ini['settings']['UserAgentExclude']);

    foreach ($lstUserAgentExclude as $key => $value) {
        $lstUserAgentExclude[$key] = trim($value);
    }
} else {
    $lstUserAgentExclude = [];
}
if (isset($dctBlock['USER_AGENT']) && !empty($dctBlock['USER_AGENT'])) {
    $UserAgent = $dctBlock['USER_AGENT'];
    if (count($lstUserAgentExclude) > 0) {
        foreach ($lstUserAgentExclude as $Agent) {
            if (stripos($UserAgent, $Agent) !== false) return;
        }
    }
}
// Пропуск по UserAgent исключениям //////////////////////////////////////////////////////



// Пропуск по белым IP адресам //////////////////////////////////////////////////////
if (file_exists($WhitelistFile)) {
    $lstWhiteIP = file($WhitelistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (in_array($IP, $lstWhiteIP)) {
        return;
    }
}
// Пропуск по белым IP адресам //////////////////////////////////////////////////////



$MinuteLimited = (int)$ini['settings']['MinuteLimited']?:5;
$TenSecondLimited = (int)$ini['settings']['TenSecondLimited']?:3;
$TTL = (int)$ini['settings']['TTL']?:3600;


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
        $BlockIP = $IP;
        $BlockIPReason = 'Превышение лимитов нарушений (последнее: '.$dctBlock['REASON'].')';
        include(__DIR__.'/block.php');
        die('Давай, до свидания!');
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

