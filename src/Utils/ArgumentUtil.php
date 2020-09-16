<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Utils;

class ArgumentUtil
{

    public static function implode(array $args): string
    {
        $result = [];
        foreach ($args as $arg) {
            if ($arg === 'id' || $arg === 'T.id') {
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
