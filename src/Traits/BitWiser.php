<?php

namespace SamMcDonald\Jason\Traits;

trait BitWiser
{
    private int $bitWiseFlags = 0;

    protected function isFlagSet($flag): bool
    {
        return (($this->bitWiseFlags & $flag) === $flag);
    }

    protected function setFlag($flag): void
    {
        $this->bitWiseFlags &= ~$flag;
    }

    protected function getFlags(): int
    {
        return $this->bitWiseFlags;
    }
}