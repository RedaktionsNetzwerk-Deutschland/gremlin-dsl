<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Traversal\Steps;

interface StepInterface
{
    public function __toString(): string;
}
