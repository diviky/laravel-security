<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src/')
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        '@PHP56Migration' => true,
        '@PhpCsFixer' => true,
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'native_function_invocation' => ['scope' => 'namespaced'],
        'no_alias_functions' => ['sets' => ['@all']],
        'concat_space' => ['spacing' => 'one'],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'binary_operator_spaces' => ['operators' => ['=>' => 'align', '=' => 'align']],
    ))
    ->setFinder($finder);