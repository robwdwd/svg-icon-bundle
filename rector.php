<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets()
    ->withPreparedSets(codeQuality: true, codingStyle: true, typeDeclarations: true)
    ->withAttributesSets(symfony: true, doctrine: true)
    ->withSkip([
        __DIR__ . '/vendor',
    ])
    ->withPaths([
        __DIR__,
    ])
;
