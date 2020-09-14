<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use Nette\PhpGenerator\Printer;
use Nette\PhpGenerator\PsrPrinter;
use RND\GremlinDSL\Generator\Exception\RuntimeException;

class TraversalGenerator extends AbstractGenerator
{
    private array $methodsDefinition;

    public function __construct(array $methodsDefinition, ?Printer $printer = null, ?FileWriter $writer = null)
    {
        $this->methodsDefinition = $methodsDefinition;

        parent::__construct($writer ?? FileWriter::getInstance()->withPrinter($printer ?? new PsrPrinter()));
    }

    public function generate()
    {
        (new PredicatesGenerator($this->writer, $this->getMethodList('predicates')))->generate();
        (new TextPredicatesGenerator($this->writer, $this->getMethodList('textPredicates')))->generate();

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
