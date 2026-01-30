<?php
/* пример вызова из места нарушениея
$IP = trim($_SERVER['HTTP_DDG_CONNECTING_IP']?$_SERVER['HTTP_DDG_CONNECTING_IP']:$_SERVER['HTTP_X_FORWARDED_FOR']);

// обязательно должны быть эти поля:
// IP - IP адрес нарушителя
// USER_AGENT - UserAgent нарушителя
// URI - с какой страницы пришел нарушитель
// REASON - причина нарушения, если оно есть

if ($IP) {
    $xGuardEvent = [
            'IP' => $IP,
            'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
            'URI' => $_SERVER['HTTP_REFERER'],
            'REASON' => '404:'.$_SERVER['REQUEST_URI'],
        ];
    include($_SERVER["DOCUMENT_ROOT"]."/xGuard/block/main.php");
}
*/
include_once __DIR__.'/.defined.php';

if (!isset($xGuardEvent['IP']) || !isset($_SERVER['DOCUMENT_ROOT'])) return;
$IP = $xGuardEvent['IP'];

// Пропуск по белым IP адресам //////////////////////////////////////////////////////
if (file_exists($WhitelistFile)) {
    $lstWhiteIP = file($WhitelistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (in_array($IP, $lstWhiteIP)) {
        return;
    }
}
// Пропуск по белым IP адресам //////////////////////////////////////////////////////


// проверяем существование папки с правилами
$refBlockRules = [];
$refExcludeRules = [];
if (is_dir($RulesDir)) {
    
    // папка с правилами мгновенной блокировки
    $BlockRulesDir = $RulesDir.'/block';
    if (is_dir($BlockRulesDir)) {
        // загружаем все правила мгновенной блокировки
        $lstBlockRulesFile = glob($BlockRulesDir.'/*.txt');
        foreach ($lstBlockRulesFile as $RuleFile) {
            $NameRule = basename($RuleFile, '.txt');
            $refBlockRules[$NameRule] = file($RuleFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
    }
    
    // проверяем не нарушил ли кто-то из правил мгновенной блокировки
    foreach ($refBlockRules as $FieldName => $lstRules) {
        if (isset($xGuardEvent[$FieldName]) && !empty($xGuardEvent[$FieldName])) {
            $FieldValue = $xGuardEvent[$FieldName];
            foreach ($lstRules as $Rule) {
                if (preg_match('/'.$Rule.'/i', $FieldValue)) {
                    // нарушение найдено - блокируем
                    $BlockIP = $IP;
                    $BlockIPReason = 'Нарушение правила ('.$FieldName.': '.$Rule.')';
                    include(__DIR__.'/block.php');
                    die('Давай, до свидания!');
                }
            }
        }
    }

    // папка с правилами исключениями
    $ExcludeRulesDir = $RulesDir.'/exclude';
    if (is_dir($ExcludeRulesDir)) {
        // загружаем все правила исключений
        $lstExcludeRulesFile = glob($ExcludeRulesDir.'/*.txt');
        foreach ($lstExcludeRulesFile as $RuleFile) {
            $NameRule = basename($RuleFile, '.txt');
            $refExcludeRules[$NameRule] = file($RuleFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
    }

    // проверяем не должны ли мы пропустить этого нарушителя по правилам исключений
    foreach ($refExcludeRules as $FieldName => $lstRules) {
        if (isset($xGuardEvent[$FieldName]) && !empty($xGuardEvent[$FieldName])) {
            $FieldValue = $xGuardEvent[$FieldName];
            foreach ($lstRules as $Rule) {
                if (preg_match('/'.$Rule.'/i', $FieldValue)) {
                    return; // пропускаем этого нарушителя
                }
            }
        }
    }
}

// логируем нарушение в отдельный общий файл
$LogEntry = time()."\t".$IP."\t".$xGuardEvent['REASON']."\t".$xGuardEvent['URI']."\t".$xGuardEvent['USER_AGENT'].PHP_EOL;
file_put_contents($LogViolationsFile, $LogEntry, FILE_APPEND);

/// Если мы оказались здесь - переходим к подсчету нарушений данного IP за последний период времени
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
        $BlockIPReason = 'Превышение лимитов нарушений (последнее: '.$xGuardEvent['REASON'].')';
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

