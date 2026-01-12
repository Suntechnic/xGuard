<?php
$ini = parse_ini_file(__DIR__ . '/config.ini', true);

$RulesDir = __DIR__.'/rules/';

$StateDir = __DIR__.'/state/';
if (!file_exists($StateDir)) {
    mkdir($StateDir, 0755, true);
}
$BlockFile = $StateDir.'blocked.txt';
if (!file_exists($BlockFile)) file_put_contents($BlockFile, '');

$WhitelistFile = $StateDir.'whitelist.txt';
if (!file_exists($WhitelistFile)) file_put_contents($WhitelistFile, '');

$KeyFile = $StateDir.'key.txt';
if (!file_exists($KeyFile)) {
    $Key = bin2hex(random_bytes(16));
    file_put_contents($KeyFile, $Key);
} else {
    $Key = trim(file_get_contents($KeyFile));
}


// 
$LogFilesDir = __DIR__.'/logs/';
if (!file_exists($LogFilesDir)) {
    mkdir($LogFilesDir, 0755, true);
}
// папка для логов по IP
$LogFilesIPDir = $LogFilesDir.'ip/';
if (!file_exists($LogFilesIPDir)) {
    mkdir($LogFilesIPDir, 0755, true);
}
// папка для логов инцидентов
$LogFilesIncidentsDir = $LogFilesDir.'incidents/';
if (!file_exists($LogFilesIncidentsDir)) {
    mkdir($LogFilesIncidentsDir, 0755, true);
}

// файлы логов
// лог блокровок
$LogBlockFile = $LogFilesDir.'block.log';

// лог нарушений
$LogViolationsFile = $LogFilesDir.'violations.log';

// главный лог работы
$LogMainFile = $LogFilesDir.'main.log';



