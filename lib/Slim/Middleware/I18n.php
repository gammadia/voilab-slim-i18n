<?php
namespace Voilab\Slim\Middleware;

/**
 * I18n
 *
 * This is middleware for a Slim application that reorganize
 * the view templates directories to load first the chosen
 * language, then the fallback.
 *
 * @package    Slim
 * @author     Joel Poulin
 * @author      Alexandre Ravey
 * @link        http://www.voilab.org
 * @copyright   2014 Voilab
 * @version     0.1.0
 *
 *  Loosely based on http://nesbot.com/2012/6/26/multilingual-site-using-slim
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

class I18n extends \Slim\Middleware
{
    /**
     *  Settings
     *
     *  @var array
     */
    private $settings = array();

    /**
     *  Parse the Accept-Language header
     *
     *  @return array Array of languages without the q part.
     */
    private function getAcceptLanguage() {
        $accept = null;
        $env = $this->app->environment();

        if (isset($env['HTTP_ACCEPT_LANGUAGE'])) {
            $accept = $env['HTTP_ACCEPT_LANGUAGE'];
        }

        if (isset($env['ACCEPT_LANGUAGE'])) {
            $accept = $env['ACCEPT_LANGUAGE'];
        }

        if (!$accept) {
            return array();
        }

        return preg_split('/(;q=...)?,/i', $accept . ',', -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     *  Return the corresponding language for an alias
     *
     *  @param  string $alias Alias
     *
     *  @return string        Language code
     */
    private function getLangByAlias($alias) {
        foreach($this->app->container['settings']['i18n.langs'] as $lang => $lang_data) {
            if (in_array($alias, $lang_data['alias'])) {
                return $lang;
            }
        }

        return null;
    }

    /**
     *  Read the current language
     *
     *  @return string
     */
    private function getLang() {
        $env = $this->app->environment();
        $activ_lang = $this->app->container['settings']['i18n.default_lang'];

        if ($env['PATH_INFO'] == '/') {
            //  Webroot, no language defined.
            //  We parse the Accept-Language header
            $matches = array_intersect($this->getAcceptLanguage(), $this->app->container['settings']['i18n.priority']);
            $activ_lang = array_shift($matches);
            $activ_lang = $this->getLangByAlias($activ_lang);
        }

        if (!$activ_lang || $activ_lang === $this->app->container['settings']['i18n.default_lang']) {
            $pathInfo = $env['PATH_INFO'];

            if (strrpos($pathInfo, '/') !== 0) {
                $pathInfo .= '/';
            }

            //  Search a known language in the resource URL.
            foreach($this->app->container['settings']['i18n.langs'] as $lang => $lang_data) {
                if (strpos($pathInfo, '/' . $lang . '/') === 0) {
                    $activ_lang = $lang;
                    $env['PATH_INFO'] = substr($env['PATH_INFO'], 3);
                }
            }
        }

        // au cas où vraiment on trouve pas de langue...
        if (!$activ_lang) {
            $activ_lang = $this->app->container['settings']['i18n.default_lang'];
        }

        return $activ_lang;
    }

    /**
     * Constructor
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $defaults = array(
            'view' => 'twig',
            'langParam' => 'i18n.lang',
            'basePath' => '',
            'translatedPath' => '',
            'defaultLang' => 'fr'
        );
        $this->settings = array_merge($defaults, $settings);
    }

    /**
     * Call
     */
    public function call()
    {
        $lang = $this->getLang();
        $view = $this->app->view();

        switch ($this->settings['view']) {
            case 'twig' :
                if ($lang != $this->settings['defaultLang']) {
                    $view->twigTemplateDirs = array(
                        $this->settings['translatedPath'] . $lang,
                        $this->settings['basePath']
                    );
                } else {
                    $view->twigTemplateDirs = $this->settings['basePath'];
                }
                $view->set($this->settings['langParam'], $lang);
                break;
            default:
                break;
        }

        $this->next->call();
    }
}
