<?php

use SamMcDonald\Jason\Assert\JsonAsserter;

require_once '../vendor/autoload.php';


JsonAsserter::assertStringIsValidJson('{"foo":}');

echo "\n";
echo "\n";
