<?php

/**
 * @file
 * Drush script related code.
 */

namespace DrupalRebuild\Steps;


/**
 * Handles executing drush scripts.
 */
class DrushScript {

  /**
   * Constructor.
   *
   * @param string $state
   *   Where we are in the rebuild process. Valid options are 'pre_process',
   *   'post_process' and 'legacy'.
   * @param string $script
   *   Optional; provide the path to the script to execute.
   */
  public function __construct($state, $script = NULL) {

  }

  /**
   * Operate in legacy mode. Execute a drush-script to rebuild a site.
   */
  public function legacyMode() {

  }

  /**
   * Start executing drush scripts.
   */
  public function execute() {

  }

}
