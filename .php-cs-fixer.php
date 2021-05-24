<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('bootstrap/*')
    ->notPath('storage/*')
    ->notPath('vendor')
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/database',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12'                                 => true,
    '@PHP71Migration:risky'                  => true,
    '@PHPUnit75Migration:risky'              => true,
    '@Symfony'                               => true,
    '@PhpCsFixer'                            => true,
    //'@PhpCsFixer:risky'                      => true,
    'array_syntax'                           => ['syntax' => 'short'],
    'ordered_imports'                        => ['sort_algorithm' => 'alpha'],
    'no_unused_imports'                      => true,
    'linebreak_after_opening_tag'            => true,
    'not_operator_with_successor_space'      => false,
    'trailing_comma_in_multiline'            => true,
    'phpdoc_scalar'                          => true,
    'unary_operator_spaces'                  => true,
    'concat_space'                           => ['spacing' => 'one'],
    'binary_operator_spaces'                 => ['operators' => ['=>' => 'align', '=' => 'align']],
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    'blank_line_before_statement'            => [
        'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
    ],
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_var_without_name'        => true,
    'class_attributes_separation'    => [
        'elements' => [
            'method', 'property', 'const',
        ],
    ],
    'method_argument_space' => [
        'on_multiline'                     => 'ensure_fully_multiline',
        'keep_multiple_spaces_after_comma' => true,
    ],
])
    ->setFinder($finder);
