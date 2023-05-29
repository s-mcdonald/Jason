<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason\Validator;

use SamMcDonald\Jason\Validator\JsonValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \SamMcDonald\Jason\Validator\JsonValidator
 */
class JsonValidatorTest extends TestCase
{
    /**
     * @dataProvider provideDataForTestIsValidJsonIsInvalid
     */
    public function testIsValidJsonIsInvalid(string $json): void
    {
        static::assertFalse(JsonValidator::isValidJson($json));
    }

    public function provideDataForTestIsValidJsonIsInvalid(): array
    {
        return [
            'empty json' => ['{}'],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsValidJson
     */
    public function testIsValidJson(string $json): void
    {
        static::assertTrue(JsonValidator::isValidJson($json));
    }

    public function provideDataForTestIsValidJson(): array
    {
        return [
            'generic json' => ['{"foo":"bar"}'],
            'with dots' => ['{".":"."}'],
            'forward slash' => ['{"/":"//"}'],
            'forward slash with null' => ['{"/":null}'],
        ];
    }
}
