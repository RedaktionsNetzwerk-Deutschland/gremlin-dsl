<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Tests\Traversal;

use RND\GremlinDSL\Traversal\GraphTraversalInterface;
use RND\GremlinDSL\Traversal\SendClosureInterface;

class SendClosure implements SendClosureInterface
{
    public const PREFIX = 'From SendClosure ';

    public function __invoke(GraphTraversalInterface $graphTraversal, string $traversalString)
    {
        return self::PREFIX . $traversalString;
    }
}
