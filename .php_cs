<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->name('*.php')
    ->exclude(array(
        'vendor'
    ))
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()
    ->finder($finder);