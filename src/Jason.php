<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

use SamMcDonald\Jason\Assert\JsonAsserter;
use SamMcDonald\Jason\Exceptions\JsonLoadFileException;
use SamMcDonald\Jason\Loaders\LocalFileLoader;
use SamMcDonald\Jason\Validator\JsonValidator;

/**
 * @deprecasted Please use the Json class as this will be replaced in next major release.
 */
class Jason
{
    private const ASSOC_ARRAY = true;

    public static function serialize(JasonSerializable $object): string
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
        $fileContents = $fileLoader->readJsonFile((string) $jsonFile);
        return self::pretty($fileContents);
    }

    public static function fromUrl(string|\Stringable $url): string|false
    {
        $strUrl = (string) $url;
        if (!filter_var($strUrl, FILTER_VALIDATE_URL)) {
            return false;
        }

        ini_set("allow_url_fopen", '1');

        $json = file_get_contents($strUrl);

        return (JsonValidator::isValidJson($json)) ? self::pretty($json) : false;
    }

    public static function toArray(string $jsonString, ): array
    {
        JsonAsserter::assertStringIsValidJson($jsonString);

        return json_decode($jsonString, self::ASSOC_ARRAY);
    }

    public static function pretty(string $jsonString): string
    {
        JsonAsserter::assertStringIsValidJson($jsonString);

        return json_encode(
            json_decode($jsonString)
            , JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
