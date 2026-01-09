<?
// заблокированыне IP
$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
// нарушения
$lstViolations = file($LogViolationsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>
<p class="lead">
    Сервис активной защиты: мониторинг, фильтрация трафика, мгновенные оповещения и аналитика
    инцидентов. Прозрачное подключение без простоя.
</p>
<div class="grid">
    <a class="card" href="?page=blocklist">
        <div class="pill" style="background:rgba(255,92,92,0.18);color:#ffb3b3;">
            Block
        </div>
        <h3>Заблокировано IP</h3>
        <strong><?php echo count($lstBlockedIPs); ?></strong>
        <p>количество заблокированных IP на данный момент</p>
    </a>
    <a class="card" href="?page=violations">
        <div class="pill" style="background:rgba(138,92,255,0.18);color:#e4d8ff;">Pulse</div>
        <h3>Нарушений</h3>
        <strong><?php echo count($lstViolations); ?></strong>
        <p>количество зафиксированных нарушений</p>
    </a>
    <div class="card">
        <div class="pill" style="background:rgba(79,209,197,0.14);color:#caf7ef;">Rumer</div>
        <h3>Киргуду</h3>
        <p>тут пока не понятно что за инфа)))</p>
    </div>
</div>
