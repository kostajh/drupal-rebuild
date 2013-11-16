<?php

namespace DrupalRebuild\Drush;

use DrupalRebuild;

use Symfony\Component\Process\Process;

define('DRUPAL_REBUILD_BACKEND_OUTPUT_START', 'DRUSH_BACKEND_OUTPUT_START>>>');
define('DRUPAL_REBUILD_BACKEND_OUTPUT_DELIMITER', DRUPAL_REBUILD_BACKEND_OUTPUT_START . '%s<<<DRUSH_BACKEND_OUTPUT_END');

class Drush
{

    private $drushExecutable;
    private $process;
    private $backendOutput;
    private $errorStatus = 0;
    private $errorLog = array();
    private $errorLogString;

    public function __construct($drushExecutable = '')
    {
        $this->drushExecutable = !empty($drushExecutable) ? $drushExecutable : __DIR__ . '/../../../vendor/bin/drush';
    }

    public function runCommand($env = '', $command, $args = array(), $options = array())
    {
        // Set path to Drush.
        $cmd = array();
        $cmd[] = $this->drushExecutable;
        $cmd[] = $env;
        $cmd[] = $command;
        $cmd[] = implode(' ', $args);
        foreach ($options as $name => $value) {
          $cmd[] = sprintf('--%s=%s', $name, $value);
        }
        $cmd[] = '--backend';
        $cmd_string = implode(' ', $cmd);
        $process = new Process($cmd_string);
        $process->run();
        $this->process = $process;
        $this->backendOutput = $process->getOutput();
        $this->parsedBackendOutput = $this->parseBackendOutput();
        if ($this->parsedBackendOutput['error_status'] == 1) {
            $this->setErrorStatus(1);
            $this->setErrorLog($this->parsedBackendOutput['error_log']);
        }

        return $this;
     }

     private function parseBackendOutput()
     {
        $string = $this->backendOutput;
        $regex = sprintf(DRUPAL_REBUILD_BACKEND_OUTPUT_DELIMITER, '(.*)');
        preg_match("/$regex/s", $string, $match);
        if ($match[1]) {
            // we have our JSON encoded string
            $output = $match[1];
            // remove the match we just made and any non printing characters
            $string = trim(str_replace(sprintf(DRUPAL_REBUILD_BACKEND_OUTPUT_DELIMITER, $match[1]), '', $string));
        }

        if ($output) {
            $data = json_decode($output, TRUE);
            if (is_array($data)) {
                return $data;
            }
        }

        return $string;
     }

     public function setErrorStatus($status)
     {
        $this->errorStatus = $status;

        return $this;
     }

     public function setErrorLog($log)
     {
        $this->errorLog = $log;
        $this->setErrorLogString($log);

        return $this;
     }

     private function setErrorLogString()
     {
        $log = $this->errorLog;
        $string = '';
        foreach ($log as $type => $message) {
            $string .= sprintf('%s: %s', $type, implode(', ', $message));
        }
        $this->errorLogString = $string;

        return $this;
     }

     public function getErrorStatus()
     {
        return $this->errorStatus;
     }

     public function getErrorLog()
     {
        return $this->errorLog;
     }

     public function getErrorLogString()
     {
        return $this->errorLogString;
     }

     public function getBackendOutput()
     {
        return $this->backendOutput();
     }

     public function getParsedBackendOutput()
     {
        return $this->parsedBackendOutput;
     }
}
