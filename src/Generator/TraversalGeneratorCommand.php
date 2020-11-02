<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Generator;

use JsonException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TraversalGeneratorCommand extends Command
{
    protected function configure()
    {
        $this->setName('dsl:generate')
             ->setDescription('Generate gremlin DSL classes for php')
             ->addArgument(
                 'in-file',
                 InputArgument::OPTIONAL,
                 'Path to the JSON file containing the methods.',
                 realpath(getcwd() . '/generator/methods.json')
             );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $methods = $this->validateAndLoadInputFile($input->getArgument('in-file'));
        } catch (RuntimeException $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');

            return self::FAILURE;
        }

        $generator = new TraversalGenerator($methods);
        $result = $generator->generate();

        $output->writeln('<info>Finished</info>');

        return self::SUCCESS;
    }

    protected function validateAndLoadInputFile($fileName): array
    {
        if (!realpath($fileName)) {
            $fileName = getcwd() . '/' . $fileName;
        }
        if (!file_exists($fileName)) {
            throw new RuntimeException(sprintf('The provided file "%s" does not exist.', $fileName));
        }

        try {
            $jsonObject = json_decode(file_get_contents($fileName), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException(
                sprintf(
                    'The provided file "%s" can not be loaded as JSON. JSON Exception: %s',
                    $fileName,
                    $e->getMessage()
                ),
                0,
                $e
            );
        }

        return $jsonObject;
    }
}
