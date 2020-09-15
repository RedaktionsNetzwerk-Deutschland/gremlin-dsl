<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Steps;

abstract class AbstractStep
{

    private array $args;

    public function __construct(...$args)
    {
        $this->args = $args;
    }

}
