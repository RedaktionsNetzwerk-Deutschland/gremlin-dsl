<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Generator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\GlobalFunction;
use Nette\PhpGenerator\PhpFile;
use SpecialWeb\GremlinDSL\Traversal\Predicates\AbstractPredicate;

class PredicatesGenerator extends AbstractGenerator
{
    protected const PREDICATE_COMMENT = 'The "%s" predicate.';
    protected const ABSTRACT_CLASS = AbstractPredicate::class;
    protected const FUNCTIONS_FILE = 'resources/predicates.php';

    /** @var string[] list of predicate methods */
    private array $methods;

    private PhpFile $functionsFile;

    /** @var GlobalFunction[] */
    protected ?array $functions = [];

    public function __construct(FileWriter $writer, array $methods)
    {
        $this->methods = $methods;
        parent::__construct($writer);
    }

    public function generate()
    {
        $this->functionsFile = $this->bootstrapFile();
        foreach ($this->methods as $method) {
            $this->generatePredicate($method);
        }

        $this->writeFunctions();
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

        $this->generateFunction($predicateName, $predicateClass);
    }

    protected function generateFunction(string $predicateName, ClassType $predicateClass)
    {
        $this->functionsFile->addUse(Utils::getFQN($predicateClass));
        $function = new GlobalFunction($predicateName);
        $function->addParameter('args');
        $function->setVariadic(true)->setReturnType($predicateClass->getName());
        $function->addBody(sprintf('return new %s(...$args);', $predicateClass->getName()));
        $this->functions[$predicateName] = $function;
    }

    protected function writeFunctions()
    {
        $output = $this->writer->printer->printFile($this->functionsFile);
        foreach ($this->functions as $functionName => $function) {
            $output .= PHP_EOL . sprintf('if (!function_exists(\'%s\')) {', $functionName);
            $output .= PHP_EOL . Utils::indent($this->writer->printer->printFunction($function), 4);
            $output .= '}' . PHP_EOL;
        }

        $this->writer->writePlain($output, static::FUNCTIONS_FILE);
    }
}
