<?php

require_once __DIR__.'/../.defined.php';

// Rotate log files
// по умолчанию два мегабайта
$LogMaxFileSize = $ini['settings']['LogMaxFileSize']?$ini['settings']['LogMaxFileSize']:2097152;

$lstLogFiles = glob($LogFilesDir.'*.log');
foreach ($lstLogFiles as $LogFile) {
    if (file_exists($LogFile) && (filesize($LogFile) >= $LogMaxFileSize)) {
        $strNewLogFile = $LogFile.'.'.date('YmdHis');
        rename($LogFile, $strNewLogFile);
        touch($LogFile);
    }
}
