<?php


use SamMcDonald\Jason\Jason;

require_once '../vendor/autoload.php';
require_once './SampleClasses/User.php';
require_once './SampleClasses/History.php';


echo Jason::fromFile('./JsonExamples/profile.json');


echo "\n";
echo "\n";
