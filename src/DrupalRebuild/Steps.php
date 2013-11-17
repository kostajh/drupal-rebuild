<?php

namespace DrupalRebuild;

interface Steps extends DrupalRebuild
{
    public function execute();
    public function setDrush($drush);
    public function setOptions($options = array());
    public function setOutput($output);
}
