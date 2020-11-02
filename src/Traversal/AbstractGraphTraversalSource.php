<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

/**
 * @see https://tinkerpop.apache.org/docs/current/reference/#start-steps
 */
class AbstractGraphTraversalSource implements GraphTraversalInterface
{

    protected Steps $steps;

    public function __construct(?Steps $steps = null)
    {
        $this->steps = $steps ?? Steps::create();
    }

    public function __toString(): string
    {
        $steps = [];
        foreach ($this->steps as $step) {
            $steps[] = $step->__toString();
        }

        return implode('.', $steps);
    }
}
