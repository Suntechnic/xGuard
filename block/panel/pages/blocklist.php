<?
// заблокированыне IP
$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


// загружаем лог блокировок разделенный \t: первый элемент - timestamp, второй - IP, третий - причина блокировки
$logBlock =  file($LogBlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
// преобразуем в массив массивов
$refLogBlock = [];
foreach ($logBlock as $Line) {
    $ulRow = explode("\t", $Line);
    $refLogBlock[$ulRow[1]] = $Line;
}
?>
<div class="log">
    <?foreach ($lstBlockedIPs as $Ip):?>
    <a class="log-entry" href="?page=violations&filter=ip&value=<?=$Ip?>">
        <span class="log-ip" href="?page=violations&filter=ip&value=<?=$Ip?>"><?=$Ip?><span>
        <?if (isset($refLogBlock[$Ip])):?>
        <span class="log-time"><?=date('d.m.Y H:i', (int)explode("\t", $refLogBlock[$Ip])[0])?></span>
        <?endif?>
    </a>
    <?endforeach?>
</div>