<?php
require_once 'Framework/Framework.php';

try
{
    $math = new \Framework\Logic\Math;
    $math->doSomeMath();
}
catch(\Exception $e)
{
    require_once 'Framework/Exception.php';
    exit;
}
