<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Assert;

use SamMcDonald\Jason\Exceptions\InvalidArgumentException;

class JsonAsserter
{
    private function __construct()
    {
    }

    public static function assertStringIsValidJson(string $json, string $message = ''): void
    {
        if (!static::validateJsonString($json)) {
            static::throwInvalidArgument(
                $message ?: 'Expected valid Json. Received Invalid Json structure'
            );
        }
    }

    public static function throwInvalidArgument(string $message): void
    {
        throw new InvalidArgumentException($message);
    }

    protected static function validateJsonString(string $json): bool
    {
        if (empty($json)) {
            return false;
        }

        return \json_decode($json, true) && json_last_error() === JSON_ERROR_NONE;
    }
}