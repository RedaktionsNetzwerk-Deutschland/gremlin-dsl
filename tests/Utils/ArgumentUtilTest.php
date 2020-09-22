<?php

declare(strict_types=1);

namespace Utils;

use PHPUnit\Framework\TestCase;
use RND\GremlinDSL\Utils\ArgumentUtil;

class ArgumentUtilTest extends TestCase
{

    public function testWithReservedKeywords()
    {
        foreach (ArgumentUtil::RESERVED_KEYWORDS as $reserved) {
            self::assertSame(
                sprintf('%s, 123', $reserved),
                ArgumentUtil::implode([$reserved, 123])
            );
        }
    }

    public function testWithString()
    {
        self::assertSame(
            '"foo", "bar"',
            ArgumentUtil::implode(['foo', 'bar'])
        );
    }

    public function testWithInt()
    {
        self::assertSame(
            '123, 456',
            ArgumentUtil::implode([123, 456])
        );
    }

    public function testWithUuidHelper()
    {
        self::assertSame(
            'T.id, UUID.fromString("013fcca0-4791-4b3a-9e48-ba7f783045df")',
            ArgumentUtil::implode(['T.id', 'UUID.fromString("013fcca0-4791-4b3a-9e48-ba7f783045df")'])
        );
    }
}
