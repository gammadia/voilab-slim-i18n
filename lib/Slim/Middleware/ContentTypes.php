<?php
/**
 * Slim - a micro PHP 5 framework
 *
 * @author      Josh Lockhart <info@slimframework.com>
 * @copyright   2011 Josh Lockhart
 * @link        http://www.slimframework.com
 * @license     http://www.slimframework.com/license
 * @version     2.4.2
 * @package     Slim
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
namespace Voilab\Slim\Middleware;

 /**
  * Content Types
  *
  * This is middleware for a Slim application that intercepts
  * the HTTP request body and parses it into the appropriate
  * PHP data structure if possible; else it returns the HTTP
  * request body unchanged. This is particularly useful
  * for preparing the HTTP request body for an XML or JSON API.
  *
  * @package    Slim
  * @author     Josh Lockhart
  * @since      1.6.0
  */
class ContentTypes extends \Slim\Middleware\ContentTypes
{

    /**
     * Constructor
     * @param mixed[] $settings
     */
    public function __construct($settings = array())
    {
        $defaults = array(
            'multipart/form-data' => array($this, 'parseRaw')
        );
        $defaults = array_merge($defaults, $settings);
        parent::__construct($defaults);
    }

    /**
     * @return mixed[]
     */
    protected function parseRaw() {
        $data = $_POST;
        foreach ($_FILES as $file) {
            $data['files'][] = $file;
        }

        return $data;
    }
}
