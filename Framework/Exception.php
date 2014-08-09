<?php
ob_clean(); // Erase all output.

class FFFUUUException
{
    private $srcLines = 3; // Show 3 lines before and 3 after.
    
    private $exception = false;
    private $stack = false;
    private $fancyHTML = '';
    private $level = 0;
    private $shortType = array('boolean' => 'bool', 'integer' => 'int', 'string' => 'string', 'double' => 'double', 'array' => 'array', 'object' => 'object', 'resource' => 'resource', 'NULL' => 'null', 'unknown type' => 'unknown');
    
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function getTraceAsFancyHTML()
    {
        $this->stack = $this->exception->getTrace();
        $this->fancyHTML = '';
        $this->level = 0;

        $this->buildTrace();
        return $this->fancyHTML;
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
            
            if(!empty($trace['class'])) $func .= $trace['class'].$trace['type'];
            $func .= $trace['function'];

            $file = isset($trace['file'])?$trace['file']:'?';
            $line = isset($trace['line'])?$trace['line']:'?';
            
            $text .= '&nbsp;&nbsp;at '.$func.' in '.$trace['file'].' on '.$trace['line'].'<br />'."\r\n";
        }
        
        $text .= '<br />'."\r\n".str_replace("\n", '<br />'."\r\n", $this->exception->getTraceAsString());
        return $text;
    }

    private function buildTrace()
    {
        if(!empty($this->stack))
        {
            $file = isset($this->stack[0]['file'])?$this->stack[0]['file']:'?';
            $line = isset($this->stack[0]['line'])?$this->stack[0]['line']:'?';

            $this->fancyHTML .= '<li><div class="trace';
            if($this->level == 0) $this->fancyHTML .= ' first';
            $this->fancyHTML .= '"><span class="title">#'.$this->level.'. In <span class="b">'.$file.'</span> around line <span class="b">'.$line.'</span>.<br /><span class="code">'.$this->makeNiceFuncString().'</span>.</span><ul class="codeBlock">'.$this->getFileSource($file, $line).'</ul></div><ul class="sub">';
            ++$this->level;
            array_shift($this->stack);
            $this->buildTrace();
        }
        else for($this->level;$this->level>0;--$this->level) $this->fancyHTML .= '</ul></li>';
    }
    
    private function getFileSource($file, $offset)
    {
        if(empty($file) || !file_exists($file)) {
            return '<li class="line"><em>No preview avaiable.</em></li>';
        }

        $fp = fopen($file, 'r');
        $html = '';
        $lines[] = '';
        do
        {
           $buffer = fgets($fp, 4096);
           $lines[] = $buffer;
        }
        while(!feof($fp));
        fclose($fp);
        
        $start = $offset-$this->srcLines;
        if($start < 0) $start = 0;

        $lines = array_slice($lines, $start, ($this->srcLines*2)+1, true);
        $a = array_keys($lines);
        $b = sizeOf($a);
        for($c=0;$c<$b;++$c)
        {
            $line = &$lines[$a[$c]];
            //$line = preg_replace('/^\s/s', '&nbsp;', preg_replace('/\s\s/s', '&nbsp;&nbsp;', htmlspecialchars($line)));
            $html .= '<li class="line';
            if($a[$c] == $offset) $html .= ' hl';
            $html .= '"><div class="num">'.$a[$c].'.</div><div class="code"><div class="border">'.$this->convertPHPHighlighting(str_replace('<br />', '', str_replace('?&gt;', '', str_replace('&lt;?php', '', highlight_string('<?php '.$line.' ?>', true))))).'</div></div></li>';
        }
        
        return $html;
    }

    private final function makeNiceFuncString()
    {
        $html = '';
        if(!empty($this->stack[0]['class'])) $html .= $this->stack[0]['class'].$this->stack[0]['type'];
        $html .= $this->stack[0]['function'].'( ';
        
        $a = array_keys($this->stack[0]['args']);
        $b = sizeOf($a);
        for($c=0;$c<$b;++$c)
        {
            $arg = &$this->stack[0]['args'][$a[$c]];
            $type = $this->shortType[gettype($arg)];

            if(is_object($arg)) {
                $arg = get_class($arg);
            }
            
            if(is_object($type)) {
                $type = get_class($type);
            }
            
            $html .= $type.' ';
            if($type == 'string') $html .= '\'<span class="i">'.$arg.'</span>\'';
            else $html .= '<span class="i">'.$arg.'</span>';
            
            if($c < ($b-1)) $html .= ', ';
        }
        
        return $html.' )';
    }

    private final function convertPHPHighlighting($str)
    {
        $defaults = new \stdClass;
        $defaults->string = ini_get('highlight.string');
        $defaults->comment = ini_get('highlight.comment');
        $defaults->keyword = ini_get('highlight.keyword');
        $defaults->default = ini_get('highlight.default');
        $defaults->html = ini_get('highlight.html');
        if(empty($defaults->string)) $defaults->string = '#DD0000';
        if(empty($defaults->comment)) $defaults->comment = '#FF8800';
        if(empty($defaults->keyword)) $defaults->keyword = '#007700';
        if(empty($defaults->default)) $defaults->default = '#0000BB';
        if(empty($defaults->html)) $defaults->html = '#000000';
        
        $str = str_replace(array('style="color: '.$defaults->string.'"', 'style="color: '.$defaults->comment.'"', 'style="color: '.$defaults->keyword.'"', 'style="color: '.$defaults->default.'"', 'style="color: '.$defaults->html.'"'), array('class="php-string"', 'class="php-comment"', 'class="php-keyword"', 'class="php-default"', 'class="php-html"'), $str);
        return $str;
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
