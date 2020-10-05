<?php

declare(strict_types=1);

namespace RND\GremlinDSL;

use Closure;
use RND\GremlinDSL\Traversal\SendClosureInterface;

class Configuration
{

    private static ?Configuration $instance = null;

    /** @var SendClosureInterface|Closure|null */
    protected $sendClosure;

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
        $instance->setConfig($configuration);

        return $instance;
    }

    public function setConfig(array $configuration): Configuration
    {
        if (isset($configuration['sendClosure'])) {
            $this->setSendClosure($configuration['sendClosure']);
        }
        if (isset($configuration['enableShortFunctions'])) {
            $this->enableShortFunctions();
        }

        return $this;
    }

    public function getSendClosure()
    {
        return $this->sendClosure;
    }

    /**
     * Example:
     * ```php
     * $configuration->setSendClosure(function (string $traversalString) {})
     * ```
     *
     * @param SendClosureInterface|Closure $closure The function used for handling the send step.
     *                                              The context of the closure will be set to the current GraphTraversal.
     *                                              The first parameter is the compiled traversal string.
     *                                              Example closure:
     *                                              <code>
     * function (string $traversalString) use ($connection) { return $connection->send($traversalString); }
     *                                              </code>
     * @return Configuration
     */
    public function setSendClosure($closure): Configuration
    {
        $this->sendClosure = $closure;

        return $this;
    }

    public function enableShortFunctions(): Configuration
    {
        gremlinLoadGlobalFunctions();

        return $this;
    }
}
