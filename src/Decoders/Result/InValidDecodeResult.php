<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Decoders\Result;

use SamMcDonald\Jason\Contracts\ResultInterface;

class InValidDecodeResult extends AbstractDecodeResult implements ResultInterface
{
    public function __construct(
        protected string $message,
    ) {
        parent::__construct('', message: $message);
    }
}
