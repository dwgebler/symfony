<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!file_exists(__DIR__.'/src')) {
    exit(0);
}

$fileHeaderComment = <<<'EOF'
This file is part of the Symfony package.

(c) Fabien Potencier <fabien@symfony.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PHP71Migration' => true,
        '@PHPUnit75Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'protected_to_private' => false,
        'native_constant_invocation' => ['strict' => false],
        'no_superfluous_phpdoc_tags' => [
            'remove_inheritdoc' => true,
            'allow_unused_params' => true, // for future-ready params, to be replaced with https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/issues/7377
        ],
        'nullable_type_declaration_for_default_null_value' => true,
        'header_comment' => ['header' => $fileHeaderComment],
        'modernize_strpos' => true,
        'get_class_to_class_keyword' => true,
        'nullable_type_declaration' => true,
        'ordered_types' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'match', 'parameters']],
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in(__DIR__.'/src')
            ->append([__FILE__])
            ->notPath('#/Fixtures/#')
            ->exclude([
                // directories containing files with content that is autogenerated by `var_export`, which breaks CS in output code
                // fixture templates
                'Symfony/Bundle/FrameworkBundle/Tests/Templating/Helper/Resources/Custom',
                // resource templates
                'Symfony/Bundle/FrameworkBundle/Resources/views/Form',
                // explicit trigger_error tests
                'Symfony/Bridge/PhpUnit/Tests/DeprecationErrorHandler/',
                'Symfony/Component/Intl/Resources/data/',
            ])
            // explicit tests for ommited @param type, against `no_superfluous_phpdoc_tags`
            ->notPath('Symfony/Component/PropertyInfo/Tests/Extractor/PhpDocExtractorTest.php')
            ->notPath('Symfony/Component/PropertyInfo/Tests/Extractor/PhpStanExtractorTest.php')
            // Support for older PHPunit version
            ->notPath('Symfony/Bridge/PhpUnit/SymfonyTestsListener.php')
            ->notPath('#Symfony/Bridge/PhpUnit/.*Mock\.php#')
            ->notPath('#Symfony/Bridge/PhpUnit/.*Legacy#')
            // file content autogenerated by `var_export`
            ->notPath('Symfony/Component/Translation/Tests/Fixtures/resources.php')
            // file content autogenerated by `VarExporter::export`
            ->notPath('Symfony/Component/Serializer/Tests/Fixtures/serializer.class.metadata.php')
            // test template
            ->notPath('Symfony/Bundle/FrameworkBundle/Tests/Templating/Helper/Resources/Custom/_name_entry_label.html.php')
            // explicit trigger_error tests
            ->notPath('Symfony/Component/ErrorHandler/Tests/DebugClassLoaderTest.php')
            // stop removing spaces on the end of the line in strings
            ->notPath('Symfony/Component/Messenger/Tests/Command/FailedMessagesShowCommandTest.php')
            // auto-generated proxies
            ->notPath('Symfony/Component/Cache/Traits/RelayProxy.php')
            ->notPath('Symfony/Component/Cache/Traits/Redis5Proxy.php')
            ->notPath('Symfony/Component/Cache/Traits/Redis6Proxy.php')
            ->notPath('Symfony/Component/Cache/Traits/RedisCluster5Proxy.php')
            ->notPath('Symfony/Component/Cache/Traits/RedisCluster6Proxy.php')
    )
    ->setCacheFile('.php-cs-fixer.cache')
;
