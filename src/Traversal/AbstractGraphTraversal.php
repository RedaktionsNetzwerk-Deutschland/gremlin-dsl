<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

use RND\GremlinDSL\Traversal\Steps\GStep;

class AbstractGraphTraversal implements GraphTraversalInterface
{

    protected Steps $steps;

    public function __construct(?Steps $steps = null)
    {
        $this->steps = $steps ?? Steps::create();
    }

    public function __toString()
    {
        $steps = [];
        foreach ($this->steps as $step) {
            $steps[] = $step->__toString();
        }

        return implode('.', $steps);
    }

    public static function g(): self
    {
        $instance = new static();
        $instance->steps->add(new GStep());

        return $instance;
    }
}
