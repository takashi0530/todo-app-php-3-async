<?php

namespace MyApp;

class Token {

    // トークンを作成する関数
    public static function create() {
        if (!isset($_SESSION['token'])) {
        // 推測されにくい文字列をトークンとして生成
        $_SESSION['token'] = bin2hex(random_bytes(32));
        }
    }

    // トークンをチェックする関数
    public static function validate() {
        // 空もしくはセッションのトークンと送信されたトークンが一致していない場合
        if (empty($_SESSION['token']) || $_SESSION['token'] !== filter_input(INPUT_POST, 'token')) {
            exit('Invalid post request');
        }
    }

}