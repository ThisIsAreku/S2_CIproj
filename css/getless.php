<?php
header("Content-Type: text/css; charset=utf-8");

if(!isset($_GET['s']))
	die("/* no file */");

$allowed_files = array('main', 'alternate');

if(!in_array($_GET['s'], $allowed_files))
	die("/* unauthorized file */");

require "lessc.inc.php";

$less = new lessc;

try {
	$less->compileFile('./less/'.$_GET['s'].'.less', './'.$_GET['s'].'.css');
	echo file_get_contents('./'.$_GET['s'].'.css');
} catch (exception $e) {
	echo "/*fatal error: " , $e->getMessage() , "*/";
}
