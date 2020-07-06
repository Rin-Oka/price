<?php
namespace App\Model\Kumaneko\MwsProducts;

class Request
{
    protected $defaultParams = [
        'MarketplaceId' => 'A1VC38T7YXB528',
        'SignatureMethod' => 'HmacSHA256',
        'SignatureVersion' => '2',
        'Version' => '2011-10-01',
    ];
    protected $params;
    protected $serviceUrl = 'https://mws.amazonservices.jp/Products/2011-10-01';
    protected $secretKey;

    public function __construct($params = [])
    {
        $this->params = array_merge($this->defaultParams, $params);
    }

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return null;
        }
    }

    public function setServiceUrl($url)
    {
        $this->serviceUrl = $url;
    }

    public function getServiceUrl()
    {
        return $this->serviceUrl;
    }

    public function setSecretKey($key)
    {
        $this->secretKey = $key;
    }

    public function getFormattedParams()
    {
        $formattedParams = [];
        foreach($this->params as $key => $value) {
            $formattedParams = $this->_formatParam($formattedParams, $key, $value);
        }
        return $formattedParams;
    }

    protected function _formatParam($formattedParams, $key, $value)
    {
        if(is_array($value)) {
            foreach($value as $k => $v) {
                $formattedParams = $this->_formatParam($formattedParams, $key . '.' . $k, $v);
            }
        } else {
            $formattedParams[$key] = $value;
        }
        return $formattedParams;
    }

    public function httpQuery()
    {
        $formattedParams = $this->getFormattedParams();
        $formattedParams = $this->_addTimeStamp($formattedParams);
        $formattedParams = $this->_addSignature($formattedParams);
        return http_build_query($formattedParams);
    }

    protected function _addTimeStamp($formattedParams)
    {
        $formattedParams['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        return $formattedParams;
    }

    protected function _addSignature($formattedParams)
    {
        ksort($formattedParams, SORT_STRING);
        if(empty($formattedParams['AWSAccessKeyId']) || empty($this->secretKey)) {
            throw new \Exception('AWSアクセスキーが設定されていません。');
        }
        $parsedUrl = parse_url($this->serviceUrl);
        $header = "POST\n" . $parsedUrl['host'] . "\n";
        $path = empty($parsedUrl['path']) ? '/' : $parsedUrl['path'];
        $header .= $path . "\n";
        $header .= http_build_query($formattedParams);
        $formattedParams['Signature'] = base64_encode(hash_hmac('sha256', $header, $this->secretKey, true));
        return $formattedParams;
    }
}
