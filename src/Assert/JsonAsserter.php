<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Assert;

use SamMcDonald\Jason\Exceptions\InvalidArgumentException;
use SamMcDonald\Jason\Validator\JsonValidator;

class JsonAsserter
{
    private function __construct()
    {
    }

    public static function assertStringIsValidJson(string $json, string $message = ''): void
    {
        if (!JsonValidator::isValidJson($json)) {
            static::throwInvalidArgument(
                $message ?: 'Expected valid Json. Received Invalid Json structure'
            );
        }
    }

    public static function throwInvalidArgument(string $message): void
    {
        throw new InvalidArgumentException($message);
    }
}