<?php

declare(strict_types=1);

namespace SamMcDonald\Jason;

class Jason
{
   public static function Serialize(JasonSerializable $object): string
   {
       $jsonSerializer = new JsonSerializer(true, false, true);
       return $jsonSerializer->serialize($object);
   }

    public static function pretty(string $jsonString): string
    {
        return json_encode(
            json_decode($jsonString)
            , JSON_PRETTY_PRINT
        );
    }
}
