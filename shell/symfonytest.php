<?php

require_once '../app' . DIRECTORY_SEPARATOR . 'Mage.php';

use Symfony\Component\Console\Application;
use MagentoCommand\Command\HelloworldCommand;

$console = new Application();
$console->add(new HelloworldCommand());

$console->run();
