<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Tests\Traversal;

use SpecialWeb\GremlinDSL\Traversal\GraphTraversalInterface;
use SpecialWeb\GremlinDSL\Traversal\SendClosureInterface;

class SendClosure implements SendClosureInterface
{
    public const PREFIX = 'From SendClosure ';

    public function __invoke(GraphTraversalInterface $graphTraversal, string $traversalString)
    {
        return self::PREFIX . $traversalString;
    }
}
