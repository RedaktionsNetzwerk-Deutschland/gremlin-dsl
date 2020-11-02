<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

use RND\GremlinDSL\Utils\ArgumentUtil;

abstract class BasicStep implements TraversalStepInterface
{
    public const STEP_NAME = 'undefined';

    private array $args;

    public function __construct(...$args)
    {
        $this->args = $args;
    }

    public function __toString(): string
    {
        return sprintf('%s(%s)', static::STEP_NAME, ArgumentUtil::implode($this->args));
    }
}
