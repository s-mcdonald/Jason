<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Decoders;

use SamMcDonald\Jason\Contracts\DecoderInterface;
use SamMcDonald\Jason\Contracts\ResultInterface;
use SamMcDonald\Jason\Decoders\Result\ValidDecodeResult;

class ByPassDecoder implements DecoderInterface
{
    public function decode($jsonValue): ResultInterface
    {
        return new ValidDecodeResult($jsonValue);
    }
}
