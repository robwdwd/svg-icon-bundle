<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets()
    ->withPreparedSets(codeQuality: true, codingStyle: true, typeDeclarations: true, naming: true, deadCode: true)
    ->withAttributesSets(symfony: true)
    ->withSkip([
        __DIR__ . '/vendor',
    ])
    ->withPaths([
        __DIR__,
    ])
;
