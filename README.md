# PHP Gremlin DSL implementation

[![PHPCS](https://img.shields.io/github/workflow/status/RedaktionsNetzwerk-Deutschland/gremlin-dsl/PHPCS?label=PHPCS)](https://github.com/RedaktionsNetzwerk-Deutschland/gremlin-dsl/actions?query=workflow%3APHPCS)
[![License](https://img.shields.io/github/license/RedaktionsNetzwerk-Deutschland/gremlin-dsl)](LICENSE.md)
[![Downloads](https://img.shields.io/packagist/dt/rnd/gremlin-dsl)](https://packagist.org/packages/rnd/gremlin-dsl)
[![Latest version](https://img.shields.io/packagist/v/rnd/gremlin-dsl)](https://packagist.org/packages/rnd/gremlin-dsl)

## Introduction

Gremlin is a graph traversal language developed by [Apache TinkerPop](https://tinkerpop.apache.org/).

Many graph vendors like [Neo4j](https://neo4j.com/), [Azure Cosmos](https://azure.microsoft.com/services/cosmos-db/), [AWS Neptune](https://aws.amazon.com/neptune/) and [many more](https://tinkerpop.apache.org/#graph-systems) supports Gremlin.

This package provides a basic integration of gremlin for php applications.

## Installation
```shell
composer require rnd/gremlin-dsl
```

## Configuration
| Option                                | Scope    | Type    | Default | Description                                   |
|---------------------------------------|----------|---------|---------|-----------------------------------------------|
| GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS | Constant | boolean | false   | Globally register [short-functions](#short-functions) for gremlin.<br>E.g. the global `g`-function will be available to start the traversal. |

## Usage
Just [install the package](#installation) and begin traversing.

```php
<?php
require_once 'vendor/autoload.php';

echo \RND\GremlinDSL\Traversal\GraphTraversal::g()
    ->V(1)->out('knows')->has('age', new \RND\GremlinDSL\Traversal\Predicates\Gt(30))->values('name');
# g.V(1).out("knows").has("age", gt(30)).values("name")

```

### Short functions
Short functions are simplifying the graph traversal generation and usage of predicates.

You've to enable `GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS` or manually load e.g. [resources/predicates.php](resources/predicates.php) to make short functions available.

```php
<?php

define('GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS', true);
require_once 'vendor/autoload.php';

# With GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS enabled:
g()->V(1)->out('knows')->has('age', gt(30))->values('name');
# g.V(1).out("knows").has("age", gt(30)).values("name")
```

## Development
### DSL generation

The DSL generation is based on the [java base-classes](https://github.com/apache/tinkerpop/tree/master/gremlin-core/src/main/java/org/apache/tinkerpop/gremlin/process/traversal/dsl/graph).

To (re)generate the DSL just call `make generate` that will first generate the JSON methods structure and afterwards the php file generation.

#### Generate JSON only
Just call `make generate-json` or `mvn -f generator -P glv-json compile`

#### Generate PHP only
To e.g. adjust the php file generation you can either call `php generate.php [dsl:generate [<in-file>]]` or `make generate-php`

___
♥ RND Technical Hub
