<?php

declare(strict_types=1);

namespace Traversal;

use RND\GremlinDSL\Traversal\Steps;
use PHPUnit\Framework\TestCase;

class StepsTest extends TestCase
{
    public function testShouldNotWakeup()
    {
        $steps = new Steps();
        $serialized = serialize($steps);

        $this->expectError();
        unserialize($serialized);
    }

    public function testShouldNotBeCloned()
    {
        $steps = new Steps();
        $this->expectException(\Error::class);
        $clone = clone $steps;
    }

    public function testSteps()
    {
        $steps = new Steps();
        $nextStep = new Steps\NextStep();
        $steps->add($nextStep);
        self::assertCount(1, $steps);
        self::assertSame($nextStep, $steps->current());

        $anotherNextStep = new Steps\NextStep();
        $steps->add($anotherNextStep);
        self::assertCount(2, $steps);

        $steps->clear();
        self::assertCount(0, $steps);
    }
}
