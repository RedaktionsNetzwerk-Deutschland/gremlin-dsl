<?php

declare(strict_types=1);

use RND\GremlinDSL\Traversal\GraphTraversal;

if (defined('GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS') && GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS) {
    if (!function_exists('g')) {
        function g()
        {
            return GraphTraversal::g();
        }
    }
    if (!function_exists('__')) {
        function __()
        {
            return new GraphTraversal();
        }
    }

    if (file_exists(__DIR__ . '/predicates.php')) {
        require_once __DIR__ . '/predicates.php';
    }
    if (file_exists(__DIR__ . '/text_predicates.php')) {
        require_once __DIR__ . '/text_predicates.php';
    }
}
