<?php
require_once 'Framework/Framework.php';

try
{
    function test()
    {
        throw new Exception(';(');
    }
    // Logic here, with errors and exceptions!
    test();
}
catch(Exception $e)
{
    if(FRAMEWORK_DEBUG_MODE === true) require_once 'Framework/Exception.php';
    else echo 'Something has gone wrong.';
    exit;
}
