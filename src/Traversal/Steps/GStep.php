<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

class GStep extends BasicStep
{
    public const STEP_NAME = 'g';

    public function __toString()
    {
        return static::STEP_NAME;
    }
}
