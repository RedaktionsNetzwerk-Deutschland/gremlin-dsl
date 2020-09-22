<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Tests\Traversal;

use PHPUnit\Framework\TestCase;
use RND\GremlinDSL\Traversal\GraphTraversalSource;

class GraphTraversalSourceTest extends TestCase
{

    public function testConstruct()
    {
        $instance = new GraphTraversalSource();

        self::assertEmpty($instance->__toString());
    }

    public function testSourceSteps()
    {
        $instance = new GraphTraversalSource();
        $instance->addV('document')->property('id', '1234');

        self::assertSame('addV("document").property(id, "1234")', $instance->__toString());
    }
}
