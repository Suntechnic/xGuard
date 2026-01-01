<?php
include __DIR__ . '/.prolog.php';

$UID = 'files'; // тест установки соединения с базой данных

// загрузим список файлов из файла files.txt
$FilesListPath = __DIR__.'/files.txt';
$lstFiles = file($FilesListPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
// добавим $_SERVER['DOCUMENT_ROOT'], к путям которые неначинаются с /
foreach ($lstFiles as &$FilePath) {
    if (strpos($FilePath, '/') !== 0) {
        $FilePath = $_SERVER['DOCUMENT_ROOT'].'/'.$FilePath;
    }
}
unset($FilePath);



// тестирует скорость операций с файлами
$StartTime = microtime(true);
$TmpDir = __DIR__.'/tmp';
if (!is_dir($TmpDir)) {
    mkdir($TmpDir, 0777, true);
}

$I=0;
$Iterations = $ini[$UID]['iterations'] ?? 40000;
while ($I<$Iterations) {
    foreach ($lstFiles as $FilePath) {
        $I++;

        if (!file_exists($FilePath)) continue;

        // чтение файла
        $Content = file_get_contents($FilePath);

        if ($I%100 == 0) {
            // запись во временный файл

            $TmpFilePath = $TmpDir.'/'.md5($I).'.tmp';
            file_put_contents($TmpFilePath, $Content);
            // удаление временного файла
            unlink($TmpFilePath);
        }
        
        if ($I > $Iterations) break;
    }
}


include __DIR__ . '/.epilog.php';