<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

use Iterator;
use RND\GremlinDSL\Traversal\Steps\AbstractStep;

class Steps implements Iterator
{
    /** @var AbstractStep[] */
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

    public function add(AbstractStep $step): self
    {
        $this->steps[] = $step;

        return $this;
    }

    public function clear()
    {
        $this->steps = [];
        $this->position = 0;
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    /**
     * prevent from being unserialized (which would create a second instance of it)
     * @noinspection PhpUnusedPrivateMethodInspection
     * @codeCoverageIgnore
     */
    private function __wakeup()
    {
    }

    public function current(): AbstractStep
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
