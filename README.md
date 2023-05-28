# Jason (A PHP JSON Library)
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Jason)
[![Source](https://img.shields.io/badge/license-MIT-gold.svg)](https://github.com/s-mcdonald/Jason)

Serialize a class using attributes.

## Documentation

* [Usage](#Usage)
* [Installation](#installation)
* [Dependencies](#dependencies)

## Usage
### Step 1: Add Attributes to your class
```php

class User implements JsonSerializable
{
    #[Property('userName')]
    public string $name;

    #[Property('phoneNumbers')]
    public array $phoneNumbers;

    #[Property]
    private float $creditCard;

    public function setCreditCard(float $credit): void
    {
        $this->creditCard = $credit;
    }
}
```

### Step 2: Instantiate an object with values
```php
$user = new User();
$user->name = "Foo";
$user->phoneNumbers = [
    '044455444',
    '244755465',
];
$user->setCreditCard(54454.5);
```

### Step 3: Serialize
```php
$serializer = new JsonSerializer();
echo $serializer->toJsonString($user);

// Produces
{
    "userName": "Foo",
    "phoneNumbers": [
        "044455444",
        "244755465"
    ],
    "creditCard": 54454.5,
}
```


### JsonBuilder - <small>@since: 1.1.0</small>

```php

echo Json::createJsonBuilder()
        ->addNumericProperty('id', 11)
        ->addStringProperty('title', "Perfume Oil")
        ->addNumericProperty('rating', 4.26)
        ->addNumericProperty('stock', 65)
        ->addObjectProperty(
            'thumbnail',
            Json::createJsonBuilder()
                ->addStringProperty("url", "https://i.dummyjson.com/data/products/11/thumbnail.jpg")
                ->addStringProperty("title", "thumbnail.jpg")
        )
        ->addArrayProperty("images", [
            "https://i.dummyjson.com/data/products/11/1.jpg",
            "https://i.dummyjson.com/data/products/11/2.jpg"
        ])
;
```
Will create the following

```json
{
    "id": 11,
    "title": "Perfume Oil",
    "rating": 4.26,
    "stock": 65,
    "thumbnail": {
    "url": "https://i.dummyjson.com/data/products/11/thumbnail.jpg",
          "title": "thumbnail.jpg"
    },
    "images": [
          "https://i.dummyjson.com/data/products/11/1.jpg",
          "https://i.dummyjson.com/data/products/11/2.jpg"
     ]
}
```

### JsonAsserter

```php
JsonAsserter::assertStringIsValidJson('{"foo":"bar"}');
```


### Json::createFromArray

```php
$json = Json::createFromArray(["foo" => "baz"]);
echo $json; // {"foo":"baz"}
echo $json->toString(); // {"foo":"baz"}
echo $json->toPretty(); 
// {
//   "foo":"baz"
// }
```

### Json::createFromUrl

```php
$json = Json::createFromUrl("http://some-domain.com/json-endpoint.json");
echo $json->toString(); // {"foo":"baz"}
```

### Json::createFromFile

```php
$json = Json::createFromFile("/path/on/server.json");
echo $json->toPretty(); 
// {
//   "foo":"baz"
// }
```


### Json::createFromStringable

```php
$json = Json::createFromStringable('{"foo":"baz"}');
echo $json->toString(); // {"foo":"baz"}
```



### Json::mergeCombine

```php
$json = Json::mergeCombine('{"foo":"baz"}', '{"buz":"qux"}');
echo $json->toString(); // {"foo":"baz","buz":"qux"}
```


### Json::convertJsonToArray

```php
$json = Json::convertJsonToArray('{"foo":"baz","buz":"qux"}');
echo $json->toString(); // ["foo" => "baz","buz" => "qux"}
```


### Json::convertFromJsonToObject

```php
$json = Json::convertFromJsonToObject('{"foo":"baz","buz":"qux"}');
echo $json->toString(); // instance of JsonSerializable::class with values
```

<a name="installation"></a>
## Installation

Via Composer. Run the following command from your project's root.

```
composer require s-mcdonald/jason
```

<a name="dependencies"></a>
## Dependencies

*  Php 8.0

## License

Jason is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).
