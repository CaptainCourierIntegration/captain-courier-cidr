<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 *
 *
 * Copyright (c) 2004-2013 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Cidr\Validator\Cache;

use Cidr\Validator\ClassMetadata;

class ApcCache implements CacheInterface
{
    private $prefix;

    public function __construct($prefix)
    {
        if (!extension_loaded('apc')) {
            throw new \RuntimeException('Unable to use ApcCache to cache validator mappings as APC is not enabled.');
        }

        $this->prefix = $prefix;
    }

    public function has($class)
    {
        if (!function_exists('apc_exists')) {
            $exists = false;

            apc_fetch($this->prefix.$class, $exists);

            return $exists;
        }

        return apc_exists($this->prefix.$class);
    }

    public function read($class)
    {
        return apc_fetch($this->prefix.$class);
    }

    public function write(ClassMetadata $metadata)
    {
        apc_store($this->prefix.$metadata->getClassName(), $metadata);
    }
}
