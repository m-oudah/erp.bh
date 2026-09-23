<?php
$dirs = [
    'c:/laragon/www/erp.bh/resources/views/settings/',
    'c:/laragon/www/erp.bh/resources/views/settings/archive_types/',
    'c:/laragon/www/erp.bh/resources/views/settings/document_categories/',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $files = glob($dir . '*.blade.php');
    foreach ($files as $f) {
        $content = file_get_contents($f);
        $content = mb_convert_encoding($content, 'Windows-1252', 'UTF-8');
        file_put_contents($f, $content);
    }
}
echo "Done\n";
