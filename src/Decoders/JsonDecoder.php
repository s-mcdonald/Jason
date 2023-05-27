<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Decoders;

use SamMcDonald\Jason\Contracts\DecoderInterface;
use SamMcDonald\Jason\Contracts\ResultInterface;
use SamMcDonald\Jason\Decoders\Result\InValidDecodeResult;
use SamMcDonald\Jason\Decoders\Result\ValidDecodeResult;
use SamMcDonald\Jason\Validator\JsonValidator;

class JsonDecoder implements DecoderInterface
{
    public function __construct(
        private bool $associative = false,
        private int $depth = 512,
        private int $flags = 0
    ) {
    }

    public function decode($jsonValue): ResultInterface
    {
        $decoded = json_decode($jsonValue, $this->associative, $this->depth, $this->flags);
        if (JsonValidator::hasDecodeValidationError()) {
            return new InValidDecodeResult(json_last_error_msg());
        }

        return new ValidDecodeResult($decoded);
    }
}
