<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Encoder\Result;

use SamMcDonald\Jason\Contracts\ResultInterface;

class InValidEncodeResult extends AbstractEncodeResult implements ResultInterface
{
    public function __construct(
        protected string $message,
    ) {
        parent::__construct('', message: $message);
    }
}
