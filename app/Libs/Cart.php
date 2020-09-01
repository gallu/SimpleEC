<?php
declare(strict_types=1);

namespace App\Libs;

use App\Model\Items as ItemsModel;

class Cart 
{
    //
    protected const SESSION_KEY = 'cart';

    // 合計値の計算
    protected static function listSum(array $data)
    {
        //
        $ret = [];
        $tax_wk = []; // 消費税計算用
        $ret['total'] = 0;

        foreach($data as $v) {
            // 合計を計算
            $ret['total'] += $v['subtotal'];

            // 税率計算の参考 https://www.gov-online.go.jp/tokusyu/keigen_zeiritsu/jigyosya/tekikaku.html
            @$tax_wk[ strval($v['model']->getTaxRate()) ] += $v['subtotal'];
        }
//var_dump($tax_wk);

        // 消費税の計算: 端数処理は税率ごとに一回ずつ
        $tax = 0;
        foreach($tax_wk as $tax_rate => $subtotal) {
            $tax += ItemsModel::calTax($subtotal, intval($tax_rate));
//var_dump($tax);
        }

        // 最終的な消費税額を設定
        $ret['tax'] = $tax;
        $ret['total_with_tax'] = $ret['total'] + $ret['tax'];

        //
        return $ret;
    }
    
    //
    public static function list()
    {
        // 各Modelを取得していく
        $data = [];
        foreach($_SESSION[static::SESSION_KEY] as $item_id => $num) {
            // 個数を把握
            $data[$item_id]['num'] = $num;

            // Modelを把握
            $model = ItemsModel::find($item_id);
            $data[$item_id]['model'] = $model;
            $data[$item_id]['data'] = $model->toArray();

            // 1商品の小計を計算
            $data[$item_id]['subtotal'] = $data[$item_id]['num'] * $data[$item_id]['data']['item_price'];

        }
        // 合計値の計算
        $total = static::listSum($data);

        //
        return [$data, $total];
    }

    //
    public static function chage($item_id, $num)
    {
        //
        $num = abs(intval($num));
        if (0 === $num) {
            unset($_SESSION[static::SESSION_KEY][$item_id]);
        } else {
            $_SESSION[static::SESSION_KEY][$item_id] = $num;
        }
    }

    // cartの全クリア
    //
    public static function clear()
    {
        //
        $_SESSION[static::SESSION_KEY] = [];
    }
    
}
