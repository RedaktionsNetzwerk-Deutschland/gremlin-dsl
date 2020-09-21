<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

interface SendClosureInterface
{
    public function __invoke(GraphTraversalInterface $graphTraversal, string $traversalString);
}
