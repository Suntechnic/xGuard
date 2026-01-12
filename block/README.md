# Установка
1 . Скопируйте папку проекта в директорию вашего проекта  
2 . Создайте файл config.ini в директории xGuard/block/ с содержимым как в файле config.ini.example  
3 . Настройте файл config.ini под ваши нужды  
4 . При нарушении создайте массив $xGuardEvent, и подключите файл xGuard/block/main.php  
```php
$xGuardEvent = [
            'IP' => $IP,
            'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
            'URI' => $_SERVER['HTTP_REFERER'],
            'REASON' => '404:'.$_SERVER['REQUEST_URI'],
        ];
require_once $_SERVER['DOCUMENT_ROOT'].'/xGuard/block/main.php';
```
5 . В файле .htaccess в корне проекта добавьте 2 строки ```### xGaurd Block ###``` - между ними будут размещаться заблокированные IP адреса  
6 . Готово! Все уже работат.  Попасть в панель управления можно по адресу /xGuard/block/panel/index.php  

## Дополнительные настройки и агенты
Для ротации логов добавьте в крон файл xGuard/block/agents/logrotator.php, с интервалом исполнения по вашему усмотрению. Пример для ежедневного запуска в полночь:  
```shell
0 0 * * * /usr/bin/php -f /путь_до_проекта/xGuard/block/agents/logrotator.php
```