<?php
ob_clean(); // Erase all output.

function plainTextStackTrace($e)
{
    $text = get_class($e).': '.$e->getMessage().'<br />'."\r\n";
    
    $lines = $e->getTrace();
    $a = array_keys($lines);
    $b = sizeOf($a);
    for($c=0;$c<$b;++$c)
    {
        $line = &$lines[$a[$c]];
        $func = '';
        if(!empty($line['class'])) $func .= $line['class'].$line['type'];
        $func .= $line['function'];
        $text .= '&nbsp;&nbsp;at '.$func.' in '.$line['file'].' on '.$line['line'].'<br />'."\r\n";
    }
    
    $text .= '<br />'."\r\n".str_replace("\n", '<br />'."\r\n", $e->getTraceAsString());
    return $text;
}

function printStackLevel($stack, $html = null, $level = 0)
{
    if(is_array($stack))
    {
        if(!empty($stack))
        {
            $html = $html.'<li><div class="trace';
            if($level == 0) $html .= ' first';
            $html .= '"><span class="title">#'.$level.'. In <span class="b">'.$stack[0]['file'].'</span> around line <span class="b">'.$stack[0]['line'].'</span>.</span><ul class="codeBlock">'.printFileSource($stack[0]['file'], $stack[0]['line']).'</ul></div><ul class="sub">';
            ++$level;
            
            array_shift($stack);
            printStackLevel($stack, $html, $level);
        }
        else
        {
            echo $html;
            for($level;$level>0;--$level) echo '</ul></li>';
        }
    }
}

function printFileSource($file, $offset)
{
    $show = 3;
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
    
    $start = $offset-$show;
    if($start < 0) $start = 0;
    
    $lines = array_slice($lines, $start, ($show*2)+1, true);
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
                <?php printStackLevel($e->getTrace()); ?>
            </ul><br />

            <p>Here is a plain text trace.</p>
            <p class="plain"><?php echo plainTextStackTrace($e); ?></p>
        </div>
        
        <div class="ffffuuu"></div>
    </body>
</html>
