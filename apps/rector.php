<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    // get parameters
    $parameters = $rectorConfig->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);

    // Define what rule sets will be applied
    $rectorConfig->import(LevelSetList::UP_TO_PHP_81);

    // get services (needed for register a single rule)
    $services = $rectorConfig->services();

    // register a single rule
    $services->set(AnnotationToAttributeRector::class)->configure([new AnnotationToAttribute('Symfony\Component\Routing\Annotation\Route')]);
};
