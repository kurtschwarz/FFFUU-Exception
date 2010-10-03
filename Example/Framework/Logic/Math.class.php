<?php
namespace Framework\Logic
{
    class DivisionByZero extends \Exception{}
    
    class Math
    {
        public function __construct()
        {
            
        }

        public function doSomeMath()
        {
            $this->someFunction();
        }

        public function someFunction()
        {
            $this->someOtherFunction();
        }
        
        public function someOtherFunction()
        {
            $this->justAnotherFunction();
        }

        public function justAnotherFunction()
        {
            $this->lastFunction();
        }

        public function lastFunction()
        {
            $this->divide(9000, 0);
        }

        public function divide($dividend, $divisor)
        {
            $dividend = (int)$dividend;
            $divisor = (int)$divisor;
            if($divisor === 0) throw new DivisionByZero('I\'m sorry Dave, I\'m afraid I can\'t do that.');
            return $dividend/$divisor;
        }
    }
}
