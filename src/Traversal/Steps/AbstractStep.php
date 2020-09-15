<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

use RND\GremlinDSL\Utils\ArgumentUtil;

abstract class AbstractStep
{
    public const STEP_NAME = 'undefined';

    private array $args;

    public function __construct(...$args)
    {
        $this->args = $args;
    }

    public function __toString()
    {
        $args = [];
        foreach ($this->args as $arg) {
            if (is_string($arg)) {
                $args[] = $arg;
            }
        }

        return sprintf('%s(%s)', static::STEP_NAME, ArgumentUtil::implode($args));
    }
}
