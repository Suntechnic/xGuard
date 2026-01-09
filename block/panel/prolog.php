<?php
include_once __DIR__.'/../.defined.php';

if (!is_array($ini)) {
    die('Ошибка загрузки конфигурационного файла.');
}

if (!is_array($ini['credentials']) || !isset($ini['credentials']['username']) || !isset($ini['credentials']['password'])) {
    die('Ошибка: неверные учетные данные в конфигурационном файле.');
}

$Hash = md5($ini['credentials']['username'].':'.$ini['credentials']['password'].'_xGuard');


if ($_POST['username'] === $ini['credentials']['username'] && $_POST['password'] === $ini['credentials']['password']) {
    define('XGUARD_AUTHORIZED', true);
    // устанавливаем куки
    setcookie('xGuard_auth', $Hash, time() + (86400 * 30), "/"); // куки действительны 30 дней
} elseif (isset($_COOKIE['xGuard_auth']) && $_COOKIE['xGuard_auth'] === $Hash) {
    define('XGUARD_AUTHORIZED', true);
} else {
    define('XGUARD_AUTHORIZED', false);
}