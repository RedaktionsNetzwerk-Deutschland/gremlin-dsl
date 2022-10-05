<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Tests\Traversal;

use PHPUnit\Framework\TestCase;
use SpecialWeb\GremlinDSL\Configuration;
use SpecialWeb\GremlinDSL\Exception\NoSendClosureException;
use SpecialWeb\GremlinDSL\Traversal\GraphTraversal;
use SpecialWeb\GremlinDSL\Traversal\Predicates\Gt;

class GraphTraversalTest extends TestCase
{

    public const DEFAULT_TRAVERSAL_STRING = 'g.V(1).out("knows").has("age", gt(30)).values("name")';

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

    public function testAnonymous()
    {
        $instance = GraphTraversal::__();

        self::assertEquals('__', $instance->__toString());
    }

    public function testTraversalWithAnonymous()
    {
        $instance = GraphTraversal::g();

        $instance->V()->repeat(GraphTraversal::__()->out('edgeType'))->until(GraphTraversal::__()->hasLabel('label'));
        self::assertEquals('g.V().repeat(__.out("edgeType")).until(__.hasLabel("label"))', $instance->__toString());
    }

    public function testSteps()
    {
        self::assertEquals(self::DEFAULT_TRAVERSAL_STRING, $this->traversalInstance()->__toString());
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

        self::assertEquals('Result from configured closure:' . self::DEFAULT_TRAVERSAL_STRING, $result['string']);
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

        self::assertEquals('Result from provided closure:' . self::DEFAULT_TRAVERSAL_STRING, $result['string']);
        self::assertInstanceOf(GraphTraversal::class, $result['instance']);
    }

    public function testSendWithSendClosureInterface()
    {
        $this->resetConfiguration();
        $sendClosure = new SendClosure();
        $result = $this->traversalInstance()->send($sendClosure);

        self::assertEquals($sendClosure::PREFIX . self::DEFAULT_TRAVERSAL_STRING, $result);
    }

    public function testRaw()
    {
        $result = (new GraphTraversal())->raw('foo = bar.baz');

        self::assertEquals('foo = bar.baz', $result->__toString());
    }

    public function testAssign()
    {
        $result = $this->traversalInstance()->assign('foo');

        self::assertEquals('foo = ' . self::DEFAULT_TRAVERSAL_STRING, $result->__toString());
    }

    public function traversalInstance(): GraphTraversal
    {
        return $this->appendDemoTraversal(GraphTraversal::g());
    }

    public function appendDemoTraversal(GraphTraversal $traversal): GraphTraversal
    {
        return $traversal
            ->V(1)
            ->out('knows')
            ->has('age', new Gt(30))
            ->values('name');
    }

}
