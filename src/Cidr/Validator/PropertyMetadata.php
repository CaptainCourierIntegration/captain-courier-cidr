<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
