<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

class AbstractGraphTraversal
{

    protected Steps $steps;

    public function __construct()
    {
        $this->steps = Steps::getInstance();
    }

    public function __toString()
    {
        $steps = [];
        foreach ($this->steps as $step) {
            $steps[] = $step->__toString();
        }

        return implode('.', $steps);
    }
}
