<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class ControllerBase extends \App\Controller\ControllerBase
{
    // テンプレート用インスタンスの取得とrenderの発行
    protected function render($name, array $context = array())
    {
        // デフォで使う値を追加する
        //$context['hoge'] = 'hoge'; // サンプル

        // テンプレートのディレクトリを追加する
        $name = "admin/{$name}";

        //
        return parent::render($name, $context);
    }

    // 1出力限りのflashなセッションの取得
    protected function getFlashSessions() : array
    {
        $ret = $_SESSION['admin']['flash'] ?? [];
        unset($_SESSION['admin']['flash']);
        return $ret;
    }

    // 1出力限りのflashなセッションの設定
    protected function setFlashSessions($key, $value)
    {
        $_SESSION['admin']['flash'][$key] = $value;
    }


}
