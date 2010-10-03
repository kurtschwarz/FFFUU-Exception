<?php
ob_clean(); // Erase all output.

class FFFUUUException
{
    private $srcLines = 3; // Show 3 lines before and 3 after.
    
    private $exception = false;
    private $stack = false;
    
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function getTraceAsFancyHTML()
    {
        $this->stack = $this->exception->getTrace();
        return $this->buildTrace('', 0);
    }
    
    public function getTraceAsText()
    {
        $text = get_class($this->exception).': '.$this->exception->getMessage().'<br />'."\r\n";
        $stack = $this->exception->getTrace();
        
        $a = array_keys($stack);
        $b = sizeOf($a);
        for($c=0;$c<$b;++$c)
        {
            $func = '';
            $trace = &$stack[$a[$c]];
            
            if(!empty($line['class'])) $func .= $line['class'].$line['type'];
            $func .= $line['function'];
            $text .= '&nbsp;&nbsp;at '.$func.' in '.$line['file'].' on '.$line['line'].'<br />'."\r\n";
        }
        
        $text .= '<br />'."\r\n".str_replace("\n", '<br />'."\r\n", $this->exception->getTraceAsString());
        return $text;
    }

    private function buildTrace($html, $level = 0)
    {
        if(!empty($this->stack))
        {
            $html = $html.'<li><div class="trace';
            if($level == 0) $html .= ' first';
            $html .= '"><span class="title">#'.$level.'. In <span class="b">'.$this->stack[0]['file'].'</span> around line <span class="b">'.$this->stack[0]['line'].'</span>.</span><ul class="codeBlock">'.$this->getFileSource($this->stack[0]['file'], $this->stack[0]['line']).'</ul></div><ul class="sub">';
            ++$level;
            array_shift($this->stack);
            $this->buildTrace($html, $level);
        }
        else
        {
            $html = $html;
            for($level;$level>0;--$level) $html .= '</ul></li>';
        }

        return $html;
    }
    
    private function getFileSource($file, $offset)
    {
        $fp = fopen($file, 'r');
        $html = '';
        
        do
        {
           $buffer = fgets($fp, 4096);
           $lines[] = $buffer;
        }
        while(!feof($fp));
        fclose($fp);
        array_unshift($lines, '');

        $start = $offset-$this->srcLines;
        if($start < 0) $start = 0;

        $lines = array_slice($lines, $start, ($this->srcLines*2)+1, true);
        $a = array_keys($lines);
        $b = sizeOf($a);
        for($c=0;$c<$b;++$c)
        {
            $line = &$lines[$a[$c]];
            $line = preg_replace('/^\s/s', '&nbsp;', preg_replace('/\s\s/s', '&nbsp;&nbsp;', htmlspecialchars($line)));
            $html .= '<li class="line';
            if($a[$c] == $offset) $html .= ' hl';
            $html .= '"><div class="num">'.$a[$c].'.</div><div class="code"><div class="border">'.$line.'</div></div></li>';
        }
        
        return $html;
    }
}

$FFFUUUException = new FFFUUUException($e);
?>
<!--
     FAIL WHALE!

W     W      W
W        W  W     W
              '.  W
  .-""-._     \ \.--|
 /       "-..__) .-'
|     _         /
\'-.__,   .__.,'
 `'----'._\--'
VVVVVVVVVVVVVVVVVVVVV

Code & Design by Kurt Schwarz <kurt.schwarz@gmail.com>
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Well, Shit. Something went wrong.</title>
        <meta http-equiv="content-language" content="en" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link href="assets/css/exception/global.css" rel="stylesheet" type="text/css" charset="utf-8" />
    </head>
    
    <body>
        <div class="wrap">
            <h1>Well, Shit. <span class="i">Something</span> went wrong.</h1><br />

            <h2>What happened?</h2>
            <p>A <span class="b"><?php echo get_class($e); ?></span> exception was thrown with the message "<span class="b"><?php echo $e->getMessage(); ?></span>".</p><br />
            
            <h2>Where did this happen?</h2>
            <p>In <span class="b"><?php echo $e->getFile(); ?></span> around line <span class="b"><?php echo $e->getLine(); ?></span>.</p><br />
            
            <h2>Stack Trace or GTFO.</h2>
            <p>Here is a formatted trace.</p>
            <ul class="stack">
                <?php echo $FFFUUUException->getTraceAsFancyHTML(); ?>
            </ul><br />

            <p>Here is a plain text trace.</p>
            <p class="plain"><?php echo $FFFUUUException->getTraceAsText(); ?></p>
        </div>
        
        <div class="ffffuuu"></div>
    </body>
</html>
