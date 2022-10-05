<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Traversal;

interface SendClosureInterface
{
    public function __invoke(GraphTraversalInterface $graphTraversal, string $traversalString);
}
