<?php

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Jason;

echo "\n";
echo "\n";
$jsonString = '{"foo":"bar","array":[1,2,3,4,5]}';

echo "\n";
echo Jason::pretty($jsonString);

echo "\n";
echo "\n";
