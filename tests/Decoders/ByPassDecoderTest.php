<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason\Decoders;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Jason\Decoders\ByPassDecoder;

/**
 * @covers \SamMcDonald\Jason\Decoders\ByPassDecoder;
 */
class ByPassDecoderTest extends TestCase
{
    public function testWithDefaults()
    {
        $json = '{"shoots": "foo", "array": ["bar"], "intVal": 1234 }';

        $sut = new ByPassDecoder();
        static::assertTrue($sut->decode($json)->isValid());
        static::assertIsString($sut->decode($json)->getBody());
    }
}

