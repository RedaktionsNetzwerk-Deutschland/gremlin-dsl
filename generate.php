<?php

declare(strict_types=1);

use RND\GremlinDSL\Generator\TraversalGeneratorCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

require 'vendor/autoload.php';

const METHOD_SOURCE_JSON = './generator/methods.json';

$application = new ConsoleApplication();

$application->add(new TraversalGeneratorCommand());

$application->setDefaultCommand('dsl:generate');

$application->run();
