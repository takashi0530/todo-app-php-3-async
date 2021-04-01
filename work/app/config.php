<?php

session_start();

define('DSN', 'mysql:host=db;dbname=myapp;charset=utf8mb4');
define('DB_USER', 'myappuser');
define('DB_PASS', 'myapppass');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);      // $_SERVER['HTTP_HOST']はlocalhost:8562のこと


// 自動ロード  $class = MyApp\Database      MyApp\Todo     MyApp\Token
spl_autoload_register(function($class) {

    // バックスラッシュを記述したい場合は\\と二回書く必要があるバックスラッシュは特種文字のため
    $prefix = 'MyApp\\';

    // $classのなかにprefixがあるかどうか調べて、その位置が0番目（つまり先頭かどうか）かどうか調べる
    if (strpos($class, $prefix) === 0) {
        // MyApp\Database
        // $fileName = sprintf(__DIR__ . '/%s.php', substr($class, 6));
        $fileName = sprintf(__DIR__ . '/%s.php', substr($class, strlen($prefix)));      // $fileName = /work/app/Database.php       /work/app/Todo.php      /work/app/Token.php


        // pr($fileName);
        if (file_exists($fileName)) {
            require_once($fileName);
        } else {
            echo '読み込むファイルが見つかりません。' . $fileName;
            exit;
        }
    }
});




//PHP var_dump2() を見やすく整形する
function vd($var) {
    echo '<pre style="white-space:pre; font-family: monospace; font-size:12px; border:3px double #BED8E0;" margin:8px;&gt;<code>';
    var_dump($var);
    echo '</code></pre>';
    echo '<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/default.min.css"/>';
    echo '<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/highlight.min.js"></script>';
    echo '<script>hljs.initHighlightingOnLoad();</script>';
}

//PHP print_r()を見やすく整形する
function pr($var) {
    echo '<pre style="white-space:pre; font-family: monospace; font-size:12px; border:3px double #BED8E0;" margin:8px;&gt;<code>';
    print_r($var);
    echo '</code></pre>';
    echo '<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/default.min.css"/>';
    echo '<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/highlight.min.js"></script>';
    echo '<script>hljs.initHighlightingOnLoad();</script>';
}