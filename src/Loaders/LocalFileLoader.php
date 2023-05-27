<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Loaders;

use SamMcDonald\Jason\Exceptions\JsonLoadFileException;

class LocalFileLoader
{
    private function __construct() {}

    public static function load(string $fileName): string
    {
        if (!file_exists($fileName)) {
            throw new JsonLoadFileException(
                sprintf(
                    'The file %s does not exist or can not be found.',
                    $fileName
                )
            );
        }

        return file_get_contents($fileName);
    }
}
