<?php

namespace DrupalRebuild\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;
use DrupalRebuild\Drush\Drush;
use DrupalRebuild\DrupalRebuild;

class RebuildCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('site-rebuild')
            ->setDescription('Rebuild a local Drupal site environment.')
            ->addArgument(
                'DrushAlias',
                InputArgument::REQUIRED,
                'The Drush alias for the local environment you want to rebuild.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = $input->getArgument('DrushAlias');
        $rebuild = new DrupalRebuild($alias);
        $rebuild->setOutputHandler($output);
        $output->writeln(sprintf('<info>Loaded alias for %s</info>', $alias));
        $env = $rebuild->getEnvironment();
        $output->writeln(sprintf('<info>Docroot: %s</info>', $env['root']));
        $output->writeln(sprintf('<info>URI: %s</info>', $env['uri']));
        $output->writeln(sprintf('<info>Rebuild Config: %s</info>', $env['path-aliases']['%rebuild']));
        $rebuild->run();
    }
}
