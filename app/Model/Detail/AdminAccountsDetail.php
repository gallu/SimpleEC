<?php
declare(strict_types=1);

namespace App\Model\Detail;

/*
 * XXX このファイルは自動生成なので書き込みをしないでください
 */

// admin_accounts
// 1レコードが「1管理者の認証情報」を表す
trait AdminAccountsDetail {
    // pk
    protected $pk = 'admin_account_id';
    // テーブル名
    protected $table = 'admin_accounts';

    // カラム一覧
    protected $colmuns = [
        'admin_account_id' => 'ログイン用のID',	// ログイン用のID	varbinary(128)
        'admin_account_pass' => 'ログイン用のパスワード(hash)',	// ログイン用のパスワード(hash)	varbinary(255)
        'admin_account_name' => '管理アカウント表示名',	// 管理アカウント表示名	varchar(128)
        'error_num' => '連続したエラー回数',	// 連続したエラー回数	int(10) unsigned
        'lock_time' => 'ロック解除時間',	// ロック解除時間	datetime
        'created_at' => '作成日時',	// 作成日時	datetime
        'updated_at' => '更新日時',	// 更新日時	datetime%
    ];

    // カラム型一覧
    protected $colmuns_type = [
        'admin_account_id' => 'varbinary(128)',	// ログイン用のID	varbinary(128)
        'admin_account_pass' => 'varbinary(255)',	// ログイン用のパスワード(hash)	varbinary(255)
        'admin_account_name' => 'varchar(128)',	// 管理アカウント表示名	varchar(128)
        'error_num' => 'int(10) unsigned',	// 連続したエラー回数	int(10) unsigned
        'lock_time' => 'datetime',	// ロック解除時間	datetime
        'created_at' => 'datetime',	// 作成日時	datetime
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