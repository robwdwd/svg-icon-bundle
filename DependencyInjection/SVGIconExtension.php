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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Twig\Extension\AbstractExtension;

/**
 * SVG Icon bundle dependency injection.
 *
 * @author Rob Woodward <rob@twfmail.uk>
 */
class SVGIconExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $xmlFileLoader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlFileLoader->load('services.xml');

        if (class_exists(AbstractExtension::class)) {
            $xmlFileLoader->load('twig.xml');
        }

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $containerBuilder->getDefinition('svg_icon.svg_icon');
        $definition->setArgument(1, $config['icons']);
    }
}
