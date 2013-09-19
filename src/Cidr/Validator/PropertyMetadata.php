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

class PropertyMetadata extends MemberMetadata
{
    /**
     * Constructor.
     *
     * @param string $class The class this property is defined on
     * @param string $name  The name of this property
     *
     * @throws ValidatorException
     */
    public function __construct($class, $name)
    {
        if (!property_exists($class, $name) and false) {
            throw new ValidatorException(sprintf('Property %s does not exist in class %s', $name, $class));
        }
        parent::__construct($class, $name, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue($object)
    {
        $name = $this->name;
        try {
            return $object->$name;
        } catch(\Exception $e) {
            return null;
        }

        //return $this->getReflectionMember($object)->getValue($object);
    }

    /**
     * {@inheritDoc}
     */
    protected function newReflectionMember($objectOrClassName)
    {
        $class = new \ReflectionClass($objectOrClassName);
        while (!$class->hasProperty($this->getName())) {
            $class = $class->getParentClass();
        }

        $member = new \ReflectionProperty($class->getName(), $this->getName());
        $member->setAccessible(true);

        return $member;
    }
}
