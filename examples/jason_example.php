<?php

use SamMcDonald\Jason\Enums\JsonOutputStyle;
use SamMcDonald\Jason\JsonSerializer;

require_once './SampleClasses/User.php';
require_once './SampleClasses/History.php';

$user = new User();
$user->name = "Foo";
$user->setCreditCard(54454.5);
$user->phoneNumbers = [
    '044455444',
    '244755465',
];

$user->history = new History();

$serializer = new JsonSerializer();
echo $serializer->serialize($user, JsonOutputStyle::Pretty);

echo "\n";
echo "\n";
