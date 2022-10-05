<?php

declare(strict_types=1);

use SpecialWeb\GremlinDSL\Traversal\GraphTraversal;

if (!function_exists('g')) {
    function g(): GraphTraversal
    {
        return GraphTraversal::g();
    }
}

if (!function_exists('__')) {
    function __(): GraphTraversal
    {
        return GraphTraversal::__();
    }
}
