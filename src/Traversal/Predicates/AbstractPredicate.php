<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal\Predicates;

use RND\GremlinDSL\Utils\ArgumentUtil;

abstract class AbstractPredicate implements PredicateInterface
{

    private array $args;

    public function __construct(... $args)
    {
        $this->args = $args;
    }

    public function __toString()
    {
        return sprintf('%s(%s)', static::STEP_NAME , ArgumentUtil::implode($this->args));
    }
}
