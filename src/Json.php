<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

use SamMcDonald\Jason\Assert\JsonAsserter;
use SamMcDonald\Jason\Decoders\JsonDecoder;
use SamMcDonald\Jason\Exceptions\InvalidPropertyException;
use SamMcDonald\Jason\Exceptions\JsonLoadFileException;
use SamMcDonald\Jason\Loaders\LocalFileLoader;
use SamMcDonald\Jason\Validator\JsonValidator;
use Stringable;

final class Json implements JsonSerializable, Stringable
{
    private function __construct(
        private array $jsonCache
    ) {
    }

    public static function createFromUrl(string $url): self
    {
        return new self(self::convertJsonToArray(self::fromUrl($url)));
    }

    public static function createFromFile(string $fileName): self
    {
        return new self(self::convertJsonToArray(self::fromFile($fileName)));
    }

    public static function createFromStringable(string|Stringable $jsonValue): self
    {
        return new self(self::convertJsonToArray(self::fromString((string) $jsonValue)));
    }

    public static function serialize(JsonSerializable $object): string
    {
        $jsonSerializer = new JsonSerializer(true, false, true);
        return $jsonSerializer->serialize($object);
    }

    public static function toJsonString(JsonSerializable $object): string
    {
        return self::serialize($object);
    }

    /**
     * @throws JsonLoadFileException
     * @deprecated - This will be changed to fetchFromFile
     */
    public static function fromFile(string|\Stringable $jsonFile): string
    {
        $fileLoader = new LocalFileLoader();
        $fileContents = $fileLoader->readJsonFile((string) $jsonFile);
        return self::pretty($fileContents);
    }

    /**
     *  Strange function, string to string conversion.
     *  However, this will validate or throw exception if not valid.
     */
    public static function fromString(string|\Stringable $jsonString): string
    {
        JsonAsserter::assertStringIsValidJson($jsonString);

        return self::pretty($jsonString);
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

    public static function convertJsonToArray(string $jsonString, int $depth = 512): array
    {
        JsonAsserter::assertStringIsValidJson($jsonString);
        $decoder = new JsonDecoder(true, depth: $depth);
        return ($decoder->decode($jsonString))->getBody();
    }

    public static function convertFromJsonToObject(
        string $jsonString,
        int $depth = 512): JsonSerializable
    {
        $array = self::convertJsonToArray($jsonString, $depth);

        $obj = new JsonSerializableEntity();

        foreach ($array as $key => $value) {
            $obj->{$key} = $value;
        }

        return $obj;
    }

    public static function pretty(string $jsonString): string
    {
        JsonAsserter::assertStringIsValidJson($jsonString);
        return json_encode(
            ((new JsonDecoder())->decode($jsonString))->getBody(),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
