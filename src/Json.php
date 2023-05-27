<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

use SamMcDonald\Jason\Assert\JsonAsserter;
use SamMcDonald\Jason\Decoders\JsonDecoder;
use SamMcDonald\Jason\Exceptions\InvalidPropertyException;
use SamMcDonald\Jason\Exceptions\JsonLoadFileException;
use SamMcDonald\Jason\Exceptions\NotSerializableException;
use SamMcDonald\Jason\Loaders\LocalFileLoader;
use SamMcDonald\Jason\Loaders\UrlLoader;
use Stringable;

final class Json implements JsonSerializable, Stringable
{
    /**
     * @throws NotSerializableException
     */
    public static function createFromArray(array $array): self
    {
        $isSerializable = static function () use ($array) {
            $return = true;
            $arr = [$array];
            array_walk_recursive($arr, function ($element) use (&$return) {
                if (is_object($element) && get_class($element) === 'Closure') {
                    $return = false;
                }
            });
            return $return;
        };

        $isSerializable() ?? throw new NotSerializableException();
        return new self($array);
    }

    public static function createFromUrl(string $url): self
    {
        return new self(self::convertJsonToArray(UrlLoader::load($url)));
    }

    /**
     * @throws JsonLoadFileException
     */
    public static function createFromFile(string $fileName): self
    {
        return new self(self::convertJsonToArray(LocalFileLoader::load($fileName)));
    }

    public static function createFromStringable(string|Stringable $jsonValue): self
    {
        return new self(self::convertJsonToArray((string) $jsonValue));
    }

    public static function mergeCombine(string ...$jsonValue): self
    {
        $v = [...$jsonValue];

        $merged = static function () use ($v) {
            $container = [];
            foreach ($v as $i) {
                $container[] = self::createFromStringable($i)->toArray();
            };
            return $container;
        };

        return new self(array_merge(...$merged()));
    }

    public static function serialize(JsonSerializable $object): string
    {
        $jsonSerializer = new JsonSerializer(true, false, true);
        return $jsonSerializer->serialize($object);
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

    public function toPretty(): string
    {
        return json_encode(
            $this->jsonCache,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    public function toCompressed(): string
    {
        return json_encode(
            $this->toObject(),
            JSON_UNESCAPED_SLASHES
        );
    }

    public function toString(): string
    {
        return json_encode(
            $this->jsonCache,
            JSON_UNESCAPED_SLASHES
        );
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toArray(): array
    {
        return $this->jsonCache;
    }

    public function toObject(): JsonSerializable
    {
        return self::convertFromJsonToObject($this->toPretty());
    }

    /**
     * @throws InvalidPropertyException
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->jsonCache)) {
            return $this->jsonCache[$key];
        }
        throw new InvalidPropertyException();
    }

    public function __set(int|string $key, mixed $value)
    {
        $this->jsonCache[$key] = $value;
    }

    private function __construct(
        private array $jsonCache
    ) {
    }
}
