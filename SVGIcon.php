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

namespace Robwdwd\SVGIconBundle;

use Robwdwd\SVGIconBundle\Exception\IconNotFoundException;
use Robwdwd\SVGIconBundle\Exception\IconPackageNotFoundException;
use Robwdwd\SVGIconBundle\Exception\SVGIconException;
use RuntimeException;
use SVG\Reading\AttributeRegistry;
use SVG\SVG;
use Symfony\Component\Asset\Packages;

/**
 * SVG Icon service to read files and output HTML Markup.
 *
 * @author Rob Woodward <rob@twfmail.uk>
 */
class SVGIcon
{
    private SVG $svg;

    // Set up some default attributes
    //
    private array $attributes = ['width' => 16, 'height' => 16, 'viewBox' => '0 0 16 16'];

    private array $styles = ['fill' => 'currentColor'];

    /**
     * @param Packages $packages Symfony asset package service
     * @param array    $icons    Icons configuration array
     */
    public function __construct(private readonly Packages $packages, private array $icons)
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
    public function loadSVG(string $package, string $name, array $attributes = [], array $styles = []): void
    {
        if (!isset($this->icons[$package])) {
            throw new IconPackageNotFoundException(sprintf("Icon package '%s' not found", $package));
        }

        // Find out where the icon is on the filesystem.
        //
        $baseDir = $this->icons[$package]['base_dir'];
        $iconDir = $this->icons[$package]['icon_dir'];

        $icon = $iconDir . '/' . $name . '.svg';

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
            $filename = $baseDir . '/' . $iconDir . '/' . basename($this->packages->getUrl($icon));
        } else {
            $filename = $baseDir . '/' . $icon;
        }

        if (!file_exists($filename)) {
            throw new IconNotFoundException($package, $name, $filename);
        }

        // Load the image from the file.
        //
        try {
            $this->svg = SVG::fromFile($filename);
        } catch (RuntimeException $runtimeException) {
            throw new SVGIconException('Unable to parse svg icon file', 0, $runtimeException);
        }

        // Get the svg document node, get all the generated attributes and styles
        // and finally merge in these with defaults and user overides.
        //
        $svgDocumentFragment = $this->svg->getDocument();
        $iconAttr = array_merge($this->attributes, $svgDocumentFragment->getSerializableAttributes(), $dimensions, $attributes);
        $iconStyles = array_merge($this->styles, $svgDocumentFragment->getSerializableStyles(), $styles);

        $this->setAttributes($iconAttr);
        $this->setStyles($iconStyles);
    }

    /**
     * Convert SVG Icon into HTML Markup string.
     *
     * @param bool $inclXMLHeader include XML header in output
     *
     * @return string HTML Markup for SVG Icon
     */
    public function toXMLString(bool $inclXMLHeader = false): string
    {
        return $this->svg->toXMLString($inclXMLHeader);
    }

    /**
     * Set additional styles on the SVG Icon.
     *
     * @param array $styles Additional styles to add to the svg tag
     */
    public function setStyles(array $styles): void
    {
        $svgDocumentFragment = $this->svg->getDocument();
        foreach ($styles as $tag => $value) {
            $svgDocumentFragment->setStyle($tag, $value);
        }
    }

    /**
     * Set additional attributes on the SVG Icon.
     *
     * @param array $attributes Additional attributes to add to the svg tag
     */
    public function setAttributes(array $attributes): void
    {
        $svgDocumentFragment = $this->svg->getDocument();
        foreach ($attributes as $tag => $value) {
            if ('style' === $tag) {
                continue;
            }

            if (AttributeRegistry::isStyle($tag)) {
                $convertedValue = AttributeRegistry::convertStyleAttribute($tag, $value);
                $svgDocumentFragment->setStyle($tag, $convertedValue);
                continue;
            }

            $svgDocumentFragment->setAttribute($tag, $value);
        }
    }
}
