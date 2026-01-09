<?php
include_once __DIR__.'/.defined.php';
include_once __DIR__ . '/header.php';

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
    <form class="auth" method="post">
      <div>
        <label for="login">Логин</label>
        <input id="login" name="username" type="text" autocomplete="username" required>
      </div>
      <div>
        <label for="password">Пароль</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
      </div>
      <div>
        <button type="submit">Войти в xGuard</button>
      </div>
    </form>
    <?php
    include_once __DIR__ . '/footer.php';
    exit;
}

?>
<div class="grid">
    <div class="card">
    <div class="pill">Shield</div>
    <h3>Веб-фильтрация</h3>
    <p>WAF-профили, защита от брутфорса, rate limiting и сигнатуры под ваш стек.</p>
    </div>
    <div class="card">
    <div class="pill" style="background:rgba(138,92,255,0.18);color:#e4d8ff;">Pulse</div>
    <h3>Live-мониторинг</h3>
    <p>Дашборд событий в реальном времени, поведенческие аномалии и всплески запросов.</p>
    </div>
    <div class="card">
    <div class="pill" style="background:rgba(79,209,197,0.14);color:#caf7ef;">Alert</div>
    <h3>Уведомления</h3>
    <p>Инциденты в Telegram/Email, ленты аудита и экспорт логов для SIEM.</p>
    </div>
</div>
<?

include_once __DIR__ . '/footer.php';