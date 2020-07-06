<?php
/**
 * Created by PhpStorm.
 * User: yukirin
 * Date: 2015/11/02
 * Time: 11:39
 */
define('AMAZON_OFFERS_LIST_URL', 'http://www.amazon.co.jp/gp/offer-listing/');
define('TAX', 0.1);
// Amazon新品と比較する場合の割引率
define('BASE_DISCOUNT', 0.03);
define('OTHER_COST', 87);
// FBAの場合の追加コスト
define('FBA_ADDITIONAL_COST', 141);

define('MWS_CONFIG_PATH', PROJECT_ROOT . 'config' . DS . 'mws' . DS . 'mws_config.php');

define('TSV_DIR', PROJECT_ROOT . 'tsv' . DS);

// ロックファイルの場所
define('LOCK_FILE_DIR', PROJECT_ROOT . 'lock' . DS );

// 改定セッションの保存期限(日)
define('SESSION_EXPIRE_DAYS', 5);

// 価格と数量ファイルの保存期限(時間)
define('FILE_EXPIRE_HOURS', 24);

// アップロードのリトライ回数上限
define('UPLOAD_MAX_RETRY', 3);

define('MAX_PRICE', 99999);

define('AUTO_REVISE_TIME_FILE', PROJECT_ROOT . 'tmp/auto_revise.txt');


define('OLD_TOOL_URL', 'http://kumaneko.me:2080/test/revise/search_queues.php');

return [
    'SellerNames' => [
        'A2EON6VM8N56Z2' => '熊猫堂',
        'A11RCYDUIV69S0' => 'クローバー',
        'A13IY8XV1MNEAZ' => '竜泉BOOKS',
        'A2C5EZY4V06N0A' => 'スピカ書房',
        'AUDII0TR5YEWR' => 'むげん堂',
        'A3OX9J1LG570FA' => '浅草レコード',
    ],
    'LockFiles' => [
        'test' => LOCK_FILE_DIR . 'test',
        'stockImport' => LOCK_FILE_DIR . 'stock_import',
        'deleteOldItems' => LOCK_FILE_DIR . 'delete_old_items',
        'deleteOldTsv' => LOCK_FILE_DIR . 'delete_old_tsv',
        'upload' => LOCK_FILE_DIR . 'upload',
        'deletePrices' => LOCK_FILE_DIR . 'delete_prices',
    ],
    'ApiKeys' => [
        'apps' => 'IgmntE72j3VmT1kLy6i54',
    ],
    'MinimumPrices' => [
        'Book' => ['default' => 50, 'fba' => 300],
        'Music' => ['default' => 50, 'fba' => 300],
        'others' => ['default' => 1, 'fba' => 300],
    ],
    'MwsProductsSettings' => [
        'A2EON6VM8N56Z2' => [
            'merchantId' => 'A2EON6VM8N56Z2',
            'marketplaceId' => 'A1VC38T7YXB528',
            'keyId' => 'AKIAIGCK2BBPNAWNJ6PQ',
            'secretKey' => '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',
        ],
        'A11RCYDUIV69S0' => [
            'merchantId' => 'A11RCYDUIV69S0',
            'marketplaceId' => 'A1VC38T7YXB528',
            'keyId' => 'AKIAIEJHLGT5WWBD44GA',
            'secretKey' => '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',
        ],
        'A13IY8XV1MNEAZ' => [
            'merchantId' => 'A13IY8XV1MNEAZ',
            'marketplaceId' => 'A1VC38T7YXB528',
            'keyId' => 'AKIAITU6KKVKWJS23OCQ',
            'secretKey' => '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',
        ],
        'AUDII0TR5YEWR' => [
            'merchantId' => 'AUDII0TR5YEWR',
            'marketplaceId' => 'A1VC38T7YXB528',
            'keyId' => 'AKIAI6PPAEMLWVIPZUCA',
            'secretKey' => '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',
        ],
        'A2C5EZY4V06N0A' => [
            'merchantId' => 'A2C5EZY4V06N0A',
            'marketplaceId' => 'A1VC38T7YXB528',
            'keyId' => 'AKIAJALZYNFETY5IL3IA',
            'secretKey' => '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',
        ],
        'A3OX9J1LG570FA' => [
            'merchantId' => 'A3OX9J1LG570FA',
            'marketplaceId' => 'A1VC38T7YXB528',
            'keyId' => 'AKIAIU7RIX2O7HVPP7HQ',
            'secretKey' => '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',
        ],

    ],
];
