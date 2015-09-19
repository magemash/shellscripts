<?php

use Symfony\Component\Console\Application;
use Magemash\Console\Command\HelloworldCommand;

$console = new Application();
$console->add(new HelloworldCommand());

$console->run();
