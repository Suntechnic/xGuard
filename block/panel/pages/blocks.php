<?
$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lstWhiteIP = file($WhitelistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); if(!$lstWhiteIP)$lstWhiteIP = [];

// загружаем лог блокировок разделенный \t: первый элемент - timestamp, второй - IP, третий - причина блокировки
$logBlock =  file($LogBlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

?>
<div class="log">
    <?foreach ($logBlock as $Line): $ulRow = explode("\t", $Line);$Ip=$ulRow[1]?>
    <span class="log-entry" href="?page=violations&filter=ip&value=<?=$Ip?>">
        

        <span class="log-time">
            <?=date('d.m.Y H:i', (int)$ulRow[0])?>
        </span>
        
        <a class="log-ip <?if(in_array($Ip, $lstBlockedIPs)){?>blocked<?}?> <?if(in_array($Ip, $lstWhiteIP)){?>whitelisted<?}?>" href="?page=violations&filter=ip&value=<?=$Ip?>"><?=$Ip?></a>
        
        <span class="log-reason">
            <?if (isset($ulRow[2])):?>
            <?=htmlspecialchars($ulRow[2])?>
            <?else:?>
            Неизвестная причина
            <?endif?>
        </span>
    </span>
    <?endforeach?>
</div>