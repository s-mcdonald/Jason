<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

$json1 = '{"foo":"bar"}';
$json2 = '{"baz":"buz"}';
$json3 = '{"foo":"fim"}';
$combined = Json::mergeCombine($json1, $json2, $json3);
echo $combined;

echo "\n";
echo "\n";
