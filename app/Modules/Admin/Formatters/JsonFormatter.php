<?php

namespace Modules\Admin\Formatters;

use Netibackend\Laravel\Support\JsonSupport;

class JsonFormatter
{
    public static function unescapedUnicode(string $json): string
    {
        return JsonSupport::encode(JsonSupport::decode($json));
    }
}
