<?php

global $_APP;

$url = $_SERVER["REQUEST_URI"];
// $url = remove query params

//$fname = __DIR__ . "/data/" . sha1($url) . ".php";
//$_APP["meta"] = file_exists($fname)? include $fname : [];
$_APP["meta"] = include __DIR__ . "/data/" . sha1($url) . ".php";

print_r($_APP["meta"]);
