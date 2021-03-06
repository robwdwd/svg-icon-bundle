<?php
/**
 *    Copyright 2021 Robert Woodward.
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

namespace Robwdwd\SVGIconBundle\Exception;

/**
 * SVG Icon Exception.
 *
 * @author Rob Woodward <rob@emailplus.org>
 */
class IconNotFoundException extends \Exception
{
    public function __construct(string $package, string $name, string $filename)
    {
        parent::__construct(\sprintf('Could not find icon file for "%s" in "%s" package, path calculated as "%s".', $name, $package, $filename));
    }
}
