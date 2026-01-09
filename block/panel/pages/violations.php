<?
$lstViolations = file($LogViolationsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// лог файла нарушений разделитель в строках \t
// колоноки: 0 - время нарушения (timestamp)
//          1 - IP адрес
//          2 - основание нарушения
//          3 - URL страницы, на которой зафиксировано нарушение
//          4 - User-Agent клиента

if ($_GET['filter'] && $_GET['value']){
    $strFilterValue = trim($_GET['value']);
    $lstFilteredViolations = [];
    foreach ($lstViolations as $strViolation){
        $arrViolation = explode("\t", $strViolation);
        switch ($_GET['filter']){
            case 'ip':
                if ($arrViolation[1] == $strFilterValue){
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
    ?><a href="?page=violations"
        class="button"
        >Сбросить фильтр</a><?
}

?>
<div class="log">
    <?foreach ($lstViolations as $strViolation){?>
        <?$arrViolation = explode("\t", $strViolation);?>
        <div class="log-entry">
            <span class="log-time"><?=date("Y-m-d H:i:s", $arrViolation[0])?></span>
            <a class="log-ip" href="?page=violations&filter=ip&value=<?=$arrViolation[1]?>"><?=$arrViolation[1]?></a>
            <a class="log-reason" href="?page=violations&filter=reason&value=<?=$arrViolation[2]?>"><?=$arrViolation[2]?></a>
            <a class="log-url" href="?page=violations&filter=url&value=<?=$arrViolation[3]?>"><?=$arrViolation[3]?></a>
            <a class="log-agent" href="?page=violations&filter=agent&value=<?=$arrViolation[4]?>">Agent: <?=$arrViolation[4]?></a>
        </div>
    <?}?>
</div>