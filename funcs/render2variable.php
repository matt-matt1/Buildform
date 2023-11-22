<?php
$GLOBALS['BufferedErrors']=Array();
function errorParse($errno, $errstr, $errfile, $errline, $errcontext) {
    $errorTypes = Array(
        E_ERROR => 'Fatal Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Fatal Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compilation Error',
        E_COMPILE_WARNING => 'Compilation Warning',
        E_USER_ERROR => 'Triggered Error',
        E_USER_WARNING => 'Triggered Warning',
        E_USER_NOTICE => 'Triggered Notice',
        E_STRICT => 'Deprecation Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error'
    );
    $ret=(object)Array(
        'number'=>$errno,
        'message'=>$errstr,
        'file'=>$errfile,
        'line'=>$errline,
        'context'=>$errcontext,
        'type'=>$errorTypes[$errno]
    );
    $GLOBALS['BufferedErrors'][]=$ret;
    return false;
}
function parse($fileToInclude, $argumentsToFile=false) {
    $bufferedErrorStack = $GLOBALS['BufferedErrors'];
    set_error_handler('errorParse', error_reporting());
    $GLOBALS['BufferedErrors']=Array();
    
    if (!file_exists($fileToInclude))
        return '';
    if ($argumentsToFile === false)
        $argumentsToFile = Array();
    $argumentsToFile = array_merge($GLOBALS, $argumentsToFile);
    foreach ($argumentsToFile as $variableName => $variableValue)
        $$variableName = $variableValue;
    ob_start();
    include($fileToInclude);
    $ret = ob_get_contents();
    ob_end_clean();
    
    restore_error_handler();
    $errors = $GLOBALS['BufferedErrors'];
    $GLOBALS['BufferedErrors'] = $bufferedErrorStack;
    if (count($errors)>0) {
        $ret.='<ul class="error">';
        foreach ($errors as $error)
            $ret.= 
                '<li>'.
                    '<b>'.$error->type.'</b>: '.
                    $error->message.
                    '<blockquote>'.
                        '<i>file</i>: '.$error->file.'<br />'.
                        '<i>line</i>: '.$error->line.
                    '</blockquote>'.
                '</li>';
        $ret.='</ul>';
    }
    return $ret;
}
