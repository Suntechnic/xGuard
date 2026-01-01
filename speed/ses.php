<?php
include __DIR__ . '/.prolog.php';

$UID = 'ses'; // тест установки соединения с базой данных


$I=0;
$Iterations = $ini[$UID]['iterations'] ?? 2;

while ($I<$Iterations) {
    $I++;

    // откроем php сессию
    session_start();

    if ($I%10 == 0) {
        // сгенерируем новый идентификатор сессии
        session_regenerate_id(true);
    }
    
    $_SESSION['xGuard_test'] = 'xGuard_test_value-'.rand(1000,9999);
    session_write_close(); 
}

include __DIR__ . '/.epilog.php';