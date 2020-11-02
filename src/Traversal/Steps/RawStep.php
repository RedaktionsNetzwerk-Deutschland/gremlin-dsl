<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

class RawStep implements StepInterface
{
    protected string $raw;

    public function __construct(string $raw)
    {
        $this->raw = $raw;
    }

    public function __toString(): string
    {
        return $this->raw;
    }
}
