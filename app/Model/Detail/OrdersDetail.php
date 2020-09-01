<?php
declare(strict_types=1);

namespace App\Model\Detail;

/*
 * XXX このファイルは自動生成なので書き込みをしないでください
 */

// orders
// 1レコードが「1人の1回の注文」を表す
trait OrdersDetail {
    // pk
    protected $pk = 'order_id';
    // テーブル名
    protected $table = 'orders';

    // カラム一覧
    protected $colmuns = [
        'order_id' => '注文ID',	// 注文ID	bigint(20) unsigned
        'orderer_name' => '注文者名',	// 注文者名	varchar(128)
        'orderer_email' => '注文者email',	// 注文者email	varbinary(254)
        'orderer_tel' => '注文者電話番号',	// 注文者電話番号	varbinary(16)
        'orderer_zip' => '注文者郵便番号: フォーマットは ddd-dddd',	// 注文者郵便番号: フォーマットは ddd-dddd	varbinary(8)
        'orderer_address' => '注文者住所',	// 注文者住所	varchar(256)
        'order_notices' => '特記事項',	// 特記事項	text
        'order_price_total' => '合計金額(二次情報): 一次は「商品明細」の合計',	// 合計金額(二次情報): 一次は「商品明細」の合計	int(10) unsigned
        'payment_at' => '入金確認日時',	// 入金確認日時	datetime
        'shipment_at' => '発送処理日時',	// 発送処理日時	datetime
        'created_at' => '作成日時/注文日時',	// 作成日時/注文日時	datetime
        'updated_at' => '更新日時',	// 更新日時	datetime%
    ];

    // カラム型一覧
    protected $colmuns_type = [
        'order_id' => 'bigint(20) unsigned',	// 注文ID	bigint(20) unsigned
        'orderer_name' => 'varchar(128)',	// 注文者名	varchar(128)
        'orderer_email' => 'varbinary(254)',	// 注文者email	varbinary(254)
        'orderer_tel' => 'varbinary(16)',	// 注文者電話番号	varbinary(16)
        'orderer_zip' => 'varbinary(8)',	// 注文者郵便番号: フォーマットは ddd-dddd	varbinary(8)
        'orderer_address' => 'varchar(256)',	// 注文者住所	varchar(256)
        'order_notices' => 'text',	// 特記事項	text
        'order_price_total' => 'int(10) unsigned',	// 合計金額(二次情報): 一次は「商品明細」の合計	int(10) unsigned
        'payment_at' => 'datetime',	// 入金確認日時	datetime
        'shipment_at' => 'datetime',	// 発送処理日時	datetime
        'created_at' => 'datetime',	// 作成日時/注文日時	datetime
        'updated_at' => 'datetime',	// 更新日時	datetime%
    ];

    /**
     * コメント付きで全カラム取得
     *
     * @param $delimiter string コメントを区切る区切り文字。空文字なら「コメントは区切らず全部返す」
     * @return array [カラム名 => コメント, ...]の配列
     */
    public static function getAllColmunsWithComment(string $delimiter = '') : array
    {
        // 区切りがいらないんなら速やかに返却
        if ('' === $delimiter) {
            return (new static())->colmuns;
        }
        // else
        // 区切りがいるんなら処理して返す
        $ret = [];
        foreach((new static())->colmuns as $k => $v) {
            $ret[$k] = explode($delimiter, $v)[0];
        }
        return $ret;
    }

    /**
     * 全カラム取得
     *
     * @return array [カラム名ト, ...]の配列
     */
    public static function getAllColmuns() : array
    {
        return array_keys(static::getAllColmunsWithComment());
    }

    /**
     * PKを除く、コメント付きで全カラム取得
     *
     * @param $delimiter string コメントを区切る区切り文字。空文字なら「コメントは区切らず全部返す」
     * @return array [カラム名 => コメント, ...]の配列
     */
    public static function getAllColmunsWithCommentWithoutPk(string $delimiter = '') : array
    {
        // まず全一覧を取得
        $ret = static::getAllColmunsWithComment($delimiter);

        // pk把握
        $pks = static::getPkName();
        if (is_string($pks)) {
            $pks = [$pks];
        }

        // pkを削除
        foreach($pks as $pk) {
            unset($ret[$pk]);
        }

        //
        return $ret;
    }

    /**
     * PKを除く、全カラム取得
     *
     * @return array [カラム名ト, ...]の配列
     */
    public static function getAllColmunsWithoutPk()
    {
        return array_keys(static::getAllColmunsWithCommentWithoutPk());
    }

    /**
     * 日付系の型か確認
     *
     * XXX 一端 "DATE", "DATETIME", "TIMESTAMP" を「日付系の型」とする
     *
     * @param $name string カラム名
     * @return boolean 日付系の型ならtrue、そうでなければfalse
     */
    public static function isColumnTypeDate(string $name)
    {
        // 先に確認
        $list = (new static())->colmuns_type;
        if (false === isset($list[$name])) {
            throw new \ErrorException('存在しないカラム名が指定されました');

        }
        // 型の把握
        $type = strtolower($list[$name]);

        // 判定
        // DATE または DATETIME
        if ( ('date' === $type) || ('datetime' === $type) ) {
            return true;
        }
        // "TIMESTAMP"は、RDBによっては「 without time zone」とか「with time zone」とか付くようなので少し配慮
        // あと、このロジックだと timestamptz(PostgreSQL独自拡張)も一応拾える想定
        if (0 === strncmp('timestamp', $type, 9)) {
            return true;
        }

        // 上述以外ならfalse
        return false;
    }


}