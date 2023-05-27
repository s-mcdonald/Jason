<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason\Decoders;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Jason\Decoders\JsonDecoder;

/**
 * @covers \SamMcDonald\Jason\Decoders\JsonDecoder;
 */
class JsonDecoderTest extends TestCase
{
    public function testWithDefaults()
    {
        $json = '{"shoots": "foo", "array": ["bar"], "intVal": 1234 }';

        $sut = new JsonDecoder();
        static::assertTrue($sut->decode($json)->isValid());
        static::assertIsInt($sut->decode($json)->getBody()->intVal);
        static::assertIsString($sut->decode($json)->getBody()->shoots);
        static::assertIsArray($sut->decode($json)->getBody()->array);
    }

    public function testWithAssociative()
    {
        $json = '{"shoots": "foo", "array": ["bar"], "intVal": 1234 }';

        $sut = new JsonDecoder(associative: true);
        static::assertTrue($sut->decode($json)->isValid());
        $result = $sut->decode($json)->getBody();
        static::assertIsArray($result);
        static::assertIsInt($result['intVal']);
        static::assertIsString($result['shoots']);
        static::assertIsArray($result['array']);
    }
}

