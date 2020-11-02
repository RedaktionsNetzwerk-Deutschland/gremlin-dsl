<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

class AnonymousStep extends BasicStep
{
    public const STEP_NAME = '__';

    public function __toString(): string
    {
        return static::STEP_NAME;
    }
}
