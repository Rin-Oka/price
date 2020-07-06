<?php
namespace App\Error;

use Cake\Core\Configure;
use Cake\Error\BaseErrorHandler;
use Cake\Log\Log;
use Cake\Error\Debugger;
use Cake\Routing\Router;
use Exception;
use Cake\Core\App;

class AppError extends BaseErrorHandler
{
    /**
     * Options to use for the Error handling.
     *
     * @var array
     */
    protected $_options = [];

    /**
     * Constructor
     *
     * @param array $options The options for error handling.
     */
    public function __construct($options = [])
    {
        $defaults = [
            'log' => true,
            'trace' => true,
            'exceptionRenderer' => 'Cake\Error\ExceptionRenderer',
        ];
        $this->_options = $options + $defaults;
    }

    /**
     * Display an error.
     *
     * Template method of BaseErrorHandler.
     *
     * Only when debug > 2 will a formatted error be displayed.
     *
     * @param array $error An array of error data.
     * @param bool $debug Whether or not the app is in debug mode.
     * @return void
     */
    protected function _displayError($error, $debug)
    {
        if (!$debug) {
            return;
        }
        Debugger::getInstance()->outputError($error);
    }

    /**
     * Displays an exception response body.
     *
     * @param \Exception $exception The exception to display
     * @return void
     * @throws \Exception When the chosen exception renderer is invalid.
     */
    protected function _displayException($exception)
    {
        $renderer = App::className($this->_options['exceptionRenderer'], 'Error');
        try {
            if (!$renderer) {
                throw new Exception("$renderer is an invalid class.");
            }
            $error = new $renderer($exception);
            $response = $error->render();
            $this->_clearOutput();
            $this->_sendResponse($response);
        } catch (Exception $e) {
            // Disable trace for internal errors.
            $this->_options['trace'] = false;
            $message = sprintf(
                "[%s] %s\n%s", // Keeping same message format
                get_class($e),
                $e->getMessage(),
                $e->getTraceAsString()
            );
            trigger_error($message, E_USER_ERROR);
        }
    }

    /**
     * Clear output buffers so error pages display properly.
     *
     * Easily stubbed in testing.
     *
     * @return void
     */
    protected function _clearOutput()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

    /**
     * Method that can be easily stubbed in testing.
     *
     * @param string|\Cake\Network\Response $response Either the message or response object.
     * @return void
     */
    protected function _sendResponse($response)
    {
        if (is_string($response)) {
            echo $response;
            return;
        }
        $response->send();
    }

    /**
     * Log an error.
     *
     * @param string $level The level name of the log.
     * @param array $data Array of error data.
     * @return bool
     */
    protected function _logError($level, $data)
    {
        $message = sprintf(
            '%s (%s): %s in [%s, line %s]',
            $data['error'],
            $data['code'],
            $data['description'],
            $data['file'],
            $data['line']
        );
        $context = [];
        if (!empty($this->_options['trace'])) {
            $trace = Debugger::trace([
                'start' => 1,
                'format' => 'log'
            ]);
            $context['trace'] = "{$trace}\n";
        }
        return Log::write($level, $message, $context);
    }

    /**
     * Handles exception logging
     *
     * @param \Exception $exception Exception instance.
     * @return bool
     */
    protected function _logException(Exception $exception)
    {
        $config = $this->_options;
        if (empty($config['log'])) {
            return false;
        }

        if (!empty($config['skipLog'])) {
            foreach ((array)$config['skipLog'] as $class) {
                if ($exception instanceof $class) {
                    return false;
                }
            }
        }
        $context = [];
        if (!empty($config['trace'])) {
            $context =['trace' =>  $exception->getTraceAsString()];
        }
        return Log::error($this->_getMessage($exception), $context);
    }

    /**
     * Generates a formatted error message
     *
     * @param \Exception $exception Exception instance
     * @return string Formatted message
     */
    protected function _getMessage(Exception $exception)
    {
        $message = sprintf(
            "[%s] %s",
            get_class($exception),
            $exception->getMessage()
        );
        $debug = Configure::read('debug');

        if ($debug && method_exists($exception, 'getAttributes')) {
            $attributes = $exception->getAttributes();
            if ($attributes) {
                $message .= "\nException Attributes: " . var_export($exception->getAttributes(), true);
            }
        }
        if (PHP_SAPI !== 'cli') {
            $request = Router::getRequest();
            if ($request) {
                $message .= "\nRequest URL: " . $request->here();
            }
        }
        return $message;
    }
}
