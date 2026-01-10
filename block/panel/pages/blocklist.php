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
    <span class="log-entry" href="?page=violations&filter=ip&value=<?=$Ip?>">
        <span class="actions">
            <a 
                    class="action unblock" 
                    href="?page=blocklist&action=unblock&ip=<?=$Ip?>" 
                    title="Разблокировать"
                >✖</a>
            <a 
                    class="action unblockperm" 
                    href="?page=blocklist&action=unblockperm&ip=<?=$Ip?>"
                    title="Разблокировать и добавить в белый список"
                >✖</a>
        </span>
        <a class="log-ip blocked" href="?page=violations&filter=ip&value=<?=$Ip?>"><?=$Ip?></a>

        <span class="log-time">
            <?if (isset($refLogBlock[$Ip])):?>
            <?=date('d.m.Y H:i', (int)explode("\t", $refLogBlock[$Ip])[0])?>
            <?endif?>
        </span>
        <span class="log-reason">
            <?if (isset($refLogBlock[$Ip])):?>
            <?=htmlspecialchars(explode("\t", $refLogBlock[$Ip])[2])?>
            <?else:?>
            Неизвестная причина
            <?endif?>
        </span>
    </span>
    <?endforeach?>
</div>