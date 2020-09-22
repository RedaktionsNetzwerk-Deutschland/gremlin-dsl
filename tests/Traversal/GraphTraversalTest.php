<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Tests\Traversal;

use PHPUnit\Framework\TestCase;
use RND\GremlinDSL\Configuration;
use RND\GremlinDSL\Exception\NoSendClosureException;
use RND\GremlinDSL\Traversal\GraphTraversal;
use RND\GremlinDSL\Traversal\Predicates\Gt;

class GraphTraversalTest extends TestCase
{

    public function resetConfiguration()
    {
        $instanceProperty = new \ReflectionProperty(Configuration::class, 'instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null);
    }

    public function testConstruct()
    {
        $instance = new GraphTraversal();

        self::assertEmpty($instance->__toString());
    }

    public function testG()
    {
        $instance = GraphTraversal::g();

        self::assertEquals('g', $instance->__toString());
    }

    public function testSteps()
    {
        self::assertEquals($this->traversalString(), $this->traversalInstance()->__toString());
    }

    public function testNext()
    {
        self::assertEquals(GraphTraversal::g()->next('foo'), 'g.next("foo")');
    }

    public function testSendUnconfigured()
    {
        $this->resetConfiguration();
        $this->expectException(NoSendClosureException::class);
        $this->traversalInstance()->send();
    }

    public function testSendConfigured()
    {
        $this->resetConfiguration();
        $closure = fn(string $traversalString) => [
            'string' => 'Result from configured closure:' . $traversalString,
            'instance' => $this,
        ];
        Configuration::fromConfig(
            [
                'sendClosure' => $closure,
            ]
        );
        $result = $this->traversalInstance()->send();

        self::assertEquals('Result from configured closure:' . $this->traversalString(), $result['string']);
        self::assertInstanceOf(GraphTraversal::class, $result['instance']);
    }

    public function testSendProvided()
    {
        $this->resetConfiguration();
        $closure = fn(string $traversalString) => [
            'string' => 'Result from provided closure:' . $traversalString,
            'instance' => $this,
        ];
        $result = $this->traversalInstance()->send($closure);

        self::assertEquals('Result from provided closure:' . $this->traversalString(), $result['string']);
        self::assertInstanceOf(GraphTraversal::class, $result['instance']);
    }

    public function traversalInstance(): GraphTraversal
    {
        return GraphTraversal::g()
                             ->V(1)
                             ->out('knows')
                             ->has('age', new Gt(30))
                             ->values('name');
    }

    public function traversalString(): string
    {
        return 'g.V(1).out("knows").has("age", gt(30)).values("name")';
    }

}
