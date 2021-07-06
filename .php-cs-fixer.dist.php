<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR2' => true,
    '@PSR12' => true,
    '@Symfony' => true,
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'method_argument_space' => ['on_multiline' => 'ensure_single_line'],
    'no_useless_else' => true,
    'no_useless_return' => true,
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
    'no_superfluous_phpdoc_tags' => false,
    'phpdoc_no_empty_return' => true,
    'phpdoc_order' => true,
    'phpdoc_separation' => true,
    'phpdoc_var_annotation_correct_order' => true,
])
    ->setFinder($finder)
;
