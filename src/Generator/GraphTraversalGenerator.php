<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Generator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\GlobalFunction;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use SpecialWeb\GremlinDSL\Traversal\AbstractGraphTraversal;
use SpecialWeb\GremlinDSL\Traversal\GraphTraversalInterface;
use SpecialWeb\GremlinDSL\Traversal\Steps\BasicStep;

class GraphTraversalGenerator extends AbstractGraphTraversalGenerator
{
    protected const CLASS_NAME = 'GraphTraversal';
    protected const CLASS_PATH = 'SpecialWeb\\GremlinDSL\\Traversal';
    protected const ABSTRACT_CLASS = AbstractGraphTraversal::class;
    protected const ABSTRACT_STEP_CLASS = BasicStep::class;
    protected const FUNCTIONS_FILE = 'resources/statics.php';

    protected const RESERVED_FUNCTIONS = [
        'abstract',
        'and',
        'as',
        'array,',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'finally',
        'fn',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'match',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
    ];

    private PhpFile $functionsFile;

    /** @var GlobalFunction[] */
    protected ?array $functions = [];

    private array $methods;

    public function __construct(FileWriter $writer, array $methods)
    {
        $this->methods = $methods;

        parent::__construct($writer);
    }

    public function generate()
    {
        $this->generateSourceClass();
        $this->functionsFile = $this->bootstrapFile();
        $this->functionsFile->addUse(GraphTraversalInterface::class);

        $this->methods = $this->groupMethods($this->methods);

        foreach ($this->methods as $methodName => $methodDefinition) {
            $this->generateClassAndMethod(
                $methodName,
                $methodDefinition,
                Utils::createFQN(static::CLASS_PATH, static::CLASS_NAME)
            );
        }

        $this->writeTraversalFile();
        $this->writeFunctions();
    }

    protected function additionalGenerator(
        string $methodName,
        array $methodDefinition,
        string $returnType,
        ClassType $stepClass,
        Method $method
    ) {
        $this->generateFunction($this->graphTraversalClass, $stepClass, $method);
    }

    protected function generateFunction(ClassType $traversalClass, ClassType $stepClass, Method $method)
    {
        $this->functionsFile->addUse(Utils::getFQN($traversalClass));

        $methodName = $method->getName();
        if (in_array($methodName, static::RESERVED_FUNCTIONS)) {
            $methodName = '_' . $methodName;
        }

        $function = new GlobalFunction($methodName);
        $function->setParameters($method->getParameters());
        $function->setVariadic($method->isVariadic())->setReturnType($traversalClass->getName());

        $params = [];

        if (count($method->getParameters()) > 0) {
            $parameters = array_keys($method->getParameters());
            for ($i = 0, $iMax = count($parameters); $i < $iMax; $i++) {
                $param = '$' . $parameters[$i];
                if ($method->isVariadic() && $i === $iMax - 1) {
                    $param = '...' . $param;
                }
                $params[] = $param;
            }
        }

        $function->addBody('');
        $function->addBody(
            sprintf(
                'return (new %s())->%s(%s);',
                $traversalClass->getName(),
                $method->getName(),
                implode(', ', $params)
            )
        );
        $function->setComment($method->getComment());
        $this->functions[$methodName] = $function;
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
