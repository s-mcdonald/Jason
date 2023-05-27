<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

$jsonObj = Json::createFromFile('./JsonExamples/compressed.json');
echo $jsonObj->toPretty();

echo "\n";
echo "\n";
