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

    public $outputHandler;

    public function __construct()
    {
        parent::__construct();
    }


    /**
    * Start executing drush scripts.
    */
    public function execute()
    {
        $drush = parent::getDrush();
        $drush->runCommand('@none', 'corz-status');
        if ($error_log = $drush->getErrorLog()) {
            // Show error.
            foreach ($error_log as $type => $error) {
                $errors[$type] = array_shift($error);
            }
            $this->writeOutput(implode("\n", $errors), 'error');
        }
    }
}
