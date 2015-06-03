<?php
if (!isset($_REQUEST["f"]))
	die("invalid request");

if (!isset($_POST['data']) || !is_array($_POST['data']))
	die("invalid request");

$data = $_POST['data'];
if (count($data)==0)
	die('ok');

$filename=$_REQUEST["f"];
$file = "../data_2fczq89/" . $filename . ".txt";
if (! is_writable($file)) {
	echo 'failed';
	die();
}

$lines = file($file);
if (!$lines)
	die("load data failed.");

$newLines = [];
foreach ($lines as $line) {
	$line = trim($line);
	if (empty($line))
		continue;
	
	$d = explode(",", $line);
	if (trim($d[0]) == trim($data[0][0])) {
		break;
	}
	
	$newLines[] = $line . "\n";
}
foreach ($data as $d) {
	$newLines[] = implode(',', $d) . "\n";
}

$first = true;
foreach($newLines as $line) {
	if ($first) {
		file_put_contents($file, $line, LOCK_EX);
		$first = false;
	} else {
		file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
	}
}

echo "ok";