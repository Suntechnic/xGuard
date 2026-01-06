<?php
$HtaccessPath = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';
$BlockRule = "\nDeny from ".$IP;
file_put_contents($HtaccessPath, $BlockRule, FILE_APPEND);

die('Давай, до свидания!');