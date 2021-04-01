<?php

// __DIR__ : 絶対パス表記の現在のファイルが存在するディレクトリ名。これを指定することで読み込みエラーを防ぐため。マジック定数
require_once(__DIR__ . '/../app/config.php');

use MyApp\Database;
use MyApp\Todo;
use MyApp\Utils;

// DatabaseクラスのgetInstanceメソッドにアクセスし、インスタンスを生成する
$pdo = Database::getInstance();

// 上記の$pdoを初期値をコンストラクタに値を渡し、Todoクラスのインスタンスを生成させ、$todoに代入する
$todo = new Todo($pdo);

// postで送信されたデータを処理するメソッドproseccPostを呼び出す
$todo->processPost();

// 【SELECT】全てののtodoを取得するメソッドを呼び出して配列に代入
$todos = $todo->getAll();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>My Todos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main>
        <header>
            <h1>Todos</h1>
            <form action="?action=purge" method="post">
                <span class="purge">purge</span>
                <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
            </form>
        </header>

        <form action="?action=add" method="post">
            <input type="text" name="title" placeholder="Type new todo.">
            <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>

        <ul>
            <?php foreach ($todos as $todo) : ?>
                <li>
                    <form action="?action=toggle" method="post">
                        <input type="checkbox" <?= $todo->is_done ? 'checked' : ''; ?>>
                        <input type="hidden" name="id" value="<?= Utils::h($todo->id); ?>">
                        <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
                    </form>
                    <span class="<?= $todo->is_done ? 'done' : ''; ?>">
                        <?= Utils::h($todo->title); ?>
                    </span>

                    <form action="?action=delete" method="post" class="delete-form">
                        <span class="delete">x</span>
                        <input type="hidden" name="id" value="<?= Utils::h($todo->id); ?>">
                        <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
                    </form>

                </li>
            <?php endforeach; ?>
        </ul>
    </main>

    <script src="js/main.js"></script>
</body>

</html>