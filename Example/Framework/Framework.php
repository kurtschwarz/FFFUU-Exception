<?php
ob_start();
session_start();

$ini = array('error_reporting' => E_ALL,
             'display_errors' => 1,
             'max_execution_time' => 0,
             'date.timezone' => 'America/Toronto');
array_walk($ini, function($v, $k){ini_set($k, $v);});

function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('exception_error_handler');

function __autoload($c)
{
    if(require_once(implode(explode('\\', $c), '/').'.class.php')) return true;
    return false;
}
