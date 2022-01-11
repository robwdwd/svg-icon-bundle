<?php
/**
*     Copyright 2021 Robert Woodward.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at

 *        http://www.apache.org/licenses/LICENSE-2.0

 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
*/

namespace Robwdwd\SVGIconBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * SVG Icon Twig extension.
 *
 * @author Rob Woodward <rob@emailplus.org>
 */
class SVGIconExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('svg_icon', [SVGIconRuntime::class, 'inlineHTML'], ['is_safe' => ['html']]),
        ];
    }
}
