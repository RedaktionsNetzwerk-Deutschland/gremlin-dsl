<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

use RND\GremlinDSL\Traversal\Steps\AbstractStep;

class Steps implements \Iterator
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

    public function add(AbstractStep $step)
    {
        $this->steps[] = $step;
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone()
    {
    }

    /**
     * prevent from being unserialized (which would create a second instance of it)
     */
    private function __wakeup()
    {
    }

    public function current(): AbstractStep
    {
        return $this->steps[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->steps[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
