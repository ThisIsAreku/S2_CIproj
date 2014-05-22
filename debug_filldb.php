<?php
require 'bootstrap.php';

foreach(file('debug_db.csv') as $l)
{
	$d = explode(' ', $l);
	$arr = array(
		'hex' => trim($d[1]),
		'name' => trim($d[0]),
		'text' => trim(file_get_contents('http://loripsum.net/api/1/short/plaintext')),
		'price' => mt_rand(99, 6000) / 100
		);
	$Dataman->addItem(new Item($arr), false);
}
$Dataman->save('products');
echo 'fin';