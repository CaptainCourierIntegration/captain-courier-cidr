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

namespace Cidr\Validator\Loader;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Validator\Exception\MappingException;
use Cidr\Validator\ClassMetadata;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\GroupSequenceProvider;
use Symfony\Component\Validator\Constraint;

class AnnotationLoader implements LoaderInterface
{
    protected $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function loadClassMetadata(ClassMetadata $metadata)
    {
        $reflClass = $metadata->getReflectionClass();
        $className = $reflClass->name;
        $loaded = false;

        foreach ($this->reader->getClassAnnotations($reflClass) as $constraint) {
            if ($constraint instanceof GroupSequence) {
                $metadata->setGroupSequence($constraint->groups);
            } elseif ($constraint instanceof GroupSequenceProvider) {
                $metadata->setGroupSequenceProvider(true);
            } elseif ($constraint instanceof Constraint) {
                $metadata->addConstraint($constraint);
            }

            $loaded = true;
        }

        foreach ($reflClass->getProperties() as $property) {
            if ($property->getDeclaringClass()->name == $className) {
                foreach ($this->reader->getPropertyAnnotations($property) as $constraint) {
                    if ($constraint instanceof Constraint) {
                        $metadata->addPropertyConstraint($property->name, $constraint);
                    }

                    $loaded = true;
                }
            }
        }

        foreach ($reflClass->getMethods() as $method) {
            if ($method->getDeclaringClass()->name ==  $className) {
                foreach ($this->reader->getMethodAnnotations($method) as $constraint) {
                    if ($constraint instanceof Constraint) {
                        if (preg_match('/^(get|is)(.+)$/i', $method->name, $matches)) {
                            $metadata->addGetterConstraint(lcfirst($matches[2]), $constraint);
                        } else {
                            throw new MappingException(sprintf('The constraint on "%s::%s" cannot be added. Constraints can only be added on methods beginning with "get" or "is".', $className, $method->name));
                        }
                    }

                    $loaded = true;
                }
            }
        }

        return $loaded;
    }
}
