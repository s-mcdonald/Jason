<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason\Assert;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Jason\Assert\JsonAsserter;
use SamMcDonald\Jason\Exceptions\InvalidArgumentException;

/**
 * @covers \SamMcDonald\Jason\Assert\JsonAsserter
 */
class JsonAsserterTest extends TestCase
{
    /**
     * @dataProvider provideDataForTestAssertStringIsValidJsonThrowsException
     */
    public function testAssertStringIsValidJsonThrowsException(string $json): void
    {
        static::expectException(InvalidArgumentException::class);

        JsonAsserter::assertStringIsValidJson($json);
    }

    public function provideDataForTestAssertStringIsValidJsonThrowsException(): array
    {
        return [
            'empty string is invalid' => [
                ''
            ],
            'empty json object' => [
                '{}'
            ],
        ];
    }

    /**
     * @dataProvider provideDataForTestAssertStringIsValidJsonDoesNotThrowException
     */
    public function testAssertStringIsValidJsonDoesNotThrowException(string $json): void
    {
        $exceptionThrown = false;
        try {
            JsonAsserter::assertStringIsValidJson($json);
        } catch (\Exception $e) {
            $exceptionThrown = true;
        }

        self::assertFalse($exceptionThrown);
    }

    public function provideDataForTestAssertStringIsValidJsonDoesNotThrowException(): array
    {
        return [
            'empty string is invalid' => [
                '{"foo":"bar"}',
                '{".":"dot"}',
                '{",":"comma"}',
            ],
        ];
    }
}
