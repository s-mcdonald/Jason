<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason;

use Generator;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Jason\Attributes\Property;
use SamMcDonald\Jason\Builder\AbstractJsonBuilder;
use SamMcDonald\Jason\JsonSerializable;
use SamMcDonald\Jason\JsonSerializer;

/**
 * @covers \SamMcDonald\Jason\JsonSerializer
 */
class JsonSerializerTest extends TestCase
{
    public function testSerializeProperties(): void
    {
        $class = new class extends AbstractJsonBuilder implements JsonSerializable {
            #[Property]
            public string $namePublicProperty = 'foo';

            #[Property]
            protected string $nameProtectedProperty = 'baz';

            #[Property]
            private string $namePrivateProperty = 'bar';

            public string $namePublicPropertyNoAttribute = 'do-not-serialize1';

            protected string $nameProtectedPropertyNoAttribute = 'do-not-serialize2';

            private string $namePrivatePropertyNoAttribute = 'do-not-serialize3';

            #[Property]
            public int $age = 25;

            #[Property]
            public array $collection = [
                'qux',
                'fiz',
                'dan'
            ];
        };

        $serializer = new JsonSerializer();

        self::assertEquals(
            '{"namePublicProperty":"foo","nameProtectedProperty":"baz","namePrivateProperty":"bar","age":25,"collection":["qux","fiz","dan"]}',
            $serializer->serialize($class)
        );
    }

    public function testSerializeWithMethods(): void
    {
        $class = new class extends AbstractJsonBuilder implements JsonSerializable {
            #[Property]
            public function getFoo(): string { return "foo"; }

            public function getBar(): string { return "bar"; }

            #[Property]
            protected function getProtected(): string { return "quz"; }

            #[Property]
            private function getPrivate(): string { return "zup"; }
            private function getPrivateNoAccess(): string { return "bang"; }
        };

        $sut = new JsonSerializer();

        self::assertEquals(
            '{"getFoo":"foo","getProtected":"quz","getPrivate":"zup"}',
            $sut->serialize($class)
        );
    }

    /**
     * @dataProvider provideDataForTestBigIntAsString
     */
    public function testBigIntAsInt(int $int): void
    {
        $class = new class implements JsonSerializable {
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
        $class = new class implements JsonSerializable {
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
