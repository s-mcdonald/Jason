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
        $origJson = <<<JSON
{"userId":7,"id":9,"title":"Mr White","active":true,"locations":["usa","aus"]}
JSON;

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
            Json::createFromString($origJson)->toObject()
        );

        static::assertTrue(
            Json::createFromString($origJson)->toObject()->active
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
            Json::createFromString($origJson)->toArray()
        );
    }

    public function testToString(): void
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

        static::assertSame(
            $prettyJson,
            Json::createFromString($origJson)->toPretty()
        );

        static::assertSame(
            $prettyJson,
            Json::createFromString($origJson)->toString()
        );

        static::assertSame(
            $prettyJson,
            (string) Json::createFromString($origJson)
        );
    }
}
