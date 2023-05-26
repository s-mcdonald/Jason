<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Validator;

class JsonValidator
{
    public static function isValidJson(string $json): bool
    {
        if (empty($json)) {
            return false;
        }

        return \json_decode($json, true) && json_last_error() === JSON_ERROR_NONE;
    }
}
