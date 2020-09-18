<?php

declare(strict_types=1);

namespace RND\GremlinDSL;

use Closure;

class Configuration
{

    private static ?Configuration $instance = null;

    protected ?Closure $sendClosure = null;

    public static function getInstance(): Configuration
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function fromConfig(array $configuration): Configuration
    {
        $instance = static::getInstance();

        if (isset($configuration['sendClosure'])) {
            $instance->setSendClosure($configuration['sendClosure']);
        }
        if (isset($configuration['enableShortFunctions'])) {
            $instance->enableShortFunctions();
        }

        return $instance;
    }

    public function getSendClosure(): ?Closure
    {
        return $this->sendClosure;
    }

    /**
     * Example:
     * ```php
     * $configuration->setSendClosure(function (string $traversalString) {})
     * ```
     *
     * @param Closure $closure The function used for handling the send step.
     *                         The context of the closure will be set to the current GraphTraversal.
     *                         The first parameter is the compiled traversal string.
     *                         Example closure:
     *                         <code>
     * function (string $traversalString) use ($connection) { return $connection->send($traversalString); }
     *                         </code>
     * @return Configuration
     */
    public function setSendClosure(Closure $closure): Configuration
    {
        $this->sendClosure = $closure;

        return $this;
    }

    public function enableShortFunctions(): Configuration
    {
        require_once dirname(__DIR__).'/resources/traversal.php';
        require_once dirname(__DIR__).'/resources/predicates.php';
        require_once dirname(__DIR__).'/resources/text_predicates.php';

        return $this;
    }
}
