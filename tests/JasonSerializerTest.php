<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Jason\Attributes\Property;
use SamMcDonald\Jason\JasonSerializable;
use SamMcDonald\Jason\JsonSerializer;

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
}
