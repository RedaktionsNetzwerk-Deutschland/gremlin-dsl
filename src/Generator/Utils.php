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

}
