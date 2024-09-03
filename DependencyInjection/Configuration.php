<?php
/**
 *    Copyright 2021-2024 Robert Woodward.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace Robwdwd\SVGIconBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * SVG Icon bundle configuration parser/tree builder.
 *
 * @author Rob Woodward <rob@twfmail.uk>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('svg_icon');
        $nodeDefinition = $treeBuilder->getRootNode();

        $nodeDefinition
            ->children()
            ->arrayNode('icons')
            ->info('List of icon packs.')
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->children()
            ->scalarNode('icon_dir')
            ->info('Sub-Directory of the base direction where icons are stored. When webpack is enabled this is used as the manifest key.')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('base_dir')
            ->info('Base directory of public site assets.')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('width')
            ->info('Default icon width.')
            ->end()
            ->scalarNode('height')
            ->info('Default icon height.')
            ->end()
            ->booleanNode('webpack')
            ->info('Enable webpack for this icon set. Will use manifest to get icon filename.')
            ->defaultFalse()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
