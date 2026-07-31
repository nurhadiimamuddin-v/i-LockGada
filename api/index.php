<?php
// Router untuk Vercel - Menghindari batas 12 Serverless Functions
// Dengan menjadikan 1 file ini sebagai entrypoint untuk semua file PHP

chdir(__DIR__ . '/../'); // Pindah ke root folder agar require() relatif berjalan normal

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

// Periksa apakah file yang diminta ada dan berekstensi .php
if (file_exists($file) && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require $file;
} else if (file_exists($file) && is_file($file)) {
    // Jika file statik nyasar ke router (seharusnya ditangani Vercel)
    $mime = mime_content_type($file);
    header("Content-Type: $mime");
    readfile($file);
} else {
    http_response_code(404);
    echo "404 Not Found";
}
