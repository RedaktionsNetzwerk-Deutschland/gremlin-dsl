<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Traversal\Steps;

class AssignStep implements StepInterface
{
    protected string $assignment;

    public function __construct(string $assignment)
    {
        $this->assignment = $assignment;
    }

    public function __toString(): string
    {
        return sprintf('%s = ', $this->assignment);
    }
}
