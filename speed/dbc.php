<?php

$UID = 'dbc';

$ini = parse_ini_file(__DIR__ . '/db.ini', true);

$LoginDB = $ini['connection']['login'];
$PasswordDB = $ini['connection']['password'];
$HostDB = $ini['connection']['host'];
$NameDB = $ini['connection']['name'];


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

    if ($result = $mysqli->query('SHOW TABLES;')) {
        
        while ($row = $result->fetch_assoc()) {
            $row2 = $row;
        }
        $result->free();
    } else {
        echo "Ошибка выполнения запроса: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    

    // закрываем соединение
    $mysqli->close(); 
}




$ExecutionTime = microtime(true) - $StartTime;
echo $ExecutionTime;
//echo "Время выполнения: ".$ExecutionTime." секунд \n";
// запишим время в лог
$LogFile = __DIR__.'/logs/'.$UID.'.log.txt';
$LogEntry = date('Y-m-d H:i:s')." ".$ExecutionTime."\n";
file_put_contents($LogFile, $LogEntry, FILE_APPEND);