<?php
// ротация лог-файлов
require_once __DIR__.'/../.defined.php';

// Rotate log files
// по умолчанию два мегабайта
$LogMaxFileSize = $ini['settings']['LogMaxFileSize']?$ini['settings']['LogMaxFileSize']:2097152;

$lstLogFiles = glob($LogFilesDir.'*.log');
foreach ($lstLogFiles as $LogFile) {
    if (file_exists($LogFile) && (filesize($LogFile) >= $LogMaxFileSize)) {
        $NewLogFile = $LogFile.'.'.date('Ymd_His').'.log';
        rename($LogFile, $NewLogFile);
        touch($LogFile);
    }
}
