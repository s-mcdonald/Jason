<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

use SamMcDonald\Jason\Exceptions\JsonLoadFileException;
use SamMcDonald\Jason\Loaders\LocalFileLoader;

class Jason
{
    /**
     * @deprecated - Next version Serialize will be lowercase 'S' for the method name.
     *               To prevent any issues you can now use 'Jason::toJsonString()`
     */
   public static function Serialize(JasonSerializable $object): string
   {
       $jsonSerializer = new JsonSerializer(true, false, true);
       return $jsonSerializer->serialize($object);
   }

    public static function toJsonString(JasonSerializable $object): string
    {
        return self::Serialize($object);
    }

    /**
     * @throws JsonLoadFileException
     */
    public static function fromFile(string|\Stringable $jsonFile): string
    {
        $fileLoader = new LocalFileLoader();
        $fileContents = $fileLoader->readJsonFile($jsonFile);
        return self::pretty($fileContents);
    }

    public static function pretty(string $jsonString): string
    {
        return json_encode(
            json_decode($jsonString)
            , JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
