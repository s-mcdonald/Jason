<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Contracts;

interface ResultInterface
{
    public function getBody(): mixed;

    public function getMessage(): string;

    public function isValid(): bool;
}
