<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @version     2.0.1
 * @package     Skinny
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
$dir = realpath(__DIR__ . '/../../');
require_once $dir . '/Skinny/Filter/HtmlEntities.php';
require_once $dir . '/Skinny/Filter/ImageSize.php';
require_once $dir . '/Skinny/Cache.php';
require_once $dir . '/Skinny/Cache/MemcacheService.php';
require_once $dir . '/Skinny/Form/Upload.php';
require_once $dir . '/Skinny/Validate/AbstractValidator.php';
require_once $dir . '/Skinny/Validate/File/Image.php';
require_once $dir . '/Skinny/Validate/Date.php';
require_once $dir . '/Skinny/Validate/EmailAddress.php';
require_once $dir . '/Skinny/Validate/NotEmpty.php';
require_once $dir . '/Skinny/Auth.php';
require_once $dir . '/Skinny/Storage.php';
require_once $dir . '/Skinny/Session.php';
require_once $dir . '/Skinny/Router.php';
require_once $dir . '/Skinny/Form.php';
require_once $dir . '/Skinny/Exception.php';

spl_autoload_register(
    function($class)
    {
        $dir = realpath(__DIR__ . '/../../');
        $target = explode('\\', $class);
        $path = $dir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $target) . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
);
