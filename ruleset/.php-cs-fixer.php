<?php

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__.'/../src'])
    ->files()
    ->name('*.php');

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        'psr_autoloading' => true, // Updated from psr0 and psr4
        'ordered_imports' => true,
        'no_extra_blank_lines' => ['tokens' => ['use']], // Updated from no_extra_consecutive_blank_lines
        'php_unit_namespaced' => ['target' => '6.0'],
        'php_unit_expectation' => true,
    ])
    ->setFinder($finder);

return $config;
