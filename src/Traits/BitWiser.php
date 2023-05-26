<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Traits;

trait BitWiser
{
    private int $bitWiseFlags = 0;

    protected function isFlagSet(int $flag): bool
    {
        return (($this->bitWiseFlags & $flag) === $flag);
    }

    protected function setFlag(int $flag): void
    {
        $this->bitWiseFlags &= ~$flag;
    }

    protected function getFlags(): int
    {
        return $this->bitWiseFlags;
    }
}