<?php
/**
 * Voilab - I18n extention for Slim
 *
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
namespace Voilab\Slim;

use Slim\Slim;

/**
 * @property \Slim\View $view
 */
class I18nSlim extends Slim {
    /**
     * Wrap Slim::urlFor() and prepend the language code
     *
     * @see Slim::urlFor()
     * @param string $name Route name
     * @param array<string, mixed> $params Array of parameters
     * @return string Url for the given resource
     */
    public function urlFor($name, $params = array()) {
        if (isset($params['lang'])) {
            $lang = $params['lang'];
        } else {
            $lang = $this->view->get('i18n.lang');
        }

        return $this->request->getRootUri() . '/' . $lang . $this->router->urlFor($name, $params);
    }

    /**
     * Récupération de la langue active
     *
     * @return string
     */
    public function guessActiveLang() {
        $path = substr($this->request()->getPathInfo(), 1);
        return substr($path, 0, 2);
    }
}
