<?php
require_once 'Framework/Framework.php';

try
{
    class StackClass
    {
        public function __construct($a, $b, $c, $d)
        {
            $this->stack($a, $b, $c, time()+time());
        }
        
        private function stack($a, $b, $c, $d)
        {
            $this->trace(':)');
        }

        private function trace($a)
        {
            $this->ho(':(');
        }

        private function ho($d)
        {
            throw new Exception('Something broke!');
        }
    }

    class Foo
    {
        public function __construct($a)
        {}
    }

    function runme($data) {
        new StackClass(time(), mt_rand(0, 9000), mt_rand(0, 9000), $data);
    }

    call_user_func_array('runme', array(new Foo('test')));
}
catch(Exception $e)
{
    if(FRAMEWORK_DEBUG_MODE === true) require_once 'Framework/Exception.php';
    else echo 'Something has gone wrong.';
    exit;
}
