<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

use SamMcDonald\Jason\Attributes\JsonObjectAsProperty;
use Stringable;

#[JsonObjectAsProperty]
class JsonSerializableEntity implements JsonSerializable, Stringable
{
    public function __toString(): string
    {
        return json_encode(
            $this,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
