<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use RND\GremlinDSL\Traversal\Predicates\AbstractPredicate;

class PredicatesGenerator extends AbstractGenerator
{
    protected const PREDICATE_COMMENT = 'The "%s" predicate.';
    protected const ABSTRACT_CLASS = AbstractPredicate::class;

    /** @var string[] list of predicate methods */
    private array $methods;

    public function __construct(FileWriter $writer, array $methods)
    {
        $this->methods = $methods;
        parent::__construct($writer);
    }

    public function generate()
    {
        foreach ($this->methods as $method) {
            $this->generatePredicate($method);
        }
    }

    protected function generatePredicate(string $predicateName)
    {
        $abstractClass = static::ABSTRACT_CLASS;
        $namespaceName = $this->detectNamespaceForClassName($abstractClass);
        $predicateClass = $this->bootstrapClass(ucfirst($predicateName), $namespaceName, $abstractClass, $file);

        $predicateClass
            ->addComment(sprintf(static::PREDICATE_COMMENT, $predicateName))
            ->addComment('')
            ->addComment('@see https://tinkerpop.apache.org/docs/current/reference/#a-note-on-predicates');
        $predicateClass
            ->addConstant('STEP_NAME', $predicateName)
            ->setPublic();

        $this->write($file);
    }
}
