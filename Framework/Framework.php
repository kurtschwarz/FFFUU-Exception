<?php
ob_start();
session_start();

define('FRAMEWORK_DEBUG_MODE', true);

class WarningException extends ErrorException{}
class NoticeException extends ErrorException{}

function errorHandle($errno, $errstr, $errfile, $errline)
{
    if($errno === E_USER_ERROR ||
       $errno === E_RECOVERABLE_ERROR)
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    else
    {
        // Handle the non-fatal error.
    }
}

set_error_handler('errorHandle');