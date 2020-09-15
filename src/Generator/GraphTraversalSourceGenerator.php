<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use RND\GremlinDSL\Traversal\AbstractGraphTraversalSource;
use RND\GremlinDSL\Traversal\Steps\Source\AbstractSourceStep;

class GraphTraversalSourceGenerator extends AbstractGraphTraversalGenerator
{
    protected const CLASS_NAME = 'GraphTraversalSource';
    protected const CLASS_PATH = 'RND\\GremlinDSL\\Traversal';
    protected const ABSTRACT_CLASS = AbstractGraphTraversalSource::class;
    protected const ABSTRACT_STEP_CLASS = AbstractSourceStep::class;
    protected const STEP_CLASS_SUFFIX = 'SourceStep';

    private array $stepMethods;
    private array $spawnMethods;

    public function __construct(FileWriter $writer, array $stepMethods, array $spawnMethods)
    {
        $this->stepMethods = $stepMethods;
        $this->spawnMethods = $spawnMethods;

        parent::__construct($writer);
    }

    public function generate()
    {
        $this->generateSourceClass();

        $this->spawnMethods = $this->groupMethods($this->spawnMethods);
        $this->stepMethods = $this->groupMethods($this->stepMethods);

        foreach ($this->stepMethods as $methodName => $methodDefinition) {
            $this->generateClassAndMethod(
                $methodName,
                $methodDefinition,
                Utils::createFQN(self::CLASS_PATH, self::CLASS_NAME)
            );
        }

        foreach ($this->spawnMethods as $methodName => $methodDefinition) {
            $this->generateClassAndMethod(
                $methodName,
                $methodDefinition,
                Utils::createFQN(self::CLASS_PATH, 'GraphTraversal')
            );
        }

        $this->writeTraversalFile();
    }

    protected function generateClassAndMethod(string $methodName, array $methodDefinition, string $returnType)
    {
        $stepNamespace = $this->detectNamespaceForClassName(self::ABSTRACT_STEP_CLASS);
        $stepClass = $this->createStepClass($methodName, $stepNamespace);

        $this->generateMethod($methodName, $methodDefinition, $returnType, $stepClass);
    }
}
