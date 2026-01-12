<?php
include_once __DIR__ . '/header.php';

if (defined('XGUARD_AUTHORIZED') && XGUARD_AUTHORIZED) {
    $lstAllowPages = ['dashboard','blocklist','blocks','violations','ip','settings','about'];

    $Page = $_GET['page'] ?? 'dashboard';

    if ($Page && in_array($Page, $lstAllowPages)) {
        $FilePage = __DIR__ . '/pages/'.$Page.'.php';
        if (file_exists($FilePage)) include $FilePage;
    }
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
    <?
}

include_once __DIR__ . '/footer.php';