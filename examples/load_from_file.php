<?php

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

echo Json::fromFile('./JsonExamples/profile.json');

echo "\n";
echo "\n";
