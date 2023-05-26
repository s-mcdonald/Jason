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

    public function __construct(
        private readonly bool $bigIntAsString = true,
        private readonly bool $allowNulls = false,
        private readonly bool $allowStatics = true
    ) {
        if ($this->bigIntAsString) {
            $this->setFlag(JSON_BIGINT_AS_STRING);
        }
    }

    public function serialize(JasonSerializable $object, JsonOutputStyle $displayMode = JsonOutputStyle::Compressed): string
    {
        $classObject = new \stdClass;
        $reflectionClass = new ReflectionClass($object);

        $this->collectProperties($reflectionClass, $object, $classObject);
        $this->collectMethods($reflectionClass, $object, $classObject);

        $flags = ($displayMode === JsonOutputStyle::Pretty) ? JSON_PRETTY_PRINT :  0;
        return json_encode($classObject,  $flags | JSON_BIGINT_AS_STRING);
    }

    private function collectProperties(
        ReflectionClass $reflectionClass,
        JasonSerializable $object,
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
                $classObject->{$addToObjectName} = $propertyValue;
            }
        }
    }

    private function collectMethods(
        ReflectionClass $reflectionClass,
        JasonSerializable $object,
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
                $classObject->{$addToObjectName} = $methodValue;
            }
        }
    }
}
