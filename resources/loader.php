<?php

declare(strict_types=1);

if (!function_exists('gremlinLoadGlobalFunctions')) {
    function gremlinLoadGlobalFunctions(): void {
        require_once __DIR__ . '/traversal.php';

        if (file_exists(__DIR__ . '/predicates.php')) {
            require_once __DIR__ . '/predicates.php';
        }
        if (file_exists(__DIR__ . '/text_predicates.php')) {
            require_once __DIR__ . '/text_predicates.php';
        }
        if (file_exists(__DIR__ . '/statics.php')) {
            require_once __DIR__ . '/statics.php';
        }
    }
}

if (defined('GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS') && GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS) {
    gremlinLoadGlobalFunctions();
}
