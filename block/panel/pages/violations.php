<?
$lstBlockedIPs = file($BlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lstViolations = file($LogViolationsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lstWhiteIP = file($WhitelistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
// лог файла нарушений разделитель в строках \t
// колоноки: 0 - время нарушения (timestamp)
//          1 - IP адрес
//          2 - основание нарушения
//          3 - URL страницы, на которой зафиксировано нарушение
//          4 - User-Agent клиента

if ($_GET['filter'] && $_GET['value']){
    $strFilterValue = trim($_GET['value']);
    $lstFilteredViolations = [];

    if ($_GET['filter'] == 'ip'){
        $FIP = $strFilterValue;
    }
    foreach ($lstViolations as $strViolation){
        $arrViolation = explode("\t", $strViolation);
        switch ($_GET['filter']){
            case 'ip':
                if ($arrViolation[1] == $FIP){
                    $lstFilteredViolations[] = $strViolation;
                }
                break;
            case 'reason':
                if (stripos($arrViolation[2], $strFilterValue) !== false){
                    $lstFilteredViolations[] = $strViolation;
                }
                break;
            case 'url':
                if (stripos($arrViolation[3], $strFilterValue) !== false){
                    $lstFilteredViolations[] = $strViolation;
                }
                break;
            case 'agent':
                if (stripos($arrViolation[4], $strFilterValue) !== false){
                    $lstFilteredViolations[] = $strViolation;
                }
                break;
        }
    }
    $lstViolations = $lstFilteredViolations;
    ?>
    
    <a href="?page=violations"
        class="button"
        >Сбросить фильтр</a>

    <?if($FIP){?>
        <h2 class="<?if(in_array($FIP, $lstBlockedIPs)){?>blocked<?}?> <?if(in_array($FIP, $lstWhiteIP)){?>whitelisted<?}?>">
            IP: <?=$FIP?> <?=(in_array($FIP, $lstBlockedIPs)) ? '[Заблокирован]' : ''?> <?=(in_array($FIP, $lstWhiteIP)) ? '[В белом списке]' : ''?>
        </h2>
        <?if(in_array($FIP, $lstBlockedIPs)){?>
            <span class="actions">
                <a 
                        class="button unblock" 
                        href="?page=violations&action=unblock&ip=<?=$FIP?>" 
                        title="Разблокировать"
                    >Разблокировать</a>
                <a 
                        class="button unblockperm" 
                        href="?page=violations&action=unblockperm&ip=<?=$FIP?>"
                        title="Разблокировать и добавить в белый список"
                    >Разблокировать и добавить в белый список</a>
            </span>
        <? } else { ?>
            <a class="button block" href="?page=violations&action=block&ip=<?=$FIP?>" class="button">Заблокировать</a>
        <? } ?>
    <? } ?>
    <?
}

?>
<div class="log">
    <?foreach ($lstViolations as $strViolation){?>
        <?$arrViolation = explode("\t", $strViolation); $VIP = $arrViolation[1]?>
        <div class="log-entry">
            <span class="log-time"><?=date("Y-m-d H:i:s", $arrViolation[0])?></span>
            <a 
                    class="log-ip <?if(in_array($VIP, $lstBlockedIPs)){?>blocked<?}?> <?if(in_array($VIP, $lstWhiteIP)){?>whitelisted<?}?>"
                    href="?page=violations&filter=ip&value=<?=$VIP?>"
                ><?=$VIP?></a>
            <a class="log-reason" href="?page=violations&filter=reason&value=<?=$arrViolation[2]?>"><?=$arrViolation[2]?></a>
            <a class="log-url" href="?page=violations&filter=url&value=<?=$arrViolation[3]?>"><?=$arrViolation[3]?></a>
            <a class="log-agent" href="?page=violations&filter=agent&value=<?=$arrViolation[4]?>">Agent: <?=$arrViolation[4]?></a>
        </div>
    <?}?>
</div>