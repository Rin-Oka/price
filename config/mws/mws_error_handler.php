<?php
use Psr\Log\LogLevel;
use Cake\Log\Log;

function mws_error_handler($message, $level)
{
    switch ($level) {
        case LOG_ERR:
            throw new Exception($message);
            break;
    }
}
