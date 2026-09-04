<?php

$base_dir = __DIR__ . '/../resources/views/admin';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir));
$index_files = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getBasename() === 'index.blade.php') {
        $index_files[] = $file->getPathname();
    }
}

$thead_pattern = '<thead class="text-xs text-gray-700 bg-gray-100 border-b border-gray-200">';
$thead_replace = '<thead class="text-xs text-white bg-blue-600 border-b border-blue-700">';

$tr_pattern = '<tr class="bg-white border-b hover:bg-gray-50 {{ $loop->even ? \'bg-gray-50\' : \'\' }}">';
$tr_replace = '<tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? \'bg-indigo-50\' : \'\' }} transition-colors">';

foreach ($index_files as $file_path) {
    if (strpos($file_path, 'audit_logs') !== false) {
        continue;
    }
    
    $content = file_get_contents($file_path);
    $modified = false;
    
    if (strpos($content, $thead_pattern) !== false) {
        $content = str_replace($thead_pattern, $thead_replace, $content);
        $modified = true;
    }
    
    if (strpos($content, $tr_pattern) !== false) {
        $content = str_replace($tr_pattern, $tr_replace, $content);
        $modified = true;
    }
    
    if ($modified) {
        file_put_contents($file_path, $content);
        echo "Updated " . $file_path . "\n";
    }
}
