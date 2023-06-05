<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Jason\Attributes\JsonObjectAsProperty;
use SamMcDonald\Jason\Attributes\Property;
use SamMcDonald\Jason\Encoder\JsonEncoder;
use SamMcDonald\Jason\Enums\JsonOutputStyle;
use SamMcDonald\Jason\Traits\BitWiser;

class JsonSerializer
{
    use BitWiser;

    public const JS_INT_MAX = 9007199254740991;

    private bool $serializeAll = false;

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
        $reflectionScope = new ReflectionObject($object);

        $classLevelAll = $reflectionScope->getAttributes(JsonObjectAsProperty::class);
        if (count($classLevelAll)) {
            $this->serializeAll = true;
        }

        $this->collectProperties($reflectionScope, $object, $classObject);
        $this->collectMethods($reflectionScope, $object, $classObject);

        if ($displayMode === JsonOutputStyle::Pretty) {
            $this->setFlag(JSON_PRETTY_PRINT);
        }

        $encoder = new JsonEncoder(flags: $this->bitWiseFlags);
        return $encoder->encode($classObject)->getBody();
    }

    private function collectProperties(
        ReflectionObject $reflectionScope,
        JsonSerializable $object,
        \stdClass $classObject): void
    {
        $properties = $reflectionScope->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE
        );

        foreach ($properties as $prop) {
            $attributes = $prop->getAttributes();
            $addToObjectName = $prop->getName();

            if (
                !$prop->isInitialized($object) ||
                ($this->serializeAll === false && $prop->isStatic() && !$this->allowStatics)
            ) {
                continue;
            }

            $prop->setAccessible(true);
            $propertyValue = $prop->getValue($object);
            if (!$this->allowNulls && $propertyValue === null) {
                continue;
            }

            $addToObject = false;

            if ($this->serializeAll === true) {
                $addToObject = true;
            }

            foreach ($attributes as $attrib) {
                if ($attrib->getName() === Property::class ) {
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
        ReflectionObject $reflectionScope,
        JsonSerializable $object,
        \stdClass $classObject
    ): void
    {
        $methods = $reflectionScope->getMethods(
            ReflectionMethod::IS_PUBLIC |
            ReflectionMethod::IS_PROTECTED |
            ReflectionMethod::IS_PRIVATE
        );

        foreach ($methods as $method) {
            $addToObject = false;
            $addToObjectName = $method->getName();

            $returnType = $method->getReturnType();

            if ($method->isConstructor() ||
                $method->isDestructor() ||
                !$method->hasReturnType() ||
                $method->getNumberOfParameters() > 0 ||
                $method->getName() === '__toString' ||
                $returnType->getName() === 'void'
            ) {
                continue;
            }

            $method->setAccessible(true);
            try {
                $methodValue = $method->invoke($object);
            } catch (ReflectionException $e) {
                continue;
            }

            if (
                !$this->allowNulls && $methodValue === null ||
                ($this->serializeAll === false && $method->isStatic() && !$this->allowStatics)
            ) {
                continue;
            }

            $attributes = $method->getAttributes(Property::class);
            foreach ($attributes as $attrib) {
                $addToObject = true;
                foreach ($attrib->getArguments() as $ag) {
                    $addToObjectName = $ag;
                    break;
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
