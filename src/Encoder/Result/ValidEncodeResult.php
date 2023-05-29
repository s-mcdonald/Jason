<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Encoder\Result;

use SamMcDonald\Jason\Contracts\ResultInterface;

class ValidEncodeResult extends AbstractEncodeResult implements ResultInterface
{
    public function __construct(
        protected mixed $body,
    ) {
        parent::__construct($body, isValid: true);
    }
}
