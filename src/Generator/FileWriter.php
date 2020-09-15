<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;

class FileWriter
{

    private static ?FileWriter $instance = null;

    public Printer $printer;

    private ?array $autoloadConfig = null;

    public static function getInstance(): FileWriter
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function withPrinter(Printer $printer): FileWriter
    {
        $this->printer = $printer;

        return $this;
    }

    public function writeWithComposerPathDetection(PhpFile $file)
    {
        $config = static::getInstance()->getAutoloadConfig();
        $targets = [];
        foreach ($file->getNamespaces() as $namespace) {
            $namespaceName = $namespace->getName();
            // Use only the first class in file for file name generation
            $fileName = current(array_keys($namespace->getClasses())) . '.php';
            foreach ($config as $namespacePrefix => $rootDirectory) {
                if (strpos($namespaceName, $namespacePrefix) === 0) {
                    $targets[] = implode(
                        '/',
                        [
                            getcwd(),
                            rtrim($rootDirectory, '/'),
                            str_replace(
                                [$namespacePrefix, '\\'],
                                ['', '/'],
                                $namespaceName
                            ),
                            $fileName,
                        ]
                    );
                }
            }
        }

        $this->write($file, $targets);
    }

    public function write(PhpFile $file, $target)
    {
        if (is_array($target)) {
            foreach ($target as $item) {
                $this->write($file, $item);
            }

            return;
        }

        $lastIndex = strrpos($target, '/');
        $directory = substr($target, 0, $lastIndex);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
            }
        }

        file_put_contents($target, $this->printer->printFile($file));
    }

    public function getAutoloadConfig(): array
    {
        if (!$this->autoloadConfig) {
            $composerJson = json_decode(file_get_contents(getcwd() . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
            $this->autoloadConfig = $composerJson['autoload']['psr-4'];
        }

        return $this->autoloadConfig;
    }

}
