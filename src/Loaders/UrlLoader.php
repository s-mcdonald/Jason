<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Loaders;

use SamMcDonald\Jason\Validator\JsonValidator;

class UrlLoader
{
    private function __construct() {}

    public static function load(string $url): bool|string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        ini_set("allow_url_fopen", '1');

        return (JsonValidator::isValidJson(file_get_contents($url))) ?? false;
    }
}
