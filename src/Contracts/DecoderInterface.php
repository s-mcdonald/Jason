<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Contracts;

interface DecoderInterface
{
    public function decode($json): ResultInterface;
}
