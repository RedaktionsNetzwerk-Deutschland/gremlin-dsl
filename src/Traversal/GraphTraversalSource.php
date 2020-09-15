<?php

/**
 * This is an autogenerated file. Changes will be lost on next generation.
 */

declare(strict_types=1);

namespace RND\GremlinDSL\Traversal;

use RND\GremlinDSL\Traversal\Steps\Source\AddESourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\AddVSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\ESourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\InjectSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\IoSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\VSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\WithBulkSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\WithPathSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\WithSackSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\WithSideEffectSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\WithSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\WithStrategiesSourceStep;
use RND\GremlinDSL\Traversal\Steps\Source\WithoutStrategiesSourceStep;

/**
 * @see https://tinkerpop.apache.org/docs/current/reference/
 */
class GraphTraversalSource extends AbstractGraphTraversalSource
{
    /**
     * The "with" source step.
     *
     * @param mixed $args being any of:
     *                    - string key
     *                    - string key, mixed value
     * @return GraphTraversalSource
     */
    public function with(...$args): GraphTraversalSource
    {
        $step = new WithSourceStep([...$args]);
        $this->steps->add($step);

        return new static();
    }

    /**
     * The "withBulk" source step.
     *
     * @param mixed $useBulk
     * @return GraphTraversalSource
     */
    public function withBulk($useBulk): GraphTraversalSource
    {
        $step = new WithBulkSourceStep([$useBulk]);
        $this->steps->add($step);

        return new static();
    }

    /**
     * The "withPath" source step.
     *
     * @return GraphTraversalSource
     */
    public function withPath(): GraphTraversalSource
    {
        $step = new WithPathSourceStep([]);
        $this->steps->add($step);

        return new static();
    }

    /**
     * The "withSack" source step.
     *
     * @param mixed $args being any of:
     *                    - mixed initialValue
     *                    - mixed initialValue, mixed mergeOperator
     *                    - mixed initialValue, mixed splitOperator
     *                    - mixed initialValue, mixed splitOperator, mixed mergeOperator
     *                    - mixed initialValue
     *                    - mixed initialValue, mixed mergeOperator
     *                    - mixed initialValue, mixed splitOperator
     *                    - mixed initialValue, mixed splitOperator, mixed mergeOperator
     * @return GraphTraversalSource
     */
    public function withSack(...$args): GraphTraversalSource
    {
        $step = new WithSackSourceStep([...$args]);
        $this->steps->add($step);

        return new static();
    }

    /**
     * The "withSideEffect" source step.
     *
     * @param mixed $args being any of:
     *                    - string key, mixed initialValue
     *                    - string key, mixed initialValue, mixed reducer
     *                    - string key, mixed initialValue
     *                    - string key, mixed initialValue, mixed reducer
     * @return GraphTraversalSource
     */
    public function withSideEffect(...$args): GraphTraversalSource
    {
        $step = new WithSideEffectSourceStep([...$args]);
        $this->steps->add($step);

        return new static();
    }

    /**
     * The "withStrategies" source step.
     *
     * @param mixed $traversalStrategies,...
     * @return GraphTraversalSource
     */
    public function withStrategies(...$traversalStrategies): GraphTraversalSource
    {
        $step = new WithStrategiesSourceStep([...$traversalStrategies]);
        $this->steps->add($step);

        return new static();
    }

    /**
     * The "withoutStrategies" source step.
     *
     * @param mixed $traversalStrategyClasses,...
     * @return GraphTraversalSource
     */
    public function withoutStrategies(...$traversalStrategyClasses): GraphTraversalSource
    {
        $step = new WithoutStrategiesSourceStep([...$traversalStrategyClasses]);
        $this->steps->add($step);

        return new static();
    }

    /**
     * The "E" source step.
     *
     * @param mixed $edgesIds,...
     * @return GraphTraversal
     */
    public function E(...$edgesIds): GraphTraversal
    {
        $step = new ESourceStep([...$edgesIds]);
        $this->steps->add($step);

        return new GraphTraversal();
    }

    /**
     * The "V" source step.
     *
     * @param mixed $vertexIds,...
     * @return GraphTraversal
     */
    public function V(...$vertexIds): GraphTraversal
    {
        $step = new VSourceStep([...$vertexIds]);
        $this->steps->add($step);

        return new GraphTraversal();
    }

    /**
     * The "addE" source step.
     *
     * @param mixed $args being any of:
     *                    - string label
     *                    - mixed edgeLabelTraversal
     * @return GraphTraversal
     */
    public function addE(...$args): GraphTraversal
    {
        $step = new AddESourceStep([...$args]);
        $this->steps->add($step);

        return new GraphTraversal();
    }

    /**
     * The "addV" source step.
     *
     * @param mixed $args being any of:
     *                    - empty
     *                    - string label
     *                    - mixed vertexLabelTraversal
     * @return GraphTraversal
     */
    public function addV(...$args): GraphTraversal
    {
        $step = new AddVSourceStep([...$args]);
        $this->steps->add($step);

        return new GraphTraversal();
    }

    /**
     * The "inject" source step.
     *
     * @param mixed $starts,...
     * @return GraphTraversal
     */
    public function inject(...$starts): GraphTraversal
    {
        $step = new InjectSourceStep([...$starts]);
        $this->steps->add($step);

        return new GraphTraversal();
    }

    /**
     * The "io" source step.
     *
     * @param string $file
     * @return GraphTraversal
     */
    public function io(string $file): GraphTraversal
    {
        $step = new IoSourceStep([$file]);
        $this->steps->add($step);

        return new GraphTraversal();
    }
}
