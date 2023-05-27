<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

use ReflectionClass;
use ReflectionProperty;
use SamMcDonald\Jason\Attributes\Property;
use SamMcDonald\Jason\Enums\JsonOutputStyle;
use SamMcDonald\Jason\Traits\BitWiser;

class JsonSerializer
{
    use BitWiser;

    public const JS_INT_MAX = 9007199254740991;

    public function __construct(
        private bool $bigIntAsString = true,
        private bool $allowNulls = false,
        private bool $allowStatics = true
    ) {
        if ($this->bigIntAsString) {
            $this->setFlag(JSON_BIGINT_AS_STRING);
        }
    }

    public function isDefinedBigIntAsString(): bool
    {
        return $this->isFlagSet(JSON_BIGINT_AS_STRING);
    }

    public function serialize(JsonSerializable $object, JsonOutputStyle $displayMode = JsonOutputStyle::Compressed): string
    {
        $classObject = new \stdClass;
        $reflectionClass = new ReflectionClass($object);

        $this->collectProperties($reflectionClass, $object, $classObject);
        $this->collectMethods($reflectionClass, $object, $classObject);

        if ($displayMode === JsonOutputStyle::Pretty) {
            $this->setFlag(JSON_PRETTY_PRINT);
        }

        return json_encode($classObject, flags: $this->bitWiseFlags);
    }

    private function collectProperties(
        ReflectionClass $reflectionClass,
        JsonSerializable $object,
        \stdClass $classObject): void
    {
        $properties = $reflectionClass->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE
        );

        foreach ($properties as $prop) {
            $attributes = $prop->getAttributes(Property::class);
            $addToObjectName = $prop->getName();

            if (!$prop->isInitialized($object)) {
                continue;
            }

            if ($prop->isStatic() && !$this->allowStatics) {
                continue;
            }

            $propertyValue = $prop->getValue($object);
            if (!$this->allowNulls && $propertyValue === null) {
                continue;
            }

            $addToObject = false;

            foreach ($attributes as $attrib) {
                if ($attrib->getName() === Property::class) {
                    $addToObject = true;
                    foreach ($attrib->getArguments() as $ag) {
                        $addToObjectName = $ag;
                        break;
                    }
                }
            }

            if ($addToObject) {
                $classObject->{$addToObjectName} = $this->bigIntAsStringValue($propertyValue);
            }
        }
    }

    private function collectMethods(
        ReflectionClass $reflectionClass,
        JsonSerializable $object,
        \stdClass $classObject
    ): void
    {
        $methods = $reflectionClass->getMethods(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE
        );

        foreach ($methods as $method) {
            $addToObject = false;
            $addToObjectName = $method->getName();

            if ($method->isConstructor() || $method->isDestructor() || !$method->hasReturnType()) {
                continue;
            }

            $returnType = $method->getReturnType();
            if ($returnType->getName() === 'void') {
                continue;
            }

            $methodValue = $object->{$method->getName()}();
            if (!$this->allowNulls && $methodValue === null) {
                continue;
            }

            if ($method->getNumberOfParameters() > 0) {
                continue;
            }

            if ($method->isStatic() && !$this->allowStatics) {
                continue;
            }

            $attributes = $method->getAttributes(Property::class);
            foreach ($attributes as $attrib) {
                if ($attrib->getName() === Property::class) {
                    $addToObject = true;
                    foreach ($attrib->getArguments() as $ag) {
                        $addToObjectName = $ag;
                        break;
                    }
                }
            }

            if ($addToObject) {
                $classObject->{$addToObjectName} = $this->bigIntAsStringValue($methodValue);
            }
        }
    }

    private function isStringValueInt(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    private function bigIntAsStringValue(mixed $propertyValue): mixed
    {
        if (is_int($propertyValue) && $this->isFlagSet(JSON_BIGINT_AS_STRING)) {
            if ($propertyValue >= self::JS_INT_MAX) {
                return strval($propertyValue);
            }
        }

        return $propertyValue;
    }
}
