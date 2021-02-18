<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Tests\Traversal;

use RND\GremlinDSL\Traversal\Steps;
use PHPUnit\Framework\TestCase;

class StepsTest extends TestCase
{
    public function testSteps()
    {
        $steps = new Steps();
        $nextStep = new Steps\NextStep('first');
        $steps->add($nextStep);
        self::assertCount(1, $steps);
        self::assertSame($nextStep, $steps->current());

        $anotherNextStep = new Steps\NextStep('second');
        $steps->add($anotherNextStep);
        self::assertCount(2, $steps);

        $prependedStep = new Steps\NextStep('new first');
        $steps->prepend($prependedStep);
        self::assertCount(3, $steps);
        self::assertEquals($prependedStep, $steps->current());

        $steps->clear();
        self::assertCount(0, $steps);
    }
}
