<?php
include __DIR__ . '/.prolog.php';

$UID = 'dbq'; // тест установки соединения с базой данных

$LoginDB = $ini['connection']['login'];
$PasswordDB = $ini['connection']['password'];
$HostDB = $ini['connection']['host'];
$NameDB = $ini['connection']['name'];

$lstSQL = [];
// загруажем SQL запросы из файла
$SQLFile = __DIR__ . '/dbq.sql';
if (file_exists($SQLFile)) {
    $SQLContent = file_get_contents($SQLFile);
    // разбиваем на отдельные запросы по символу ;
    $lstSQL = array_filter(array_map('trim', explode(';', $SQLContent)));
} else {
    echo "Файл с SQL запросами не найден: " . $SQLFile;
    exit();
}

// тестирует скорость операций с файлами
$StartTime = microtime(true);

// устанавливаем соединение с базой данных
$mysqli = new mysqli($HostDB, $LoginDB, $PasswordDB, $NameDB);
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}
$I=0;
$Iterations = $ini[$UID]['iterations'] ?? 2;
while ($I<$Iterations) {
    $I++;
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
}

// закрываем соединение
$mysqli->close(); 

include __DIR__ . '/.epilog.php';
