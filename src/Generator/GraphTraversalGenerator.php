<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use RND\GremlinDSL\Traversal\AbstractGraphTraversal;
use RND\GremlinDSL\Traversal\Steps\AbstractStep;

class GraphTraversalGenerator extends AbstractGraphTraversalGenerator
{
    protected const CLASS_NAME = 'GraphTraversal';
    protected const CLASS_PATH = 'RND\\GremlinDSL\\Traversal';
    protected const ABSTRACT_CLASS = AbstractGraphTraversal::class;
    protected const ABSTRACT_STEP_CLASS = AbstractStep::class;

    private array $methods;

    public function __construct(FileWriter $writer, array $methods)
    {
        $this->methods = $methods;

        parent::__construct($writer);
    }

    public function generate()
    {
        $this->generateSourceClass();

        $this->methods = $this->groupMethods($this->methods);

        foreach ($this->methods as $methodName => $methodDefinition) {
            $this->generateClassAndMethod(
                $methodName,
                $methodDefinition,
                Utils::createFQN(self::CLASS_PATH, self::CLASS_NAME)
            );
        }

        $this->writeTraversalFile();
    }
}
