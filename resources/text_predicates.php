<?php

/**
 * This is an autogenerated file. Changes will be lost on next generation.
 */

declare(strict_types=1);

use RND\GremlinDSL\Traversal\Predicates\Text\Containing;
use RND\GremlinDSL\Traversal\Predicates\Text\EndingWith;
use RND\GremlinDSL\Traversal\Predicates\Text\NotContaining;
use RND\GremlinDSL\Traversal\Predicates\Text\NotEndingWith;
use RND\GremlinDSL\Traversal\Predicates\Text\NotRegex;
use RND\GremlinDSL\Traversal\Predicates\Text\NotStartingWith;
use RND\GremlinDSL\Traversal\Predicates\Text\Regex;
use RND\GremlinDSL\Traversal\Predicates\Text\StartingWith;

if (!function_exists('containing')) {
    function containing(...$args): Containing
    {
        return new Containing(...$args);
    }
}

if (!function_exists('endingWith')) {
    function endingWith(...$args): EndingWith
    {
        return new EndingWith(...$args);
    }
}

if (!function_exists('notContaining')) {
    function notContaining(...$args): NotContaining
    {
        return new NotContaining(...$args);
    }
}

if (!function_exists('notEndingWith')) {
    function notEndingWith(...$args): NotEndingWith
    {
        return new NotEndingWith(...$args);
    }
}

if (!function_exists('notRegex')) {
    function notRegex(...$args): NotRegex
    {
        return new NotRegex(...$args);
    }
}

if (!function_exists('notStartingWith')) {
    function notStartingWith(...$args): NotStartingWith
    {
        return new NotStartingWith(...$args);
    }
}

if (!function_exists('regex')) {
    function regex(...$args): Regex
    {
        return new Regex(...$args);
    }
}

if (!function_exists('startingWith')) {
    function startingWith(...$args): StartingWith
    {
        return new StartingWith(...$args);
    }
}
