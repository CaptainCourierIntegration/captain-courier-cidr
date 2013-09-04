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

use Symfony\Component\Validator\Constraint;

abstract class ElementMetadata
{
    /**
     * @var Constraint[]
     */
    public $constraints = array();

    /**
     * @var array
     */
    public $constraintsByGroup = array();

    /**
     * Returns the names of the properties that should be serialized.
     *
     * @return array
     */
    public function __sleep()
    {
        return array(
            'constraints',
            'constraintsByGroup',
        );
    }

    /**
     * Clones this object.
     */
    public function __clone()
    {
        $constraints = $this->constraints;

        $this->constraints = array();
        $this->constraintsByGroup = array();

        foreach ($constraints as $constraint) {
            $this->addConstraint(clone $constraint);
        }
    }

    /**
     * Adds a constraint to this element.
     *
     * @param Constraint $constraint
     *
     * @return ElementMetadata
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->constraints[] = $constraint;

        foreach ($constraint->groups as $group) {
            $this->constraintsByGroup[$group][] = $constraint;
        }

        return $this;
    }

    /**
     * Returns all constraints of this element.
     *
     * @return Constraint[] An array of Constraint instances
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Returns whether this element has any constraints.
     *
     * @return Boolean
     */
    public function hasConstraints()
    {
        return count($this->constraints) > 0;
    }

    /**
     * Returns the constraints of the given group and global ones (* group).
     *
     * @param string $group The group name
     *
     * @return array An array with all Constraint instances belonging to the group
     */
    public function findConstraints($group)
    {
        return isset($this->constraintsByGroup[$group])
                ? $this->constraintsByGroup[$group]
                : array();
    }
}
