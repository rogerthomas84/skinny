<?php /** @noinspection SpellCheckingInspection */

/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       2.0
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

namespace Skinny\Filter;

use Skinny\BaseException;

/**
 * HtmlEntities
 *
 * This class provides a simple way of using HTML Entities
 * Copied from Zend_Filter_HtmlEntities
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 *
 */
class HtmlEntities
{
    /**
     * Corresponds to the second htmlentities() argument
     *
     * @var int
     */
    protected int $_quoteStyle;

    /**
     * Corresponds to the third htmlentities() argument
     *
     * @var string
     */
    protected string $_encoding;

    /**
     * Corresponds to the forth htmlentities() argument
     *
     * @var bool
     */
    protected bool $_doubleQuote;

    /**
     * Sets filter options
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['quotestyle'])) {
            $options['quotestyle'] = ENT_COMPAT;
        }
        if (!isset($options['encoding'])) {
            $options['encoding'] = 'UTF-8';
        }
        if (isset($options['charset'])) {
            $options['encoding'] = $options['charset'];
        }
        if (!isset($options['doublequote'])) {
            $options['doublequote'] = true;
        }

        $this->setQuoteStyle($options['quotestyle']);
        $this->setEncoding($options['encoding']);
        $this->setDoubleQuote($options['doublequote']);
    }

    /**
     * Returns the quoteStyle option
     *
     * @return int
     */
    public function getQuoteStyle(): int
    {
        return $this->_quoteStyle;
    }

    /**
     * Sets the quoteStyle option
     *
     * @param int $quoteStyle
     * @return HtmlEntities Provides a fluent interface
     */
    public function setQuoteStyle(int $quoteStyle): static
    {
        $this->_quoteStyle = $quoteStyle;
        return $this;
    }


    /**
     * Get encoding
     *
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->_encoding;
    }

    /**
     * Set encoding
     *
     * @param string $value
     * @return HtmlEntities
     */
    public function setEncoding(string $value): static
    {
        $this->_encoding = $value;
        return $this;
    }

    /**
     * Returns the charSet option
     *
     * Proxies to {@link getEncoding()}
     *
     * @return string
     */
    public function getCharSet(): string
    {
        return $this->getEncoding();
    }

    /**
     * Sets the charSet option
     *
     * Proxies to {@link setEncoding()}
     *
     * @param string $charSet
     * @return HtmlEntities
     */
    public function setCharSet(string $charSet): HtmlEntities|static
    {
        return $this->setEncoding($charSet);
    }

    /**
     * Returns the doubleQuote option
     *
     * @return bool
     */
    public function getDoubleQuote(): bool
    {
        return $this->_doubleQuote;
    }

    /**
     * Sets the doubleQuote option
     *
     * @param bool $doubleQuote
     * @return HtmlEntities
     */
    public function setDoubleQuote(bool $doubleQuote): static
    {
        $this->_doubleQuote = $doubleQuote;
        return $this;
    }

    /**
     * Returns the string $value, converting characters to their corresponding HTML entity
     * equivalents where they exist
     *
     * @param string $value
     * @return string
     * @throws BaseException
     */
    public function filter(string $value): string
    {
        $filtered = htmlentities($value, $this->getQuoteStyle(), $this->getEncoding(), $this->getDoubleQuote());
        if (strlen($value) && !strlen($filtered)) {
            // @codeCoverageIgnoreStart
            if (!function_exists('iconv')) {
                throw new BaseException('Encoding mismatch has resulted in htmlentities errors');
            }
            $enc = $this->getEncoding();
            $value = iconv('', $enc . '//IGNORE', $value);
            $filtered = htmlentities($value, $this->getQuoteStyle(), $enc, $this->getDoubleQuote());
            if (!strlen($filtered)) {
                throw new BaseException('Encoding mismatch has resulted in htmlentities errors');
            }
        }
        // @codeCoverageIgnoreEnd
        return $filtered;
    }
}
