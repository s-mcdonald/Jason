<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

$array = [
    "string" => "foo",
    "int" => 123,
    "arr" => [
        1,2,3
    ]
];
$json = Json::createFromArray($array);
echo $json->toPretty();

echo "\n";
echo "\n";
