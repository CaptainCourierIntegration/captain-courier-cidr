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

namespace Cidr\Validator;

use Symfony\Component\Validator\Exception\ValidatorException;

class GetterMetadata extends MemberMetadata
{
    /**
     * Constructor.
     *
     * @param string $class    The class the getter is defined on
     * @param string $property The property which the getter returns
     *
     * @throws ValidatorException
     */
    public function __construct($class, $property)
    {
        $getMethod = 'get'.ucfirst($property);
        $isMethod = 'is'.ucfirst($property);

        if (method_exists($class, $getMethod)) {
            $method = $getMethod;
        } elseif (method_exists($class, $isMethod)) {
            $method = $isMethod;
        } else {
            $method = $getMethod;
            //throw new ValidatorException(sprintf('Neither method %s nor %s exists in class %s', $getMethod, $isMethod, $class));
        }

        parent::__construct($class, $method, $property);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue($object)
    {
        $method = $this->name;
        return $object->$method();
        //return $this->newReflectionMember($object)->invoke($object);
    }

    /**
     * {@inheritDoc}
     */
    protected function newReflectionMember($objectOrClassName)
    {
        return new \ReflectionMethod($objectOrClassName, $this->getName());
    }
}
