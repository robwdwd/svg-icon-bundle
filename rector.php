<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: false,
        naming: true,
        instanceOf: true,
        earlyReturn: false,
        strictBooleans: false,
    )
    ->withAttributesSets(symfony: true)
    ->withImportNames()
    ->withSkip([
        __DIR__ . '/vendor',
    ])
    ->withPaths([
        __DIR__,
    ])
;
