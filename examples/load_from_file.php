<?php

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

$json = Json::createFromFile('./JsonExamples/profile.json');
echo $json->toPretty();

echo "\n";
echo "\n";
