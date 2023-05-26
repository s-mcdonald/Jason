# Jason
[![Source](https://img.shields.io/badge/source-S_McDonald-blue.svg)](https://github.com/s-mcdonald/Jason)
[![Source](https://img.shields.io/badge/license-MIT-gold.svg)](https://github.com/s-mcdonald/Jason)

Serialize a class using attributes.
```php
$serializer = new JsonSerializer();
echo $serializer->serialize($objectImplementsJasonSerializable);
```

To make this work, just apply the Property attribute to your properties and/or methods.
You can pass a string arg to the Attribute if you want to change the name of the Json property for the
serialized json string.


### Step 1: Add Attributes to your class
```php

class User implements JasonSerializable
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

## Documentation

* [Installation](#installation)
* [Dependencies](#dependencies)


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
