<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

class AssignStep implements StepInterface
{
    protected string $assignment;

    public function __construct(string $assignment)
    {
        $this->assignment = $assignment;
    }

    public function __toString()
    {
        return sprintf('%s = ', $this->assignment);
    }
}
