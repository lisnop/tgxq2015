<?php
if (!isset($_GET["d"])) {
	die("invalid request");
}
$filename = $_GET["d"];
$callback="callback";
if (isset($_GET["callback"])) {
	$callback=$_GET["callback"];
}
$file = "data_2fczq89/" . $filename . ".txt";
if (! file_exists($file) || ! is_readable($file)) die("invalid require!!");

$lines = file($file);
if (!$lines)
	die("load data failed.");

$first = true;
echo $callback;
echo "([";
foreach($lines as $line) {
	$line = trim($line);
	if (empty($line))
		continue;
	if ($first)
		$first = false;
	else
		echo ",";
	echo "[" . trim($line) . "]";
}
echo "]);";
