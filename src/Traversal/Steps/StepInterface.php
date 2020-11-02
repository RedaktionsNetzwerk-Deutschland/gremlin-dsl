<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

interface StepInterface
{
    public function __toString(): string;
}
