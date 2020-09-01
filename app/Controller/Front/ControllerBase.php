<?php
declare(strict_types=1);

namespace App\Controller\Front;

class ControllerBase extends \App\Controller\ControllerBase
{
    // テンプレート用インスタンスの取得とrenderの発行
    protected function render($name, array $context = array())
    {
        // デフォで使う値を追加する
        //$context['hoge'] = 'hoge'; // サンプル

        // テンプレートのディレクトリを追加する
        $name = "front/{$name}";

        //
        return parent::render($name, $context);
    }

}
