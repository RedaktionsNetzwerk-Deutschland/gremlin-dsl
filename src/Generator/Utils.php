<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use Nette\PhpGenerator\ClassType;

class Utils
{

    public static function getFQN(ClassType $class): string
    {
        return $class->getNamespace()
            ? $class->getNamespace()->getName() . '\\' . $class->getName()
            : $class->getName();
    }

    public static function createFQN(string $path, string $class): string
    {
        return implode(
            '\\',
            [
                rtrim($path, '\\'),
                ltrim($class, '\\'),
            ]
        );
    }

    public static function indent(string $input, int $size = 4): string
    {
        $lines = explode(PHP_EOL, $input);
        $lines = array_map(fn($line) => !empty($line) ? str_repeat(' ', $size) . $line : '', $lines);

        return implode(PHP_EOL, $lines);
    }
}
