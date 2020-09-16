<?php

/**
 * This is an autogenerated file. Changes will be lost on next generation.
 */

declare(strict_types=1);

use RND\GremlinDSL\Traversal\Predicates\Between;
use RND\GremlinDSL\Traversal\Predicates\Eq;
use RND\GremlinDSL\Traversal\Predicates\Gt;
use RND\GremlinDSL\Traversal\Predicates\Gte;
use RND\GremlinDSL\Traversal\Predicates\Inside;
use RND\GremlinDSL\Traversal\Predicates\Lt;
use RND\GremlinDSL\Traversal\Predicates\Lte;
use RND\GremlinDSL\Traversal\Predicates\Neq;
use RND\GremlinDSL\Traversal\Predicates\Not;
use RND\GremlinDSL\Traversal\Predicates\Outside;
use RND\GremlinDSL\Traversal\Predicates\Test;
use RND\GremlinDSL\Traversal\Predicates\Within;
use RND\GremlinDSL\Traversal\Predicates\Without;

if (!function_exists('between')) {
    function between(...$args): Between
    {
        return new Between(...$args);
    }
}

if (!function_exists('eq')) {
    function eq(...$args): Eq
    {
        return new Eq(...$args);
    }
}

if (!function_exists('gt')) {
    function gt(...$args): Gt
    {
        return new Gt(...$args);
    }
}

if (!function_exists('gte')) {
    function gte(...$args): Gte
    {
        return new Gte(...$args);
    }
}

if (!function_exists('inside')) {
    function inside(...$args): Inside
    {
        return new Inside(...$args);
    }
}

if (!function_exists('lt')) {
    function lt(...$args): Lt
    {
        return new Lt(...$args);
    }
}

if (!function_exists('lte')) {
    function lte(...$args): Lte
    {
        return new Lte(...$args);
    }
}

if (!function_exists('neq')) {
    function neq(...$args): Neq
    {
        return new Neq(...$args);
    }
}

if (!function_exists('not')) {
    function not(...$args): Not
    {
        return new Not(...$args);
    }
}

if (!function_exists('outside')) {
    function outside(...$args): Outside
    {
        return new Outside(...$args);
    }
}

if (!function_exists('test')) {
    function test(...$args): Test
    {
        return new Test(...$args);
    }
}

if (!function_exists('within')) {
    function within(...$args): Within
    {
        return new Within(...$args);
    }
}

if (!function_exists('without')) {
    function without(...$args): Without
    {
        return new Without(...$args);
    }
}