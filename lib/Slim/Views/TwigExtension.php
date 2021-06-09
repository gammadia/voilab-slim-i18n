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
     * @return \Twig_SimpleFunction[] List of functions
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('rootUri', array($this, 'rootUri')),
            new \Twig_SimpleFunction('currentRoute', array($this, 'currentRoute')),
            new \Twig_SimpleFunction('urlForI18n', array($this, 'urlForI18n')),
            new \Twig_SimpleFunction('currentRouteName', array($this, 'currentRouteName')),
            new \Twig_SimpleFunction('currentRouteObject', array($this, 'currentRouteObject')),
            new \Twig_SimpleFunction('activeLang', array($this, 'activeLang'))
        );
    }

    /**
     * Returns a simple root URI for this application. Without the URL part.
     *
     * @param  string $appName Application name or 'default'
     * @return string          The base URI
     */
    public function rootUri($appName = 'default') {
        return $this->slim($appName)->request()->getRootUri();
    }

    /**
     * Returns the current route displayed
     *
     * @param string $appName
     * @return string
     */
    public function currentRoute($appName = 'default') {
        $request = $this->slim($appName)->request();
        return $request->getPathInfo();
    }

    /**
     * @param string $appName
     *
     * @return \Slim\Route
     */
    public function currentRouteObject($appName = 'default') {
        $slim = $this->slim($appName);
        $current_route = $slim->router()->getCurrentRoute();
        if (!$current_route) {
            $current_route = $slim->router()->getNamedRoute('home');
        }
        /** @var \Slim\Route $current_route */
        return $current_route;
    }

    /**
     * @param string $appName
     *
     * @return string|null
     */
    public function currentRouteName($appName = 'default') {
        $current_route = $this->currentRouteObject($appName);
        return $current_route->getName();
    }

    /**
     * @param string $lang
     * @param string $name
     * @param mixed[] $params
     * @param string $appName
     *
     * @return string
     */
    public function urlForI18n($lang, $name, $params = array(), $appName = 'default') {
        $slim = $this->slim($appName);
        return $slim->request->getRootUri() . '/' . $lang . $slim->router->urlFor($name, $params);
    }

    /**
     * @param string $appName
     *
     * @return mixed
     */
    public function activeLang($appName = 'default') {
        return $this->slim($appName)->view()->get('i18n.lang');
    }

    /**
     * @param string $appName
     *
     * @return Slim
     */
    private function slim($appName)
    {
        /** @var Slim $slim */
        $slim = Slim::getInstance($appName);

        return $slim;
    }
}
