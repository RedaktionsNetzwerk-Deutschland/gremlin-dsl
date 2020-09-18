<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Utils;

class ArgumentUtil
{

    public const RESERVED_KEYWORDS = ['id', 'T.id'];

    public static function implode(array $args): string
    {
        $result = [];
        foreach ($args as $arg) {
            if (in_array($arg, static::RESERVED_KEYWORDS, true)) {
                $result[] = $arg;
            } elseif (is_string($arg)) {
                $result[] = sprintf('"%s"', $arg);
            } else {
                $result[] = sprintf('%s', $arg);
            }
        }

        return implode(', ', $result);
    }
}
