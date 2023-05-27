<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Validator;

use SamMcDonald\Jason\Exceptions\JsonDecodeException;

class JsonValidator
{
    public static function isValidJson(string $json): bool
    {
        if (empty($json)) {
            return false;
        }

        return \json_decode($json, true) && self::hasDecodeValidationError() === false;
    }

    public static function hasDecodeValidationError(): bool|JsonDecodeException
    {
        return match (json_last_error()) {
            JSON_ERROR_NONE => false,
            JSON_ERROR_DEPTH => new JsonDecodeException(
                'The maximum stack depth was exceeded.',
            ),
            JSON_ERROR_STATE_MISMATCH => new JsonDecodeException(
                'Malformed Json.'
            ),
            JSON_ERROR_CTRL_CHAR => new JsonDecodeException(
                'Unexpected control character was found within the Json string.'
            ),
            JSON_ERROR_SYNTAX => new JsonDecodeException(
                'Syntax error.'
            ),
            JSON_ERROR_UTF8 => new JsonDecodeException(
                'Invalid UTF-8 characters.'
            ),
            default => new JsonDecodeException(
                 sprintf(
                    'The following decoding error was encountered: `%s`',
                    json_last_error_msg()
                 )
            ),
        };
    }
}
