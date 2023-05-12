<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Enums;

enum JsonOutputStyle: string
{
    case Compressed = 'compressed';

    case Pretty = 'pretty';
}