<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use Composer\Autoload\ClassLoader;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;
use Nette\PhpGenerator\PsrPrinter;
use RND\GremlinDSL\Generator\Exception\RuntimeException;
use RND\GremlinDSL\Traversal\GraphTraversal;
use RND\GremlinDSL\Traversal\Predicates\AbstractPredicate;
use RND\GremlinDSL\Traversal\Predicates\Text\AbstractTextPredicate;

class TraversalGenerator
{
    public const PREDICATE_EXTEND = AbstractPredicate::class;
    public const TEXT_PREDICATE_EXTEND = AbstractTextPredicate::class;

    private array $methodsDefinition;
    private FileWriter $writer;

    public function __construct(array $methodsDefinition, ?Printer $printer = null, ?FileWriter $writer = null)
    {
        $this->methodsDefinition = $methodsDefinition;
        $this->writer = $writer ?? FileWriter::getInstance()->withPrinter($printer ?? new PsrPrinter());
    }

    public function generate()
    {
        $this->generatePredicates();
        $this->generateTextPredicates();
    }

    protected function generatePredicates()
    {
        $predicates = $this->getMethodList('predicates');
        foreach ($predicates as $predicateName) {
            $this->generatePredicate($predicateName);
        }
    }

    protected function generateTextPredicates()
    {
        $predicates = $this->getMethodList('textPredicates');
        foreach ($predicates as $predicateName) {
            $this->generatePredicate($predicateName, true);
        }
    }

    protected function generatePredicate(string $predicateName, bool $textPredicate = false)
    {
        $abstractClass = $textPredicate ? self::TEXT_PREDICATE_EXTEND : self::PREDICATE_EXTEND;
        $namespaceName = $this->detectNamespaceForClassName($abstractClass);
        $file = $this->bootstrapFile();

        $namespace = $file->addNamespace($namespaceName);
        $predicateClass = $namespace->addClass(ucfirst($predicateName));

        $predicateClass
            ->addExtend($abstractClass)
            ->addComment(sprintf('The %s %spredicate.', $predicateName, $textPredicate ? 'text-' : ''))
            ->addComment('')
            ->addComment('@see https://tinkerpop.apache.org/docs/current/reference/#a-note-on-predicates');
        $predicateClass
            ->addConstant('STEP_NAME', $predicateName)
            ->setPublic();

        $this->writer->writeWithComposerPathDetection($file);


    }

    protected function bootstrapFile(): PhpFile
    {
        return (new PhpFile())
            ->addComment('This is an autogenerated file. Changes will be lost on next generation.')
            ->setStrictTypes();
    }

    protected function detectNamespaceForClassName(string $className): string
    {
        return substr($className, 0, strrpos($className, '\\'));
    }

    protected function getMethodList(string $listType): array
    {
        $methods = $this->methodsDefinition[$listType] ?? null;
        if (!$methods) {
            throw new RuntimeException(
                sprintf(
                    'The provided methods does not contain "%s". Available method lists: %s',
                    $listType,
                    implode(', ', array_keys($this->methodsDefinition))
                )
            );
        }

        return $methods;
    }
}
