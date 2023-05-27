<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class JsonObjectAsProperty extends Property
{
}
