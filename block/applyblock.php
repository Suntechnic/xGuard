<?php
// просто добавляем заблокированные IP из файла $BlockFile
// в htaccess
include_once __DIR__.'/.defined.php';

$HtaccessPath = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';
$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (is_array($lstBlockedIPs) && count($lstBlockedIPs) > 0) {
    // считаем файл в переменную и разобеьм ее по строкам ### xGaurd Block ### на три части
    $HtaccessContent = file_get_contents($HtaccessPath);
    $ulHtaccessParts = explode("### xGaurd Block ###", $HtaccessContent);
    // если частей не три, значит блоков нет, создаем новый шаблон
    // блокировка не возможна без размеченного .htaccess - выходим
    if (count($ulHtaccessParts) == 3) {
        
        // формируем новый список блокировок
        $BlockString = PHP_EOL;
        foreach ($lstBlockedIPs as $BlockedIP) {
            $BlockString .= "Deny from ".$BlockedIP.PHP_EOL;
        }
        $ulHtaccessParts[1] = $BlockString;
        // собираем обратно файл
        $NewHtaccessContent = implode("### xGaurd Block ###", $ulHtaccessParts);
        // сохраняем файл
        file_put_contents($HtaccessPath, $NewHtaccessContent);

        $LogMainEntry = date("Y-m-d H:i:s")." | INFO: .htaccess file updated with xGuard blocked IPs.".PHP_EOL;
    } else {
        $LogMainEntry = date("Y-m-d H:i:s")." | ERROR: .htaccess file is not properly marked for xGuard block management.".PHP_EOL;
    }
} else {
    $LogMainEntry = date("Y-m-d H:i:s")." | INFO: No blocked IPs found to update .htaccess.".PHP_EOL;
}


file_put_contents($LogMainFile, $LogMainEntry, FILE_APPEND);