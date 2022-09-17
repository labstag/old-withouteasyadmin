<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheClass(FileCacheStorage::class);
    $rectorConfig->cacheDirectory('./var/cache/rector');
    $rectorConfig->importNames();
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->rules(
        [
            InlineConstructorDefaultToPropertyRector::class,
            ArrayKeyExistsTernaryThenValueToCoalescingRector::class,
            ArrayMergeOfNonArraysToSimpleArrayRector::class,
            EncapsedStringsToSprintfRector::class,
        ]
    );
    $rectorConfig->ruleWithConfiguration(
        AnnotationToAttributeRector::class,
        [
            new AnnotationToAttribute('Symfony\Component\Routing\Annotation\Route'),
        ]
    );
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');
    // define sets of rules
    $rectorConfig->sets([
        SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        // SetList::DEAD_CODE,
        SetList::NAMING,
        SetList::PHP_81,
        SetList::PSR_4,
        // SetList::TYPE_DECLARATION,
        LevelSetList::UP_TO_PHP_81
    ]);
};
