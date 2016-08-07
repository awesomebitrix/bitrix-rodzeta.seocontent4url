<?php

require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

if (setlocale(LC_CTYPE, "ru_RU.UTF-8", "en_US.UTF-8") === false) {
	echo "Error while setlocale: ru_RU.UTF-8, en_US.UTF-8";
}

$path = dirname(__DIR__);

$fcsv = fopen($path . "/meta.csv", "r");
if ($fcsv === FALSE) {
	return;
}

$i = 0;
$headers = null;
while (($row = fgetcsv($fcsv, 10000, "\t")) !== FALSE) {
	$i++;
	if ($i == 1) {
		$headers = $row;
		continue;
	}
	if (count($row) < 2) {
		continue;
	}

	$meta = array_combine($headers, $row);
	file_put_contents(
		$path . "/data/" . sha1(trim($row[0])) . ".php",
		"<?php\nreturn " . var_export($meta, true) . ";"
	);

}
fclose($fcsv);

echo "OK";
