<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Error;

use Cake\Console\ConsoleOutput;
use Cake\Core\Configure;
use Cake\Error\BaseErrorHandler;
use Cake\Error\Debugger;
use Cake\Error\FatalErrorException;
use Cake\Log\Log;
use Cake\Routing\Router;
use Exception;

/**
 * Error Handler for Cake console. Does simple printing of the
 * exception that occurred and the stack trace of the error.
 */
class AppConsoleError extends BaseErrorHandler
{

    /**
     * Standard error stream.
     *
     * @var ConsoleOutput
     */
    protected $_stderr;

    /**
     * Options for this instance.
     *
     * @var array
     */
    protected $_options;

    /**
     * Constructor
     *
     * @param array $options Options for the error handler.
     */
    public function __construct($options = [])
    {
        if (empty($options['stderr'])) {
            $options['stderr'] = new ConsoleOutput('php://stderr');
        }
        $this->_stderr = $options['stderr'];
        $this->_options = $options;
    }

    /**
     * Handle errors in the console environment. Writes errors to stderr,
     * and logs messages if Configure::read('debug') is false.
     *
     * @param \Exception $exception Exception instance.
     * @return void
     * @throws \Exception When renderer class not found
     * @see http://php.net/manual/en/function.set-exception-handler.php
     */
    public function handleException(Exception $exception)
    {
        $this->_displayException($exception);
        $this->_logException($exception);
        $code = $exception->getCode();
        $code = ($code && is_int($code)) ? $code : 1;
        $this->_stop($code);
    }

    /**
     * Prints an exception to stderr.
     *
     * @param \Exception $exception The exception to handle
     * @return void
     */
    protected function _displayException($exception)
    {
        $errorName = 'Exception:';
        if ($exception instanceof FatalErrorException) {
            $errorName = 'Fatal Error:';
        }
        $message = sprintf(
            "<error>%s</error> %s in [%s, line %s]",
            $errorName,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
        $this->_stderr->write($message);
    }

    /**
     * Prints an error to stderr.
     *
     * Template method of BaseErrorHandler.
     *
     * @param array $error An array of error data.
     * @param bool $debug Whether or not the app is in debug mode.
     * @return void
     */
    protected function _displayError($error, $debug)
    {
        $message = sprintf(
            '%s in [%s, line %s]',
            $error['description'],
            $error['file'],
            $error['line']
        );
        $message = sprintf(
            "<error>%s Error:</error> %s\n",
            $error['error'],
            $message
        );
        $this->_stderr->write($message);
    }

    /**
     * Stop the execution and set the exit code for the process.
     *
     * @param int $code The exit code.
     * @return void
     */
    protected function _stop($code)
    {
        exit($code);
    }

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
            $context['trace'] = $trace;
        }
        $message .= "\n\n";
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
