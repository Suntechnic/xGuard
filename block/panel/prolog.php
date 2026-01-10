<?php
include_once __DIR__.'/../.defined.php';

if (!is_array($ini)) {
    die('Ошибка загрузки конфигурационного файла.');
}

if (!is_array($ini['credentials']) || !isset($ini['credentials']['username']) || !isset($ini['credentials']['password'])) {
    die('Ошибка: неверные учетные данные в конфигурационном файле.');
}

$Hash = md5($ini['credentials']['username'].':'.$ini['credentials']['password'].'_xGuard');

/// авторизация
if ($_POST['username'] === $ini['credentials']['username'] && $_POST['password'] === $ini['credentials']['password']) {
    define('XGUARD_AUTHORIZED', true);
    // устанавливаем куки
    setcookie('xGuard_auth', $Hash, time() + (86400 * 30), "/"); // куки действительны 30 дней
} elseif (isset($_COOKIE['xGuard_auth']) && $_COOKIE['xGuard_auth'] === $Hash) {
    define('XGUARD_AUTHORIZED', true);
} else {
    define('XGUARD_AUTHORIZED', false);
}

// экшены
if (($_GET['action'] == 'unblock' || $_GET['action'] == 'unblockperm') 
        && filter_var($_GET['ip'], FILTER_VALIDATE_IP)) 
    {
    $lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // разблокировка IP
    $IpToUnblock = $_GET['ip'];
    $lstBlockedIPs = array_filter($lstBlockedIPs, function ($ip) use ($IpToUnblock) {
        return trim($ip) !== trim($IpToUnblock);
    });

    file_put_contents($BlockFile, implode("\n", $lstBlockedIPs) . "\n");
    include_once __DIR__.'/applyblock.php';

    if ($_GET['action'] == 'unblockperm') {
        // добавление файла в белый список
        $lstWhiteIP = file($WhitelistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!in_array($IpToUnblock, $lstWhiteIP)) {
            $lstWhiteIP[] = $IpToUnblock;
            file_put_contents($WhitelistFile, implode("\n", $lstWhiteIP)."\n");
        }
        
    }

    header('Location: ?page='.urlencode($_GET['page'] ?? ''));
} elseif ($_GET['action'] == 'block' && filter_var($_GET['ip'], FILTER_VALIDATE_IP)) {
    $BlockIP = $_GET['ip'];
    $BlockIPReason = $_GET['reason'] ?? 'Ручная блокировка';
    include(__DIR__.'/../block.php');

    header('Location: ?page='.urlencode($_GET['page'] ?? ''));
}