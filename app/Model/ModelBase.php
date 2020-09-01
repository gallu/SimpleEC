<?php
namespace App\Model;

class ModelBase extends \SlimLittleTools\Model\ModelBase
{
    // XXX やるなら、Filter と Validator のクラスを「拡張先」にしておく
    //protected static $validate_class = '\App\Libs\Validator';
    //protected static $filter_class = '\App\Libs\Filter';

    // 日付が空文字ならNULLにする
    protected static function isDateEmptyStringToNull()
    {
        return true;
    }    

}
