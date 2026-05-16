<?php

$finder = PhpCsFixer\Finder::create()
    ->name('.php_cs')
    ->exclude('vendor')
    ->exclude('.git')
    ->exclude('coverage')
    ->exclude('ProtobufPrinter')
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setRules([
        '@auto' => true
    ])
    ->setFinder($finder);
