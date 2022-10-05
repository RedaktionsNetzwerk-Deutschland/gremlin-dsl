<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Traversal;

use Closure;
use SpecialWeb\GremlinDSL\Configuration;
use SpecialWeb\GremlinDSL\Exception\NoSendClosureException;
use SpecialWeb\GremlinDSL\Traversal\Steps\AnonymousStep;
use SpecialWeb\GremlinDSL\Traversal\Steps\AssignStep;
use SpecialWeb\GremlinDSL\Traversal\Steps\GStep;
use SpecialWeb\GremlinDSL\Traversal\Steps\NextStep;
use SpecialWeb\GremlinDSL\Traversal\Steps\RawStep;
use SpecialWeb\GremlinDSL\Traversal\Steps\TraversalStepInterface;

class AbstractGraphTraversal implements GraphTraversalInterface
{
    protected Steps $steps;

    public function __construct(?Steps $steps = null)
    {
        $this->steps = $steps ?? Steps::create();
    }

    /**
     * Convert the current graph traversal to string
     *
     * @return string
     */
    public function __toString(): string
    {
        $steps = '';
        $previousStep = null;
        foreach ($this->steps as $index => $step) {
            if (
                $step instanceof TraversalStepInterface
                && $index !== 0
                && !$previousStep instanceof RawStep
                && !$previousStep instanceof AssignStep
            ) {
                $steps .= '.';
            }
            $previousStep = $step;
            $steps .= $step->__toString();
        }

        return $steps;
    }

    /**
     * @return static
     */
    public static function g(): self
    {
        $instance = new static();
        $instance->steps->add(new GStep());

        return $instance;
    }

    /**
     * @return static
     */
    public static function __(): self
    {
        $instance = new static();
        $instance->steps->add(new AnonymousStep());

        return $instance;
    }

    /**
     * @param string $raw
     * @return $this
     */
    public function raw(string $raw): self
    {
        $this->steps->prepend(new RawStep($raw));

        return $this;
    }

    /**
     * @param string $assignment
     * @return $this
     */
    public function assign(string $assignment): self
    {
        $this->steps->prepend(new AssignStep($assignment));

        return $this;
    }

    /**
     * @param mixed ...$args
     * @return $this
     */
    public function next(...$args): self
    {
        $this->steps->add(new NextStep(...$args));

        return $this;
    }

    /**
     * Example usage with provided closure:
     * ```php
     *   g()->send(function (string $traversalString) {});
     * ```
     *
     * Example usage with configured closure:
     * ```php
     *   Configuration::getInstance()->setSendClosure(function (string $traversalString) {})
     *   g()->send();
     * ```
     *
     * @param SendClosureInterface|Closure|null $closure The function handling the send step.
     *                                                   If not provided the configured
     *                                                   `Configuration::setSendClosure()` one will be used.
     *                                                   The context of the closure will be the current GraphTraversal.
     *                                                   The first parameter is the compiled traversal string.
     *                                                   Example closure:
     *                                                   <code>
     * function (string $traversalString) use ($connection) { return $connection->send($traversalString); }
     *                                                   </code>
     * @return mixed the result
     * @see Configuration::setSendClosure()
     */
    public function send($closure = null)
    {
        $closure = $closure ?? Configuration::getInstance()->getSendClosure();
        if (!$closure) {
            throw new NoSendClosureException();
        }

        return $closure instanceof SendClosureInterface
            ? $closure($this, $this->__toString())
            : $closure->call($this, $this->__toString());
    }
}
