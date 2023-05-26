<?php

declare(strict_types=1);

namespace SamMcDonald\Jason\Loaders;

use SamMcDonald\Jason\Exceptions\JsonLoadFileException;

class LocalFileLoader
{
    public function __construct()
    {
    }

    /**
     * @throws JsonLoadFileException
     */
    public function readJsonFile(string $pathToFile): string
    {
        if (!file_exists($pathToFile)) {
            throw new JsonLoadFileException(
                sprintf(
                    'The file %s does not exist or can not be found.',
                    $pathToFile
                )
            );
        }

        return file_get_contents($pathToFile);
    }
}