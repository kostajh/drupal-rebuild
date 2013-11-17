<?php

namespace DrupalRebuild\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressHelper;
use DrupalRebuild\Drush\Drush;
use DrupalRebuild\DrupalRebuild;
use DrupalRebuild\Steps\DrushScript;

class RebuildCommand extends Command
{
    /**
     * Set command name.
     */
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
        // TODO: validate alias.
        $rebuild = new DrupalRebuild();
        $rebuild->setOutputHandler($output);
        // TODO: Preflight.
        if (!$rebuild->initialize($alias)) {
            return false;
        }
        $env = $rebuild->getEnvironment();
        $output->writeln(sprintf('<info>Loaded alias for %s</info>', $alias));
        $output->writeln(sprintf('<info>Docroot: %s</info>', $env['root']));
        $output->writeln(sprintf('<info>URI: %s</info>', $env['uri']));
        $output->writeln(sprintf('<info>Rebuild Config: %s</info>', $env['path-aliases']['%rebuild']));
        $rebuild->run();
    }
}
