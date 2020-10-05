<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Tests;

use RND\GremlinDSL\Configuration;
use PHPUnit\Framework\TestCase;
use RND\GremlinDSL\Tests\Traversal\SendClosure;

class ConfigurationTest extends TestCase
{

    public function resetConfiguration()
    {
        $instanceProperty = new \ReflectionProperty(Configuration::class, 'instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null);
    }

    public function testFromConfig(): void
    {
        $this->resetConfiguration();
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
        $this->resetConfiguration();
        $closure = fn() => 'closure';
        $object = Configuration::getInstance()->setSendClosure($closure);
        $configuredClosure = $object->getSendClosure();
        self::assertSame($closure, $configuredClosure);
        self::assertEquals('closure', $configuredClosure->call($this));
    }

    public function testAcceptSendClosureInterface()
    {
        $this->resetConfiguration();
        $sendClosure = new SendClosure();

        Configuration::getInstance()->setSendClosure($sendClosure);

        self::assertSame($sendClosure, Configuration::getInstance()->getSendClosure());
    }

    public function testGetInstance(): void
    {
        $this->resetConfiguration();
        self::assertSame(Configuration::getInstance(), Configuration::getInstance());
    }

    public function testEnableShortFunctions(): void
    {
        $this->resetConfiguration();
        Configuration::getInstance()->enableShortFunctions();
        self::assertTrue(function_exists('g'));
        self::assertTrue(function_exists('addE'));
    }
}
