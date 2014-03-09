<?php
/**
 * Voilab - Twig extensions for Slim
 *
 * @author      Alexandre Ravey
 * @link        http://www.voilab.org
 * @copyright   2014 Voilab
 * @version     0.1.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Voilab\Slim\Views;

use Slim\Slim;

class TwigExtension extends \Twig_Extension
{
	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName() {
		return 'voilab-slim';
	}

	/**
	 * Returns a list of functions for Twig to expose.
	 *
	 * @return array List of functions
	 */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('rootUri', array($this, 'rootUri')),
            new \Twig_SimpleFunction('currentRoute', array($this, 'currentRoute'))
		);
	}

	/**
	 * Returns a simple root URI for this application. Without the URL part.
	 *
	 * @param  string $appName Application name or 'default'
	 * @return string          The base URI
	 */
	public function rootUri($appName = 'default') {
		return Slim::getInstance($appName)->request()->getRootUri();
	}

    /**
     * Returns the current route displayed
     *
     * @param string $appName
     * @return string
     */
    public function currentRoute($appName = 'default') {
        $request = Slim::getInstance($appName)->request();
        return $request->getPath();
    }
}
