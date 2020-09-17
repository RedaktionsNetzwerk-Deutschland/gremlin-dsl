<?php

declare(strict_types=1);

if (defined('GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS') && GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS) {
    require_once __DIR__ . '/traversal.php';

    if (file_exists(__DIR__ . '/predicates.php')) {
        require_once __DIR__ . '/predicates.php';
    }
    if (file_exists(__DIR__ . '/text_predicates.php')) {
        require_once __DIR__ . '/text_predicates.php';
    }
}
