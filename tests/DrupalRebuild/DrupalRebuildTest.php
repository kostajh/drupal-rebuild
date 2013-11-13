<?php

namespace DrupalRebuild;

use DrupalRebuild\DrupalRebuild;
use DrupalRebuild\Drush;
use DrupalRebuild\Command\RebuildCommand;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class DrupalRebuildTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {

    }

    /**
     * @covers DrupalRebuild\Drush::__construct
     */
    public function testDrushConstructor()
    {
        $drush = new Drush;
        $this->assertNull($drush, 'message')
        print_r($drush);
    }
}
