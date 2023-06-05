<?php

declare(strict_types=1);

namespace Tests\SamMcDonald\Jason;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Jason\Json;
use SamMcDonald\Jason\JsonSerializable;

/**
 * @cover \SamMcDonald\Jason\Json
 */
class JsonTest extends TestCase
{
    public function testConvertJsonToArray(): void
    {
        $json = <<<JSON
{
  "id": 2,
  "name": "Ervin Howell",
  "username": "Antonette",
  "email": "Shanna@melissa.tv",
  "address": {
    "street": "Victor Plains",
    "suite": "Suite 879",
    "city": "Wisokyburgh",
    "zipcode": "90566-7771",
    "geo": {
      "lat": "-43.9509",
      "lng": "-34.4618"
    }
  },
  "phone": "010-692-6593 x09125",
  "website": "anastasia.net",
  "company": {
    "name": "Deckow-Crist",
    "catchPhrase": "Proactive didactic contingency",
    "bs": "synergize scalable supply-chains"
  }
}
JSON;

        $array = [
            "id" => 2,
            "name" => "Ervin Howell",
            "username" => "Antonette",
            "email" => "Shanna@melissa.tv",
            "address" => [
                "street" => "Victor Plains",
                "suite" => "Suite 879",
                "city" => "Wisokyburgh",
                "zipcode" => "90566-7771",
                "geo" => [
                    "lat" => "-43.9509",
                    "lng" => "-34.4618"
                ]
            ],
          "phone" => "010-692-6593 x09125",
          "website" => "anastasia.net",
          "company" => [
            "name" => "Deckow-Crist",
            "catchPhrase" => "Proactive didactic contingency",
            "bs" => "synergize scalable supply-chains"
          ]
        ];

        static::assertEquals(
            $array,
            Json::convertJsonToArray($json)
        );
    }

    public function testConvertFromJsonToObject(): void
    {
        $origJson = <<<JSON
{"userId": 7,"id": 9,"title": "Mr White","active": true,"locations": ["usa","aus"]}
JSON;

        $prettyJson = <<<JSON
{
    "userId": 7,
    "id": 9,
    "title": "Mr White",
    "active": true,
    "locations": [
        "usa",
        "aus"
    ]
}
JSON;

        static::assertInstanceOf(
            JsonSerializable::class,
            Json::convertFromJsonToObject($origJson)
        );

        static::assertEquals(
            $prettyJson,
            (string) Json::convertFromJsonToObject($origJson)
        );
    }

    public function testConvertFromJsonToObjectCanBeSerialized(): void
    {
        $origJson = '{"userId":7,"id":9,"title":"Mr White","active":true,"locations":["usa","aus"]}';

        $obj = Json::convertFromJsonToObject($origJson);

        static::assertEquals(
            $origJson,
            Json::serialize($obj)
        );
    }

    public function testToObject(): void
    {
        $origJson = <<<JSON
{"userId": 7,"id": 9,"title": "Mr White","active": true,"locations": ["usa","aus"]}
JSON;

        static::assertInstanceOf(
            JsonSerializable::class,
            Json::createFromStringable($origJson)->toObject()
        );

        static::assertTrue(
            Json::createFromStringable($origJson)->toObject()->active
        );
    }

    public function testToArray(): void
    {
        $origJson = <<<JSON
{"userId": 7,"id": 9,"title": "Mr White","active": true,"locations": ["usa","aus"]}
JSON;

        static::assertSame(
            [
                'userId' => 7,
                'id' => 9,
                'title' => 'Mr White',
                'active' => true,
                'locations' => [
                    0 => 'usa',
                    1 => 'aus',
                ]
            ],
            Json::createFromStringable($origJson)->toArray()
        );
    }

    public function testToPretty(): void
    {
        static::assertSame(
            $this->getExpectedJsonPretty(),
            Json::createFromStringable($this->getExpectedJsonCompressed())->toPretty()
        );
    }

    public function getExpectedJsonCompressed(): string
    {
        return <<<JSON
{"userId":7,"id":9,"title":"Mr White","active":true,"locations":["usa","aus"]}
JSON;

        $prettyJson = <<<JSON
{
    "userId": 7,
    "id": 9,
    "title": "Mr White",
    "active": true,
    "locations": [
        "usa",
        "aus"
    ]
}
JSON;
    }

    public function getExpectedJsonPretty(): string
    {
        return <<<JSON
{
    "userId": 7,
    "id": 9,
    "title": "Mr White",
    "active": true,
    "locations": [
        "usa",
        "aus"
    ]
}
JSON;
    }

    public function testToString(): void
    {
        $origJson = <<<JSON
{"userId":7,"id":9,"title":"Mr White","active":true,"locations":["usa","aus"]}
JSON;

        $prettyJson = <<<JSON
{
    "userId": 7,
    "id": 9,
    "title": "Mr White",
    "active": true,
    "locations": [
        "usa",
        "aus"
    ]
}
JSON;

        static::assertSame(
            $origJson,
            Json::createFromStringable($origJson)->toString()
        );

        static::assertSame(
            $origJson,
            (string) Json::createFromStringable($origJson)
        );
    }

    public function testMergeCombine(): void
    {
        $origJson1 = <<<JSON
{"userId": 7,"id": 9,"title": "Mr White","active": true,"locations": ["usa","aus"]}
JSON;
        $origJson2 = <<<JSON
{"userId": 8,"id": 3,"title": "Mr Black","locations": ["usa", "china"]}
JSON;
        // @todo - This is an identified bug, ideally we would want the locations to output
        //         ["usa","aus","china"]
        $expectedResult = <<<JSON
{"userId":8,"id":3,"title":"Mr Black","active":true,"locations":["usa","china"]}
JSON;

        static::assertSame(
            $expectedResult,
            Json::mergeCombine($origJson1, $origJson2)->toString()
        );
    }

    public function testIterator(): void
    {
        $json = <<<JSON
{
    "userId": 7,
    "id": 9,
    "title": "Mr White",
    "active": true,
    "deep": {
      "dive": {
        "audi": "eu",
        "ford": "usa"
      }
    },
    "locations": [
        "usa",
        "aus"
    ]
}
JSON;
        $json = Json::createFromStringable($json);

        self::assertEquals([
                'userId' => 7,
                'id' => 9,
                'title' => 'Mr White',
                'active' => true,
                'audi' => 'eu',
                'ford' => 'usa',
                0 => 'usa',
                1 => 'aus',
            ],
            iterator_to_array($json->toIterator())
        );
    }
}
