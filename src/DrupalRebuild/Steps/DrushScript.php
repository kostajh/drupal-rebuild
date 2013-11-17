<?php

/**
 * @file
 * Drush script related code.
 */

namespace DrupalRebuild\Steps;

use DrupalRebuild\DrupalRebuild;

/**
 * Handles executing drush scripts.
 */
class DrushScript extends DrupalRebuild
{

    public $state;

    public function __construct($state)
    {
        parent::__construct();
        $this->state = $state;
        $this->config = parent::getConfig();
        $this->environment = parent::getEnvironment();
        $this->outputHandler = parent::getOutputHandler();
        $this->target = parent::getTarget();
        $this->drush = parent::getDrush();
    }


    /**
    * Start executing drush scripts.
    */
    public function run()
    {
        $state = $this->state;
        if ($state == 'pre_process') {
            $target = '@none';
            $step = 'Drush Script - Pre Process';
        } else {
            $target = $this->target;
            $step = 'Drush Script - Post Process';
        }
        // Get scripts.
        $scripts = isset($this->config['drush_scripts'][$state]) ? $this->config['drush_scripts'][$state] : array();
        if (!$scripts) {
            return false;
        }
        foreach ($scripts as $script) {
            $rebuild_filepath = $this->environment['path-aliases']['%rebuild'];
            $file = str_replace(basename($rebuild_filepath), $script, $rebuild_filepath);
            if (file_exists($file)) {
                $this->outputHandler->writeln(sprintf('<info>Executing script \'%s\'', $script));
                $this->drush->runCommand($target, 'php-script', array($file));
                $backend_output = $this->drush->getParsedBackendOutput();
                if ($backend_output) {
                    $this->outputResults(sprintf('<comment>%s</comment>', $backend_output['output']), $step);
                }
                $this->outputResults(sprintf('<info>Successfully executed script \'%s\'<info>', $script), $step);
            } else {
                $this->outputHandler(sprintf('Failed to load script %s', $script), 'error');
            }
            if ($this->drush->getErrorStatus() == 1) {
                return false;
            }
        }
    }
}
