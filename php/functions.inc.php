<?php
function sendErrorHeader($code)
{
	$codeText = array(
		404 => 'Not Found',
		500 => 'Internal Server Error'
		);
	header($code.' '.$codeText[$code], true, $code);
	$_GET['e'] = $code;
}

function returnRunTime(){
    return round((microtime(true) - TIME_START), 4);
}

function hexToRgb($hex)
{
	preg_match('/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i', $hex, $m);
	$r = array(
		'r' => hexdec($m[1]),
		'g' => hexdec($m[2]),
		'b' => hexdec($m[3])
		);
	return $r;
}