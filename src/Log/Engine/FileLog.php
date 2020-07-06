<?php
namespace App\Log\Engine;

use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Log\Engine\BaseLog;
use Cake\Utility\Text;
use Psr\Log\LogLevel;

/**
 * File Storage stream for Logging. Writes logs to different files
 * based on the level of log it is.
 *
 */
class FileLog extends BaseLog
{

    /**
     * Default config for this class
     *
     * - `levels` string or array, levels the engine is interested in
     * - `scopes` string or array, scopes the engine is interested in
     * - `file` Log file name
     * - `path` The path to save logs on.
     * - `size` Used to implement basic log file rotation. If log file size
     *   reaches specified size the existing file is renamed by appending timestamp
     *   to filename and new log file is created. Can be integer bytes value or
     *   human readable string values like '10MB', '100KB' etc.
     * - `rotate` Log files are rotated specified times before being removed.
     *   If value is 0, old versions are removed rather then rotated.
     * - `mask` A mask is applied when log files are created. Left empty no chmod
     *   is made.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'path' => null,
        'file' => null,
        'types' => null,
        'levels' => [],
        'scopes' => [],
        'rotate' => 10,
        'size' => 10485760, // 10MB
        'mask' => null,
    ];

    /**
     * Path to save log files on.
     *
     * @var string
     */
    protected $_path = null;

    /**
     * The name of the file to save logs into.
     *
     * @var string
     */
    protected $_file = null;

    /**
     * Max file size, used for log file rotation.
     *
     * @var int
     */
    protected $_size = null;

    /**
     * Sets protected properties based on config provided
     *
     * @param array $config Configuration array
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (!empty($this->_config['path'])) {
            $this->_path = $this->_config['path'];
        }
        if ($this->_path !== null &&
            Configure::read('debug') &&
            !is_dir($this->_path)
        ) {
            mkdir($this->_path, 0775, true);
        }

        if (!empty($this->_config['file'])) {
            $this->_file = $this->_config['file'];
            if (substr($this->_file, -4) !== '.log') {
                $this->_file .= '.log';
            }
        }

        if (!empty($this->_config['size'])) {
            if (is_numeric($this->_config['size'])) {
                $this->_size = (int)$this->_config['size'];
            } else {
                $this->_size = Text::parseFileSize($this->_config['size']);
            }
        }
    }

    /**
     * Implements writing to log files.
     *
     * @param string $level The severity level of the message being written.
     *    See Cake\Log\Log::$_levels for list of possible levels.
     * @param string|object $message The message you want to log.
     * @param array $context Additional information about the logged message
     * @return bool success of write.
     */
    public function log($level, $message, array $context = [])
    {
        if (! ($message instanceof \Exception) && (! isset($context['trace']))) {
            $context['trace'] = Debugger::trace();
        }
        $message = $this->_format($message, $context);
        if (isset($context['trace']) && $level != LogLevel::INFO) {
            $message .= "\nTrace: \n{$context['trace']}\n";
        }
        $output = date('Y-m-d H:i:s') . ' ' . ucfirst($level) . ': ' . $message . "\n\n";
        $filename = $this->_getFilename($level);
        if (!empty($this->_size)) {
            $this->_rotateFile($filename);
        }

        $pathname = $this->_path . $filename;
        $mask = $this->_config['mask'];
        if (empty($mask)) {
            return file_put_contents($pathname, $output, FILE_APPEND);
        }

        $exists = file_exists($pathname);
        $result = file_put_contents($pathname, $output, FILE_APPEND);
        static $selfError = false;

        if (!$selfError && !$exists && !chmod($pathname, (int)$mask)) {
            $selfError = true;
            trigger_error(vsprintf(
                'Could not apply permission mask "%s" on log file "%s"',
                [$mask, $pathname]
            ), E_USER_WARNING);
            $selfError = false;
        }

        return $result;
    }

    /**
     * Get filename
     *
     * @param string $level The level of log.
     * @return string File name
     */
    protected function _getFilename($level)
    {
        $debugTypes = ['notice', 'info', 'debug'];

        if (!empty($this->_file)) {
            $filename = $this->_file;
        } elseif ($level === 'error' || $level === 'warning') {
            $filename = 'error.log';
        } elseif (in_array($level, $debugTypes)) {
            $filename = 'debug.log';
        } else {
            $filename = $level . '.log';
        }

        return $filename;
    }

    /**
     * Rotate log file if size specified in config is reached.
     * Also if `rotate` count is reached oldest file is removed.
     *
     * @param string $filename Log file name
     * @return mixed True if rotated successfully or false in case of error.
     *   Void if file doesn't need to be rotated.
     */
    protected function _rotateFile($filename)
    {
        $filepath = $this->_path . $filename;
        clearstatcache(true, $filepath);

        if (!file_exists($filepath) ||
            filesize($filepath) < $this->_size
        ) {
            return;
        }

        $rotate = $this->_config['rotate'];
        if ($rotate === 0) {
            $result = unlink($filepath);
        } else {
            $result = rename($filepath, $filepath . '.' . time());
        }

        $files = glob($filepath . '.*');
        if ($files) {
            $filesToDelete = count($files) - $rotate;
            while ($filesToDelete > 0) {
                unlink(array_shift($files));
                $filesToDelete--;
            }
        }

        return $result;
    }
}
