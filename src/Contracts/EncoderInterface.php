<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Contracts;

interface EncoderInterface
{
    public function encode($value): ResultInterface;
}
