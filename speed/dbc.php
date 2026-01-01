<?php

$LoginDB = 'useriprofishop';
$PasswordDB = '@N}dwiV+4}@+OC1';
$HostDB = 'localhost';
$NameDB = 'dbiprofishop';

$lstSQL = [
"SELECT
   `iblock_section`.`IBLOCK_SECTION_ID` AS `IBLOCK_SECTION_ID`,
   `iblock_section`.`ID` AS `UALIAS_0`
FROM
   `b_iblock_section` `iblock_section`
WHERE
   `iblock_section`.`ID` = 48939",
"SELECT
   `iblock_section`.`IBLOCK_SECTION_ID` AS `IBLOCK_SECTION_ID`,
   `iblock_section`.`ID` AS `UALIAS_0`
FROM
   `b_iblock_section` `iblock_section`
WHERE
   `iblock_section`.`ID` = 3698",
   "SELECT
   `iblock_iblock`.`ID` AS `ID`,
   `iblock_iblock`.`API_CODE` AS `API_CODE`
FROM
   `b_iblock` `iblock_iblock`
WHERE
   `iblock_iblock`.`ID` = 5"
];

// тестирует скорость операций с файлами
$StartTime = microtime(true);

$I=0;

while ($I<2000) {
    $I++;

    // устанавливаем соединение с базой данных
    $mysqli = new mysqli($HostDB, $LoginDB, $PasswordDB, $NameDB);
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        exit();
    }

    foreach ($lstSQL as $SQL) {

        if ($result = $mysqli->query($SQL)) {
            
            while ($row = $result->fetch_assoc()) {
                $row2 = $row;
            }
            $result->free();
        } else {
            echo "Ошибка выполнения запроса: (" . $mysqli->errno . ") " . $mysqli->error;
        }

    }

    // закрываем соединение
    $mysqli->close(); 
}




$ExecutionTime = microtime(true) - $StartTime;

//echo "Время выполнения: ".$ExecutionTime." секунд \n";
// запишим время в лог
$LogFile = __DIR__.'/logs/dbc.log.txt';
$LogEntry = date('Y-m-d H:i:s')." ".$ExecutionTime."\n";
file_put_contents($LogFile, $LogEntry, FILE_APPEND);