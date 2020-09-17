# gremlin-dsl

![PHPCS](https://github.com/RedaktionsNetzwerk-Deutschland/gremlin-dsl/workflows/PHPCS/badge.svg)

## DSL generation

The gremlin dsl generation is based on the [java base-classes](https://github.com/apache/tinkerpop/tree/master/gremlin-core/src/main/java/org/apache/tinkerpop/gremlin/process/traversal/dsl/graph).

To generate the DSL just call `make generate` that will first generate the JSON methods structure and afterwards the php file generation.

### JSON only
Just call `make generate-json` or `mvn -f generator -P glv-json compile`

### PHP only
To e.g. adjust the php file generation you can call
`php generate.php [dsl:generate [<in-file>]]` or `make generate-php`

___
♥ RND Technical Hub
