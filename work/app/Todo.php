<?php

namespace MyApp;

class Todo {

    // プロパティとして保持し他のメソッドで使いたいため宣言
    private $pdo;

    public function __construct($pdo) {
        // 引数として渡ってきた$pdoをプロパティに代入
        $this->pdo = $pdo;

        // 画面を表示した時点でトークンが作成されセッションに保存される。クラスメソッドの呼び出し。（トークンの作成と検証はpost処理時に必要であるためここで記述）
        Token::create();
    }

    // todoがpostされたときの処理
    public function processPost() {
        // todoが入力されPOSTされたら以下が発動
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Tokenクラスのvalidateメソッドを呼び出す。tokenが一致していなければ処理を強制終了させる
            Token::validate();

            $action = filter_input(INPUT_GET, 'action');
            pr($action);
            switch ($action) {
                case 'add':
                    $this->add();
                    break;
                case 'toggle':
                    $this->toggle();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'purge':
                    $this->purge();
                    break;
                default;
                    exit;
            }
            // todo登録後に更新すると再度todoが追加されるのを防ぐためのリダイレクト
            header('Location: ' . SITE_URL);
            exit;
        }
    }

    // 【INSERT】todoを追加する (クラス内からしか呼び出さないためprivateとする)
    private function add() {
        // postされたデータ$_POST['title']が存在しなくても$titleが未定義エラーとならない（$titleが初期化される)
        $title = trim(filter_input(INPUT_POST, 'title'));
        // もしフィルターの結果、$titleが空だったら（todoが空だった場合）
        if ($title === '') {
            echo 'todoを入力してください';
            return;
        }
        // 自分自身のプロパティから$pdoを使用する
        $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (:title)");
        $stmt->bindValue('title', $title, \PDO::PARAM_STR);
        // クエリを実行する
        $stmt->execute();
    }

    // 【UPDATE】todoを更新する (クラス内からしか呼び出さないためprivateとする)
    private function toggle() {
        $id = filter_input(INPUT_POST, 'id');
        if (empty($id)) {
            return;
        }
        // preateメソッドを使用しSQLの実行前準備をする。変数値をプレースホルダとして設定する  :id   is_done = NOT is_done とすることでtrueとfalseが入れ替わる
        $stmt = $this->pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = :id");
        // プレースホルダに値をバインドする bindValue(プレースホルダ名, バインドする値, 値のデータ型)
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        // execute()メソッド：値をバインドした結果のSQLを実行する
        $stmt->execute();
    }

    // 【DELETE】todoを削除する  (クラス内からしか呼び出さないためprivateとする)
    private function delete() {
        $id = filter_input(INPUT_POST, 'id');
        if (empty($id)) {
            return;
        }
        // preateメソッドを使用しSQLの実行前準備をする。変数値をプレースホルダとして設定する  :id   is_done = NOT is_done とすることでtrueとfalseが入れ替わる
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = :id");
        // プレースホルダに値をバインドする bindValue(プレースホルダ名, バインドする値, 値のデータ型)
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        // execute()メソッド：値をバインドした結果のSQLを実行する
        $stmt->execute();
    }

    // 【DELETE all】todoを全て削除する
    private function purge() {
        $this->pdo->query("DELETE FROM todos WHERE is_done = 1");
    }

    // 【SELECT】全てののtodoを取得する
    public function getAll() {
        // クエリを発行
        $stmt = $this->pdo->query("SELECT * FROM todos ORDER BY id DESC");
        // SQLの結果を取得 fetchAll()すべてのレコードを取得する。  fetch() 対象の１件のレコードを取得する。
        $todos = $stmt->fetchAll();
        return $todos;
    }
}