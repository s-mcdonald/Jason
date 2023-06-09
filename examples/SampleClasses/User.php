<?php

declare(strict_types=1);

use SamMcDonald\Jason\Attributes\Property;
use SamMcDonald\Jason\JsonSerializable;

class User implements JsonSerializable
{
    #[Property('userName')]
    public string $name;
    public static string $staticProp = 'fooBar';

    #[Property('willNotBeSerialized')]
    public string $dontSerializeUnInstantiatedProperty;

    public string $dontSerializeWithoutAttributeProperty;

    #[Property('phoneNumbers')]
    public array $phoneNumbers;

    #[Property]
    private float $creditCard;

    #[Property('onlyDisplayIfAllowNullsIsTrue')]
    private ?float $valueIsInitializedAsNull = null;

    #[Property('accountHistory')]
    public History $history;

    public function setCreditCard(float $credit): void
    {
        $this->creditCard = $credit;
    }

    #[Property('doSomething')]
    public function doSomething(): string
    {
        return 'foo';
    }

    #[Property('doSomething3')]
    public static function dontDoSomething(): string
    {
        return 'bar';
    }
}