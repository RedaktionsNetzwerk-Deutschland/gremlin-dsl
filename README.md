# PHP Gremlin DSL implementation

[![PHPCS](https://img.shields.io/github/workflow/status/RedaktionsNetzwerk-Deutschland/gremlin-dsl/PHPCS?label=PHPCS)](https://github.com/RedaktionsNetzwerk-Deutschland/gremlin-dsl/actions?query=workflow%3APHPCS)
[![PHPUnit](https://img.shields.io/github/workflow/status/RedaktionsNetzwerk-Deutschland/gremlin-dsl/PHPUnit?label=PHPUnit)](https://github.com/RedaktionsNetzwerk-Deutschland/gremlin-dsl/actions?query=workflow%3APHPCS)
[![License](https://img.shields.io/github/license/RedaktionsNetzwerk-Deutschland/gremlin-dsl)](LICENSE.md)
[![Downloads](https://img.shields.io/packagist/dt/rnd/gremlin-dsl)](https://packagist.org/packages/rnd/gremlin-dsl)
[![Latest version](https://img.shields.io/packagist/v/rnd/gremlin-dsl)](https://packagist.org/packages/rnd/gremlin-dsl)
[![codecov](https://codecov.io/gh/RedaktionsNetzwerk-Deutschland/gremlin-dsl/branch/master/graph/badge.svg)](https://codecov.io/gh/RedaktionsNetzwerk-Deutschland/gremlin-dsl)

## Introduction

Gremlin is a graph traversal language developed by [Apache TinkerPop](https://tinkerpop.apache.org/).

Many graph vendors like [Neo4j](https://neo4j.com/), [Azure Cosmos](https://azure.microsoft.com/services/cosmos-db/), [AWS Neptune](https://aws.amazon.com/neptune/) and [many more](https://tinkerpop.apache.org/#graph-systems) supports Gremlin.

This package provides a basic integration of gremlin for php applications.

This version is built from [TinkerPop v3.4.10](generator/pom.xml#L24).

## Installation
```shell
composer require rnd/gremlin-dsl
```

## Configuration
This packages provides a static [Configuration](src/Configuration.php) Class with some configuration options.

| Option                                | Scope         | Type    | Default | Description                                   |
|---------------------------------------|---------------|---------|---------|-----------------------------------------------|
| GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS | Constant      | boolean | false   | Globally register [short-functions](#short-functions) for gremlin.<br>E.g. the global `g`-function will be available to start the traversal. |
| enableShortFunctions                  | Configuration | boolean | false   | Globally register [short-functions](#short-functions) for gremlin.<br>E.g. the global `g`-function will be available to start the traversal. |
| sendClosure                           | Configuration | Closure | null    | Register a global callback for the [pseudo send step](#sending-the-graph-traversal-string) |

You can either configure it from array:
```php
use RND\GremlinDSL\Configuration;

/** @var \Brightzone\GremlinDriver\Connection $connection */
$connection = null;

Configuration::fromConfig([
    'sendClosure' => function (string $traversalString) use ($connection) {
         return $connection->send($traversalString);
     },
    'enableShortFunctions' => true,
]);
```

or set the desired settings directly:
```php
use RND\GremlinDSL\Configuration;

/** @var \Brightzone\GremlinDriver\Connection $connection */
$connection = null;

Configuration::getInstance()
    ->setSendClosure(function (string $traversalString) use ($connection) {
        return $connection->send($traversalString);
    })
    ->enableShortFunctions()
;

```


## Usage
Just [install the package](#installation) and begin traversing.

```php
<?php
require_once 'vendor/autoload.php';

echo \RND\GremlinDSL\Traversal\GraphTraversal::g()
    ->V(1)->out('knows')->has('age', new \RND\GremlinDSL\Traversal\Predicates\Gt(30))->values('name');
# g.V(1).out("knows").has("age", gt(30)).values("name")
```

### Sending the graph traversal string
There is a pseudo `send` step provided with this package.

You can either globally configure a closure for the send step or provide it with every call.

```php
<?php
require_once 'vendor/autoload.php';

use RND\GremlinDSL\Configuration;

/** @var \Brightzone\GremlinDriver\Connection $connection */
$connection = null;
$sendClosure = function (string $traversalString) use ($connection) {
    return $connection->send($traversalString);
};

Configuration::getInstance()->setSendClosure($sendClosure);
g()->V(1)->out("knows")->has("age", gt(30))->values("name")->send();

# or

g()->V(1)->out("knows")->has("age", gt(30))->values("name")->send($sendClosure);
```

Instead of a closure you can also provide an instance of SendClosureInterface.

```php
use RND\GremlinDSL\Traversal\SendClosureInterface;
use RND\GremlinDSL\Traversal\GraphTraversalInterface;

class SendClosure implements SendClosureInterface
{
    public function __invoke(GraphTraversalInterface $graphTraversal, string $traversalString) {
        // handle the send
    }
}
```

### Short functions
Short functions are simplifying the graph traversal generation and usage of predicates.

You've to enable `GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS`,
manually load e.g. [resources/predicates.php](resources/predicates.php)
or call `Configuration::enableShortFunctions()` to make short functions available.

```php
<?php

require_once 'vendor/autoload.php';

\RND\GremlinDSL\Configuration::getInstance()->enableShortFunctions();
g()->V(1)->out('knows')->has('age', gt(30))->values('name');
# g.V(1).out("knows").has("age", gt(30)).values("name")
```

With `GREMLIN_DSL_REGISTER_GLOBAL_FUNCTIONS` constant:
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
