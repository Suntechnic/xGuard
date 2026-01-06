<?php
// загрузим конфигурацию из ini файла
$ini = parse_ini_file(__DIR__ . '/config.ini', true);

if ($ini === false) {
    die('Ошибка загрузки конфигурационного файла.');
}

$Hash = md5($ini['credentials']['username'].':'.$ini['credentials']['password'].'_xGuard');

if ($_POST['username'] === $ini['credentials']['username'] && $_POST['password'] === $ini['credentials']['password']) {
    echo 'Авторизация успешна!';
    // устанавливаем куки
    setcookie('auth', $Hash, time() + (86400 * 30), "/"); // куки действительны 30 дней
} elseif (isset($_COOKIE['auth']) && $_COOKIE['auth'] === $Hash) {
    echo 'Авторизация успешна по куки!';
} else {
    ?>
    <form method="POST" action="">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Войти">
    </form>
    <?
    die();
}