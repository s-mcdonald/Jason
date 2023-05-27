<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Decoders\Result;

use SamMcDonald\Jason\Contracts\ResultInterface;

class ValidDecodeResult extends AbstractDecodeResult implements ResultInterface
{
    public function __construct(
        protected mixed $body,
    ) {
        parent::__construct($body, isValid: true);
    }
}
