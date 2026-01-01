<?php
include __DIR__ . '/.prolog.php';

$UID = 'dbc'; // тест установки соединения с базой данных

$LoginDB = $ini['connection']['login'];
$PasswordDB = $ini['connection']['password'];
$HostDB = $ini['connection']['host'];
$NameDB = $ini['connection']['name'];


$I=0;
$Iterations = $ini[$UID]['iterations'] ?? 2000;
while ($I<$Iterations) {
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




include __DIR__ . '/.epilog.php';