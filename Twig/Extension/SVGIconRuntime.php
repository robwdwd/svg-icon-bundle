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

use Robwdwd\SVGIconBundle\SVGIcon;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * SVG Icon bundle twig runtime extension.
 *
 * @author Rob Woodward <rob@emailplus.org>
 */
class SVGIconRuntime implements RuntimeExtensionInterface
{
    private $svgIcon;

    /**
     * @param SVGIcon $svgIcon SVGIcon service from this bundle
     */
    public function __construct(SVGIcon $svgIcon)
    {
        $this->svgIcon = $svgIcon;
    }

    /**
     * Convert SVG icon file to HTML Markup string for Twig template.
     *
     * @param string $package    icon package key as defined in configuration
     * @param string $iconName   icon name (filename without extension)
     * @param array  $attributes optional attributes to add to the SVG tag
     * @param array  $styles     optional styles to add to the SVG tag
     *
     * @return string|null HTML markup
     */
    public function inLineHTML(string $package, string $iconName, $attributes = [], $styles = [])
    {
        $this->svgIcon->loadSVG($package, $iconName, $attributes, $styles);

        return $this->svgIcon->toXMLString();
    }
}
