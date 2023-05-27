<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason;

use Generator;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Jason\Attributes\Property;
use SamMcDonald\Jason\JasonSerializable;
use SamMcDonald\Jason\JsonSerializer;

/**
 * @covers \SamMcDonald\Jason\JsonSerializer
 */
class JasonSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $class = new class implements JasonSerializable {
            #[Property]
            public string $name = 'foo';

            #[Property]
            public int $age = 25;

            #[Property]
            public array $collection = [
                'ford',
                'ferrari',
                'porche'
            ];

            public string $doNotSerialize = 'oops';

            public function __construct() {}
        };

        $serializer = new JsonSerializer();

        self::assertEquals(
            '{"name":"foo","age":25,"collection":["ford","ferrari","porche"]}',
            $serializer->serialize($class)
        );
    }

    public function testSerializeWithMethods(): void
    {
        $class = new class implements JasonSerializable {
            #[Property]
            public string $name = 'foo';

            public string $doNotSerialize = 'oops';

            #[Property]
            public function getFoo(): string { return "foo"; }

            public function getBar(): string { return "bar"; }
        };

        $serializer = new JsonSerializer();

        self::assertEquals(
            '{"name":"foo","getFoo":"foo"}',
            $serializer->serialize($class)
        );
    }

    /**
     * @dataProvider provideDataForTestBigIntAsString
     */
    public function testBigIntAsInt(int $int): void
    {
        $class = new class implements JasonSerializable {
            #[Property]
            public int $bigInt = 9007199254740991;

        };

        $class->bigInt = $int;

        $serializer = new JsonSerializer(bigIntAsString: false);

        self::assertEquals(
            sprintf('{"bigInt":%s}', $int),
            $serializer->serialize($class)
        );
    }

    /**
     * @dataProvider provideDataForTestBigIntAsString
     */
    public function testBigIntAsString(int $int): void
    {
        $class = new class implements JasonSerializable {
            #[Property]
            public int $bigInt = 9007199254740991;

        };

        $class->bigInt = $int;

        $serializer = new JsonSerializer(bigIntAsString: true);

        self::assertEquals(
            sprintf('{"bigInt":"%s"}', $int),
            $serializer->serialize($class)
        );
    }

    public function provideDataForTestBigIntAsString(): Generator
    {
        $jsMax = JsonSerializer::JS_INT_MAX;
        for ($i = 1; $i < 10000; $i = $i + 500) {
            $val = $jsMax + $i;
            yield 'foo' . $val => [$val];
        }
    }

    public function testIsDefinedBigIntAsString(): void
    {
        $sut = new JsonSerializer(bigIntAsString: true);
        static::assertTrue($sut->isDefinedBigIntAsString());
    }

    public function testIsDefinedBigIntAsStringWithFalse(): void
    {
        $sut = new JsonSerializer(bigIntAsString: false);
        static::assertFalse($sut->isDefinedBigIntAsString());
    }
}
