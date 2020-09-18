<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Tests;

use RND\GremlinDSL\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{

    public function testFromConfig(): void
    {
        $closure = fn() => 'closure';
        $config = [
            'enableShortFunctions' => true,
            'sendClosure' => $closure,
        ];

        $object = Configuration::fromConfig($config);
        self::assertSame($closure, $object->getSendClosure());

    }

    public function testGetSendClosure(): void
    {
        $closure = fn() => 'closure';
        $object = Configuration::getInstance()->setSendClosure($closure);
        $configuredClosure = $object->getSendClosure();
        self::assertSame($closure, $configuredClosure);
        self::assertEquals('closure', $configuredClosure->call($this));
    }

    public function testGetInstance(): void
    {
        self::assertSame(Configuration::getInstance(), Configuration::getInstance());
    }

    public function testEnableShortFunctions(): void
    {
        $exists = function_exists('g');
        self::assertFalse($exists);
        Configuration::getInstance()->enableShortFunctions();
        self::assertTrue(function_exists('g'));
    }
}
