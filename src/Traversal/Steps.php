<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

use Iterator;
use RND\GremlinDSL\Traversal\Steps\AbstractStep;

class Steps implements Iterator
{
    /** @var AbstractStep[] */
    protected array $steps;

    private static ?Steps $instance = null;

    private int $position = 0;

    /**
     * Steps constructor.
     */
    private function __construct()
    {
        $this->steps = [];
    }

    public static function getInstance(): Steps
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function add(AbstractStep $step): self
    {
        $this->steps[] = $step;

        return $this;
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone()
    {
    }

    /**
     * prevent from being unserialized (which would create a second instance of it)
     * @noinspection PhpUnusedPrivateMethodInspection
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
