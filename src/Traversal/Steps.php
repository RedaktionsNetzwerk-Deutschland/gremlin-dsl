<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

use RND\GremlinDSL\Traversal\Steps\AbstractStep;

class Steps
{
    /** @var AbstractStep[] */
    protected array $steps;

    /**
     * Steps constructor.
     */
    public function __construct()
    {
        $this->steps = [];
    }

    public function add(AbstractStep $step)
    {
        $this->steps[] = $step;
    }

}
