<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use RND\GremlinDSL\Traversal\Predicates\Text\AbstractTextPredicate;

class TextPredicatesGenerator extends PredicatesGenerator
{
    protected const PREDICATE_COMMENT = 'The "%s" text-predicate.';
    protected const ABSTRACT_CLASS = AbstractTextPredicate::class;
    protected const FUNCTIONS_FILE = 'src/text_predicates.php';
}
