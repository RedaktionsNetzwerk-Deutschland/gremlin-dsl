<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Utils;

class ArgumentUtil
{
    public const RESERVED_KEYWORDS = ['id', 'T.id'];

    public static function implode(array $args): string
    {
        $result = [];
        foreach ($args as $arg) {
            if (is_string($arg)) {
                if (in_array($arg, static::RESERVED_KEYWORDS, true)) {
                    $result[] = $arg;
                } elseif (strpos($arg, 'UUID.') === 0) {
                    $result[] = $arg;
                } else {
                    $result[] = sprintf('"%s"', $arg);
                }
            } else {
                $result[] = sprintf('%s', $arg);
            }
        }

        return implode(', ', $result);
    }
}
