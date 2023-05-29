<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Encoder;

use SamMcDonald\Jason\Contracts\EncoderInterface;
use SamMcDonald\Jason\Contracts\ResultInterface;
use SamMcDonald\Jason\Encoder\Result\InValidEncodeResult;
use SamMcDonald\Jason\Encoder\Result\ValidEncodeResult;
use SamMcDonald\Jason\Validator\JsonValidator;

class JsonEncoder implements EncoderInterface
{
    public function __construct(
        private int $depth = 512,
        private int $flags = 0
    ) {
    }

    public function encode($value): ResultInterface
    {
        $encoded = json_encode($value, $this->depth, $this->flags);
        if (JsonValidator::hasEncodeValidationError()) {
            return new InValidEncodeResult(json_last_error_msg());
        }

        return new ValidEncodeResult($encoded);
    }
}
