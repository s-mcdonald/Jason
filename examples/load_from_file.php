<?php

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Jason;

echo Jason::fromFile('./JsonExamples/profile.json');

echo "\n";
echo "\n";
