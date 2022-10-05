<?php

declare(strict_types=1);

namespace SpecialWeb\GremlinDSL\Generator;

use SpecialWeb\GremlinDSL\Traversal\Predicates\Text\AbstractTextPredicate;

class TextPredicatesGenerator extends PredicatesGenerator
{
    protected const PREDICATE_COMMENT = 'The "%s" text-predicate.';
    protected const ABSTRACT_CLASS = AbstractTextPredicate::class;
    protected const FUNCTIONS_FILE = 'resources/text_predicates.php';
}
