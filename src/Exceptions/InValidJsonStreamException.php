<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Exceptions;

class InValidJsonStreamException extends \Exception
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}
