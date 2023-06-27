<?php
/**
 *     Copyright 2021 Robert Woodward.
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

namespace Robwdwd\SVGIconBundle;

use Robwdwd\SVGIconBundle\Exception\IconNotFoundException;
use SVG\Reading\AttributeRegistry;
use SVG\SVG;
use Symfony\Component\Asset\Packages;

/**
 * SVG Icon service to read files and output HTML Markup.
 *
 * @author Rob Woodward <rob@emailplus.org>
 */
class SVGIcon
{
    private ?SVG $svgImage = null;

    // Set up some default attributes
    //
    private array $attributes = ['width' => 16, 'height' => 16, 'viewBox' => '0 0 16 16'];
    private array $styles = ['fill' => 'currentColor'];

    /**
     * @param Packages $packages Symfony asset package service
     * @param array    $icons    Icons configuration array
     */
    public function __construct(private Packages $packages, private $icons)
    {
    }

    /**
     * Loads the SVG icon file into the class.
     *
     * @param string $package    icon package key as defined in configuration
     * @param string $name       icon name (filename without extension)
     * @param array  $attributes optional attributes to add to the SVG tag
     * @param array  $styles     optional styles to add to the SVG tag
     */
    public function loadSVG($package, $name, $attributes = [], $styles = [])
    {
        if (!isset($this->icons[$package])) {
            return;
        }

        // Find out where the icon is on the filesystem.
        //
        $baseDir = $this->icons[$package]['base_dir'];
        $iconDir = $this->icons[$package]['icon_dir'];

        $icon = $iconDir.'/'.$name.'.svg';

        // If config includes user override width and height
        //
        $dimensions = [];

        if (isset($this->icons[$package]['width'])) {
            $dimensions['width'] = $this->icons[$package]['width'];
        }

        if (isset($this->icons[$package]['height'])) {
            $dimensions['height'] = $this->icons[$package]['height'];
        }

        if (true === $this->icons[$package]['webpack']) {
            // getUrl returns the public URL of the asset, we only want the filename
            //
            $filename = $baseDir.'/'.$iconDir.'/'.basename((string) $this->packages->getUrl($icon));
        } else {
            $filename = $baseDir.'/'.$icon;
        }

        if (file_exists($filename)) {
            // Load the image from the file.
            //
            $this->svgImage = SVG::fromFile($filename);

            // Get the svg document node, get all the generated attributes and styles
            // and finally merge in these with defaults and user overides.
            //
            $document = $this->svgImage->getDocument();
            $iconAttr = array_merge($this->attributes, $document->getSerializableAttributes(), $dimensions, $attributes);
            $iconStyles = array_merge($this->styles, $document->getSerializableStyles(), $styles);

            $this->setAttributes($iconAttr);
            $this->setStyles($iconStyles);
        } else {
            throw new IconNotFoundException($package, $name, $filename);
        }
    }

    /**
     * Convert SVG Icon into HTML Markup string.
     *
     * @param bool $inclXMLHeader include XML header in output
     *
     * @return string|null HTML Markup for SVG Icon
     */
    public function toXMLString($inclXMLHeader = false)
    {
        if ($this->svgImage) {
            return $this->svgImage->toXMLString($inclXMLHeader);
        }
    }

    /**
     * Set additional styles on the SVG Icon.
     *
     * @param array $styles Additional styles to add to the svg tag
     */
    public function setStyles(array $styles)
    {
        if (!$this->svgImage) {
            return;
        }
        $doc = $this->svgImage->getDocument();
        foreach ($styles as $tag => $value) {
            $doc->setStyle($tag, $value);
        }
    }

    /**
     * Set additional attributes on the SVG Icon.
     *
     * @param array $attributes Additional attributes to add to the svg tag
     */
    public function setAttributes(array $attributes)
    {
        if (!$this->svgImage) {
            return;
        }

        $doc = $this->svgImage->getDocument();
        foreach ($attributes as $tag => $value) {
            if ('style' === $tag) {
                continue;
            }
            if (AttributeRegistry::isStyle($tag)) {
                $convertedValue = AttributeRegistry::convertStyleAttribute($tag, $value);
                $doc->setStyle($tag, $convertedValue);
                continue;
            }

            $doc->setAttribute($tag, $value);
        }
    }
}
