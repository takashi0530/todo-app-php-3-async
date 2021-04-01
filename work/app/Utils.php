<?php

namespace MyApp;

class Utils {

    // htmlspecialcharsメソッド
    // インスタンスを作って操作するものでないためクラスから直接呼び出せるクラスメソッドにする public static
    public static function h($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

}