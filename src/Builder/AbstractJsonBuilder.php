<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Builder;

use SamMcDonald\Jason\Exceptions\JsonRuntimeException;
use SamMcDonald\Jason\Json;
use SamMcDonald\Jason\JsonSerializable;

abstract class AbstractJsonBuilder implements JsonSerializable
{
    public function addObjectProperty(string $prop, AbstractJsonBuilder $value): self
    {
        return $this->addProperty($prop,  $value);
    }

    public function addArrayProperty(string $prop, array $value): self
    {
        return $this->addProperty($prop,  $value);
    }

    public function addStringProperty(string $prop, string $value): self
    {
        return $this->addProperty($prop,  $value);
    }

    public function addBooleanProperty(string $prop, bool $value): self
    {
        return $this->addProperty($prop,  $value);
    }

    public function addNumericProperty(string $prop, int|float $value): self
    {
        return $this->addProperty($prop,  $value);
    }

    public function addNullProperty(string $prop): self
    {
        return $this->addProperty($prop);
    }

    /**
     * @throws JsonRuntimeException
     */
    protected function addProperty(string $prop, $value = null): self
    {
        $this->validatePropertyName($prop) ?? throw new JsonRuntimeException('Unable to add property $prop');
        $this->{$prop} = $value;
        return $this;
    }

    protected function validatePropertyName(string $prop): bool
    {
        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9-_]*$/', $prop))
        {
            trigger_error(
                "Invalid json property name"
            );
            return false;
        }

        return true;
    }

    public function __toString(): string
    {
        return Json::createFromStringable(json_encode($this))->toPretty();
    }
}
