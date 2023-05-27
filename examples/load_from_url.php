<?php

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

$json = Json::createFromUrl('https://jsonplaceholder.typicode.com/todos/1');
echo $json;
echo $json->toPretty();

echo "\n";
echo "\n";
