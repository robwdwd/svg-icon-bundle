# SVGIcon Symfony Bundle

Symfony Bundle for reading SVG Files and outputting in HTML Markup.

## What is the SVGIcon Bundle?

SVGIcon is a symfony bundle to read SVG files (such as material design
icons, bootstrap icons, etc) and can be used to output inline HTML either
with the twig function or by using SVGIcon as a service.

## Features

SVGIcon supports the following:

-   Twig function for easy output of inline HTML in templates.
-   Reads SVG Icon files (from bootstrap-icons, material design icons.)
-   Overwrite styles and tags (width, height, etc) and provides useful
    defaults if not found in SVG Icon.
-   Support webpack encore for icons in public build location using
    manifest file.

## Requirements

SVGIcon PHP Class requires the following:

-   PHP 7.2 or higher
-   meyfa/php-svg

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require robwdwd/svg-icon-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require robwdwd/svg-icon-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Robwdwd\SVGIconBundle\SVGIconBundleBundle::class => ['all' => true],
];
```

## Configuration

Configuration is done in `config/packages/robwdwd_svg_icon.yaml` although this
can be any filename.

Here is an example configuration using webpack encore for material design
icons and a normal file path for bootstrap icons.

```yaml
svg_icon:
    icons:
        mdi:
            base_dir: '%kernel.project_dir%/public'
            icon_dir: 'build/icons/mdi'
            webpack: true
        bsi:
            base_dir: '%kernel.project_dir%'
            icon_dir: 'node_modules/bootstrap-icons/icons'
            webpack: false
```

For webpack encore managed icons the `base_dir` for each icon should be the public
directory of your symfony project and the `icon_dir` should be the build
directory and the subdirectory under it where webpack encore copies the icon
files. The `icon_dir` plus the icon filename is used to lookup the icon in
the webpack manifest.json.

For icons not managed by webpack Encore (for example directly from the
node_modules directory) set the `base_dir` to be `%kernel.project_dir%` and
the `icon_dir` to be the location under and including node_modules. However
`icon_dir` and `base_dir` are simply concatenated together for icon packages
not managed by webpack encore.

## Icon installation

Icons should be installed as per the normal icon installation process. For
example to install bootstrap icons and material design icons.

```console
yarn add bootstrap-icons --dev
yarn add @mdi/svg --dev
```

## Webpack Encore

Use webpack encore to copy the SVG icons to your build directory by using
[copyFiles](https://symfony.com/doc/current/frontend/encore/copy-files.html).
The following example copies the material design icons to your public build
directory under icons/mdi and the boostrap icons under icons/bsi. Versioning
will be used if you have it enabled.

With webpack set to true in the configuration file the bundle will load the
icon file by looking up it's location in the default webpack manifest.

```javascript
.copyFiles({
        from: './node_modules/@mdi/svg/svg',
        to: 'icons/mdi/[path][name].[hash:8].[ext]',
        pattern: /\.svg$/
    })

.copyFiles({
        from: './node_modules/bootstrap-icons/icons',
        to: 'icons/bsi/[path][name].[hash:8].[ext]',
        pattern: /\.svg$/
    })
```

## Usage and Examples

The bundle provides a twig function and a service to load and get the HTML
for SVG icons.

### Default attributes and styles.

By default the bundle sets width and height to 16 and the viewBox to 0 0 16 16
and fill to currentColor. These will be used if the SVG icon does not already
have these attributes or you have not overwritten them. The bundle takes the
follow precedence order: Default->SVG File->Overwrite. Overwrite will always be
used over the attributes/styles in the SVG file and the defaults. SVG File always
overwrites the default.

### Twig

The SVGIcon bundle provides a twig function to inline SVG icon HTML markup
into templates. The function takes the icon package name and the name of
the icon (filename without the .svg extension). It allows for optional attributes
and styles to be applied to the icon.

    svg_icon('<icon package>', '<icon name>', {[attributes]}, {[styles]})

#### Simple use

To embed to Material design pencil icon into your html use the following. Any
attributes and styles from the icon will be used as well as the defaults if the
icon does not include them.

    {{ svg_icon('mdi', 'pencil') }}

#### Advanced use

To overwrite the width and height attributes.

    {{ svg_icon('mdi', 'eye-outline', {'width': 18, 'height': 18}) }}

Set the width and height and class and set the fill colour to red.

    {{ svg_icon('mdi', 'eye-outline', {'width': 18, 'height': 18, 'class':'svgicon'}, {'fill':'red'}) }}

You can also set the fill colour as an attribute which will get converted into
a style automatically. Any attribute which should be a style will be converted.

    {{ svg_icon('mdi', 'eye-outline', {'width': 18, 'height': 18, 'class':'svgicon', 'fill':'red'}) }}

### As a service

You can use the SVGIcon service in your controllers or services to manually
generate SVGIcons.

```PHP
use Robwdwd\SVGIconBundle\SVGIcon;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(SVGIcon $svgIcon) {

        // Load the SVG
        $svgIcon->loadSVG('mdi', 'magnify', ['class' => 'svgicon'], ['fill' => 'currentColor']);

        // Get HTML for SVG
        $searchIcon = $svgIcon->toXMLString();

        // HTML with XML header
        dump($svgIcon->toXMLString(true));

        return $this->render('index.html.twig', ['search_icon' => $searchIcon]);
    }
}
```
