<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use RND\GremlinDSL\Traversal\Steps\AbstractStep;

abstract class AbstractGraphTraversalGenerator extends AbstractGenerator
{

    protected const CLASS_NAME = '';
    protected const CLASS_PATH = 'RND\\GremlinDSL\\Traversal';
    protected const ABSTRACT_CLASS = '';
    protected const ABSTRACT_STEP_CLASS = AbstractStep::class;
    protected const STEP_CLASS_SUFFIX = 'Step';

    protected PhpFile $graphTraversalFile;
    protected ?PhpNamespace $graphTraversalNs = null;
    protected ClassType $graphTraversalClass;

    protected function generateSourceClass()
    {
        $this->graphTraversalFile = $this->bootstrapFile();
        $this->graphTraversalClass = $this->bootstrapClass(
            static::CLASS_NAME,
            static::CLASS_PATH,
            static::ABSTRACT_CLASS,
            $this->graphTraversalFile,
            $this->graphTraversalNs
        );
        $this->graphTraversalClass->addComment('@see https://tinkerpop.apache.org/docs/current/reference/');
    }

    protected function createStepClass(string $methodName, string $stepNamespace): ClassType
    {
        $methodName = ucfirst($methodName) . static::STEP_CLASS_SUFFIX;
        $stepNamespace = $stepNamespace ?? $this->detectNamespaceForClassName(self::ABSTRACT_STEP_CLASS);
        $class = $this->bootstrapClass(
            $methodName,
            $stepNamespace,
            self::ABSTRACT_STEP_CLASS,
            $classFile
        );
        $this->write($classFile);

        return $class;
    }

    protected function generateParameter(Method $method, $parameterDefinition): Parameter
    {
        $generatedParameter = $method->addParameter($parameterDefinition['name']);
        $generatedParameter->setNullable(false);
        $generatedParameter->setType($this->getParameterType($parameterDefinition['type']));
        if ($parameterDefinition['variadic']) {
            $method->setVariadic(true);
        }
        $method->addComment(
            sprintf(
                '@param %2$s $%1$s%3$s',
                $generatedParameter->getName(),
                $generatedParameter->getType() ?? 'mixed',
                $parameterDefinition['variadic'] ? ',...' : ''
            )
        );

        return $generatedParameter;
    }

    protected function generateMethod(
        string $methodName,
        array $methodDefinition,
        string $returnType,
        ClassType $stepClass
    ): Method {
        $method = $this->graphTraversalClass->addMethod($methodName);
        $method->addComment(sprintf('The "%s" source step.', $methodName));
        $method->addComment('');
        $method->setReturnType($returnType);
        $parameters = [];
        if ($methodDefinition['differentSignatures'] > 1) {
            $method->addParameter('args', null);
            $method->setVariadic(true);
            $parameters[] = '...$args';
            $this->addMethodDocs($method, $methodDefinition);
        } else {
            foreach ($methodDefinition['parameters'][0] ?? [] as $parameterDefinition) {
                $generatedParameter = $this->generateParameter($method, $parameterDefinition);
                $parameters[] = $generatedParameter->getName();
            }
            $parameters = array_map(fn($item) => '$' . $item, $parameters);
            if ($method->isVariadic()) {
                $lastItem = array_pop($parameters);
                $parameters[] = '...' . $lastItem;
            }
        }

        $this->graphTraversalNs->addUse(Utils::getFQN($stepClass));
        $this->graphTraversalNs->addUse($returnType);

        $unresolvedReturnType = $this->graphTraversalNs->unresolveName($returnType);

        $method->addComment('@return ' . $unresolvedReturnType);
        $this->addMethodBody($method, $stepClass, $parameters, $unresolvedReturnType);

        return $method;
    }

    protected function addMethodBody(Method $method, ClassType $stepClass, array $parameters, string $returnType)
    {
        $method->addBody(sprintf('$step = new %s([%s]);', $stepClass->getName(), implode(', ', $parameters)));
        $method->addBody('$this->steps->add($step);');
        $method->addBody('');

        if ($returnType === $this->graphTraversalClass->getName()) {
            $returnType = 'static';
        }

        $method->addBody(sprintf('return new %s();', $returnType));
    }

    protected function addMethodDocs(Method $method, array $methodDefinition)
    {
        $method->addComment(sprintf('@param mixed $args being any of:'));
        foreach ($methodDefinition['parameters'] as $parameterGroup) {
            $parameters = array_map(
                fn($item) => $this->getParameterType($item['type'], true) . ' ' . $item['name'],
                $parameterGroup
            );
            if (empty($parameters)) {
                $parameters = ['empty'];
            }
            $method->addComment(sprintf('%s- %s', str_repeat(' ', 19), implode(', ', $parameters)));
        }
    }

    protected function getParameterType(string $inType, bool $mixed = false): ?string
    {
        switch ($inType) {
            case 'string':
                return 'string';
        }

        return $mixed ? 'mixed' : null;
    }

    protected function groupMethods(array $methods): array
    {
        $result = [];

        foreach ($methods as $method) {
            $result[$method['methodName']] = $result[$method['methodName']] ?? [
                    'differentSignatures' => 0,
                    'parameters' => [],
                ];

            $result[$method['methodName']]['differentSignatures']++;
            $result[$method['methodName']]['parameters'][] = $method['parameters'];
        }

        return $result;
    }

    protected function writeTraversalFile()
    {
        $this->write($this->graphTraversalFile);
    }

}
