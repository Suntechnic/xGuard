<?php
// получает данные с других друженственных проектов, о заблокированных IP и добавляет их в локальный блоклист
require_once __DIR__.'/../.defined.php';

// получаем проекты из projects.ini
$projects = parse_ini_file($MainDir.'/projects.ini', true);

$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!is_array($lstBlockedIPs)) {
    $lstBlockedIPs = [];
}
$Changed = false;

foreach ($projects as $Code=>$dctProject) {
    $Name = $dctProject['name'] ?? $Code;
    $Domen = $dctProject['domen'];
    $Sef = $dctProject['sef'] ?? 'xGuard';
    $Key = $dctProject['key'];

    if (empty($Domen) || empty($Key)) continue;

    $Url = "https://$Domen/{$Sef}/block/api/index.php?action=iplist";
    $Url .= "&key=".urlencode($Key);
    $Response = file_get_contents($Url);
    if ($Response === false) continue;

    $lstIP = explode("\n", trim($Response));
    foreach ($lstIP as $IP) {
        $IP = trim($IP);
        if (filter_var($IP, FILTER_VALIDATE_IP) && !in_array($IP, $lstBlockedIPs)) {
            // добавляем в локальный блоклист
            $lstBlockedIPs[] = $IP;

            $LogBlockEntry = time()."\t".$IP."\t"."Exchange from $Name";
            file_put_contents($LogBlockFile, $LogBlockEntry.PHP_EOL, FILE_APPEND);

            $Changed = true;
        }
    }
}

if ($Changed) {
    // сохраняем обновленный блоклист
    sort($lstBlockedIPs);
    file_put_contents($BlockFile, implode(PHP_EOL, $lstBlockedIPs).PHP_EOL);
    include_once $MainDir.'/applyblock.php';
}