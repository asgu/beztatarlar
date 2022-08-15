<?php


namespace App\Support;


use JsonException;
use RuntimeException;

class JsonSupport
{
    /**}
     * @param array $array
     * @param int|string $flags
     *
     * @return string
     * @throws JsonException
     */
    public static function encode(array $array, $flags = JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($array, $flags);
    }

    /**
     * @param $json
     * @param bool $asArray
     * @param int $depth
     * @param int $flags
     *
     * @return array|null
     * @throws JsonException
     */
    public static function decode($json, $asArray = true, int $depth = 512, $flags = JSON_THROW_ON_ERROR): ?array
    {
        if (is_array($json)) {
            throw new RuntimeException('Invalid JSON data.');
        }

        if ($json === null || $json === '') {
            return null;
        }

        return json_decode((string) $json, $asArray, $depth, $flags);
    }
}
