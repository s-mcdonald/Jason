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

    public function __construct(private bool $bigIntAsString = true,
                                private bool $allowNulls = false)
    {
        if ($this->bigIntAsString) {
            $this->setFlag(JSON_BIGINT_AS_STRING);
        }
    }

    public function serialize(JasonSerializable $object, JsonOutputStyle $displayMode = JsonOutputStyle::Compressed): string
    {
        $classObject = new \stdClass;
        $reflectionClass = new ReflectionClass($object);

        $properties = $reflectionClass->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE
        );

        foreach ($properties as $prop) {
            $attributes =  $prop->getAttributes(Property::class);
            $addToObjectName = $prop->getName();

            if(!$prop->isInitialized($object)) {
                continue;
            }
            $propertyValue = $prop->getValue($object);
            if (!$this->allowNulls && $propertyValue === null) {
                continue;
            }

            foreach ($attributes as $attrib) {
                if ($attrib->getName() === Property::class) {
                    foreach($attrib->getArguments() as $ag) {
                        $addToObjectName = $ag;
                    }
                }
            }

            $classObject->{$addToObjectName} = $propertyValue;
        }

        $flags = ($displayMode === JsonOutputStyle::Pretty) ? JSON_PRETTY_PRINT :  0;
        return json_encode($classObject,  $flags | JSON_BIGINT_AS_STRING);
    }
}
