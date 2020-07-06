<?php
/**
 * Created by PhpStorm.
 * User: yukirin
 * Date: 2015/10/13
 * Time: 12:31
 */

namespace App\Log\Engine;

use App\Model\Kumaneko\SendToSlack;
use Cake\Error\Debugger;
use Cake\Log\Engine\BaseLog;

class SlackLog extends BaseLog
{
    protected $sender;
    protected $errorMonitorUrl;

    /**
     * construct method
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->sender = new SendToSlack();
    }

    /**
     * log method
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if (! in_array($level, $this->_config['levels'])) {
            return;
        }
        if ($message instanceof \Exception) {
            $context['trace'] = $message->getTraceAsString();
        }
        $trace = isset($context['trace']) ? $context['trace'] : '';
        if (! $trace) {
            $trace = Debugger::trace();
        }

        if ($message instanceof \Exception) {
            $message = $message->getMessage();
        } else {
            $message = $this->_format($message, $context);
        }
        $requestCode = isset($context['reportRequestCode']) ? $context['reportRequestCode'] : '';
        $message = $this->_buildText($level, $message, $trace, $requestCode);
        $this->sender->send($message);
        return;
    }

    /**
     * @param string $level
     * @param string $message
     * @param string $trace
     * @param string $requestCode
     * @return string
     */
    protected function _buildText($level, $message, $trace, $requestCode)
    {
        $monitorUrl = ROOT_URL . 'logs/';
        $text = <<<EOD
改定ちゃんエラー発生 {$monitorUrl}
エラーレベル: {$level}
リクエストID: {$requestCode}
メッセージ:
{$message}

トレース:
{$trace}

EOD;

        return $text;
    }
}