<?php

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

echo Json::fromUrl('https://jsonplaceholder.typicode.com/todos/1');

echo "\n";
echo "\n";
