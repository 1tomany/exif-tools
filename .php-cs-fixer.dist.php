<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfig;

$finder = new Finder();

$finder->in([
    './examples/',
    './src/',
    './tests/',
]);

$config = new Config()
    ->setFinder($finder)
    ->setParallelConfig(new ParallelConfig(4))
    ->setCacheFile('./.build/php-cs-fixer.cache')
    ->setRules([
        '@Symfony' => true,
        'global_namespace_import' => [
            'import_classes' => false,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'phpdoc_align' => [
            'align' => 'left',
        ],
    ]);

return $config;
