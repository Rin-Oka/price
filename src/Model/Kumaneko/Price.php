<?php

namespace App\Model\Kumaneko;

/**
 * Class Price
 * @package App\Model\Kumaneko
 */
class Price
{
    /**
     *
     * @param array $productInfo 商品情報の配列を受け取る
     * @return array
     */
    public static function main(array $productInfo)
    {
        // 読み込んだ商品情報をそのまま表示するようにしてあります。
        // 実際に必要な処理に書き換えて下さい。
        return $productInfo;
    }
}
