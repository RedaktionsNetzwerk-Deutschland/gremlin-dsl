<?php

declare(strict_types=1);

use RND\GremlinDSL\Traversal\GraphTraversal;

if (!function_exists('g')) {
    function g()
    {
        return GraphTraversal::g();
    }
}

