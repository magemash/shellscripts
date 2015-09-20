<?php

require_once '../app' . DIRECTORY_SEPARATOR . 'Mage.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Parser;

$console = new Application();

$finder = new Finder();
$finder->files()->in('../vendor')->name('*config.yml');
$yaml = new Parser();

foreach ($finder as $file) {
    $content = $yaml->parse(file_get_contents($file));

    foreach ($content['commands'] as $command) {
        $console->add(new $command());
    }
}

$console->run();
