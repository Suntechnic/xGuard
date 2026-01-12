<?php
require_once __DIR__.'/../.defined.php';

// Здесь у нас уже есть секеретный ключ в $Key
// проверяем что запрос имеет ключ в заголовке или в GET параметре (не использовать в продакшене)
if (
        (isset($_GET['key']) && $_GET['key'] == $Key)
        || (isset($_SERVER['HTTP_X_API_KEY']) && $_SERVER['HTTP_X_API_KEY'] == $Key)
    ) {
    // все ок
} else { 
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ($_GET['action'] === 'iplist') {
    // отдаем список заблокированных IP в виде простого текста
    header('Content-Type: text/plain; charset=utf-8');  
    echo file_get_contents($BlockFile);
    exit;
}


header('HTTP/1.1 400 Bad Request');
exit;