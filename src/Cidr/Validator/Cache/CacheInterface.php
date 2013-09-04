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

/**
 * Persists ClassMetadata instances in a cache
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface CacheInterface
{
    /**
     * Returns whether metadata for the given class exists in the cache
     *
     * @param string $class
     */
    public function has($class);

    /**
     * Returns the metadata for the given class from the cache
     *
     * @param string $class Class Name
     *
     * @return ClassMetadata|false A ClassMetadata instance or false on miss
     */
    public function read($class);

    /**
     * Stores a class metadata in the cache
     *
     * @param ClassMetadata $metadata A Class Metadata
     */
    public function write(ClassMetadata $metadata);
}
