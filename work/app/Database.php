<?php

namespace MyApp;

class Database {

    // クラス変数 複数のDB接続を防ぐための変数。外部からのアクセスをするわけでないのでprivate
    private static $instance;

    // DBにアクセスしてpdoを返す変数
    public static function getInstance() {
        try {
            // 複数のDB接続がされることを防ぐための条件分岐
            if (!isset(self::$instance)) {
                // PDOクラスをインスタンス化し、クラス変数である$instanceに代入
                self::$instance = new \PDO(
                    DSN,
                    DB_USER,
                    DB_PASS, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    // オブジェクト形式で結果を取得するためのオプション
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    // 取得したデータの型をSQLで定義した型に合わせて取得したいときのオプション
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
                return self::$instance;
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}