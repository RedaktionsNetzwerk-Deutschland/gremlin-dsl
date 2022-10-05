<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Traversal;

use Iterator;
use SpecialWeb\GremlinDSL\Traversal\Steps\StepInterface;

class Steps implements Iterator
{
    /** @var StepInterface[] */
    protected array $steps;

    private int $position = 0;

    /**
     * Steps constructor.
     */
    public function __construct()
    {
        $this->clear();
    }

    public static function create(): Steps
    {
        return new static();
    }

    public function add(StepInterface $step): self
    {
        $this->steps[] = $step;

        return $this;
    }

    public function prepend(StepInterface $step): self
    {
        array_unshift($this->steps, $step);

        return $this;
    }

    public function clear(): void
    {
        $this->steps = [];
        $this->position = 0;
    }

    public function current(): StepInterface
    {
        return $this->steps[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->steps[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
