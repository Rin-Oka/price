<?php
namespace App\Log\Engine;

use Cake\Error\Debugger;
use Cake\Log\Engine\BaseLog;
use Cake\ORM\TableRegistry;
use Exception;

class DatabaseLog extends BaseLog
{
    protected $table;
    protected $_defaultConfig = [
        'types' => null,
        'levels' => [],
        'scopes' => [],
    ];

    /**
     * construct method
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->table = TableRegistry::get('Logs');
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
        if ($message instanceof Exception) {
            $context['trace'] = $message->getTraceAsString();
        }
        $trace = isset($context['trace']) ? $context['trace'] : '';
        if (! $trace) {
            $trace = Debugger::trace();
        }

        if ($message instanceof Exception) {
            $message = $message->getMessage();
        } else {
            $message = $this->_format($message, $context);
        }
        $requestCode = isset($context['reportRequestCode']) ? $context['reportRequestCode'] : null;
        $log = $this->table->newEntity([
                'level' => $level,
                'message' => $message,
                'trace' => $trace,
                'report_amazon_request_code' => $requestCode,
                'is_confirmed' => 0,
            ]
        );
        $this->table->save($log);
        return;
    }
}
