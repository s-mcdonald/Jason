<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Attributes;

use Attribute;

#[Attribute]
class Property
{
    public function __construct(private ?string $name = null)
    {
    }
}
