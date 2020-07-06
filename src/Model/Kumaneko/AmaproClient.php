<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015/12/21
 * Time: 16:10
 */

namespace App\Model\Kumaneko;


use Cake\Core\Configure;

class AmaproClient
{
    protected $config = [
        'url' => 'http://dev.amapro.kumaneko.me/asinsApi/',
        'infoUrl' => 'http://dev.amapro.kumaneko.me/system-info-api/get-info',
    ];
    const EXCEPTION_CODE_EMPTY_ASINS = 101;
    const EXCEPTION_CODE_HTTP_ERROR = 102;
    const MAX_REQUEST_ASINS = 500;
    protected $ch;

    public function __construct($config = [])
    {
        if (Configure::read('AmaproSettings')) {
            $this->config = array_merge($this->config, Configure::read('AmaproSettings'));
        }
        if ($config) {
            $this->config = array_merge($this->config, $config);
        }
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_URL, $this->config['url']);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * AmaproにASINリストを送信し、競合商品情報を取得する。
     * @param array $arrayAsin
     * @return mixed
     * @throws \Exception
     */
    public function exec(array $arrayAsin)
    {
        if (empty($arrayAsin)) {
            throw new \Exception('ASINリストが空です。', self::EXCEPTION_CODE_EMPTY_ASINS);
        }
        if (count($arrayAsin) > self::MAX_REQUEST_ASINS) {
            throw new \Exception('取得できるASIN数は' . self::MAX_REQUEST_ASINS . 'までです。');
        }
        $query = http_build_query([
            'asins' => json_encode($arrayAsin),
            'asins_count' => count($arrayAsin),
        ]);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($this->ch);
        $result = json_decode($response, true);
        if (curl_getinfo($this->ch, CURLINFO_HTTP_CODE) != '200') {
            throw new \Exception('ASIN情報取得に失敗しました。 HTTP-STATUS-CODE: ' . curl_getinfo($this->ch, CURLINFO_HTTP_CODE), self::EXCEPTION_CODE_HTTP_ERROR);
        }
        if (empty($result) || empty($result['results'])) {
            throw new \Exception('ASIN情報が正しく取得できませんでした。', self::EXCEPTION_CODE_HTTP_ERROR);
        }
        return $result['results'];
    }

    public function getInfo()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $this->config['infoUrl']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        return $result['result'];
    }
}