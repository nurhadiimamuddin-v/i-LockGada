<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Pindah ke root folder agar require() relatif berjalan normal
chdir(__DIR__ . '/../'); 

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Jika akses root, arahkan ke index.php
if ($path === '/' || $path === '') {
    $path = '/index.php';
}

// Cegah akses langsung ke folder api dari url
if (strpos($path, '/api/') === 0) {
    http_response_code(403);
    die("Forbidden");
}

$file = __DIR__ . '/..' . $path;

// Periksa apakah file yang diminta ada
if (file_exists($file) && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require $file;
} else if (file_exists($file) && is_file($file)) {
    $mime = mime_content_type($file);
    header("Content-Type: $mime");
    readfile($file);
} else {
    http_response_code(404);
    echo "404 Not Found: " . htmlspecialchars($path);
}
