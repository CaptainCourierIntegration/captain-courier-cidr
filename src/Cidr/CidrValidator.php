<?php 

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr;

use Bond\Di\Factory;
use Cidr\Di\ValidatorFactory;
use Cidr\Exception\IllegalStateException;
use Cidr\Model\Task;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader;

use Cidr\Milk;

class CidrValidator
{  use Milk;

    private $validationFiles;
    private $cidrValidationViolationFactory;
    private $validatorFactory;

    private $validators = [];
    
    protected static $propertiesNotManagedByMilk = ["validators"];

    public function init()
    {
        assert(is_array($this->validationFiles));
        assert(0 < count($this->validationFiles));
        assert( $this->cidrValidationViolationFactory instanceof Factory);
        assert($this->validatorFactory instanceof ValidatorFactory);

        foreach ($this->validationFiles as $courier => $taskFiles) {
            $this->validators[$courier] = [];
            foreach ($taskFiles as $task => $fileName ) {
                $this->validators[$courier][$task] = is_null($fileName) ? null : $this->validatorFactory->create($fileName);
            }
        }
    }

    /**
     * validates the cidr request for that capability
     * @param string $courier name of courier that is servicing the cidrRequest
     * @param CidrRequest $cidrRequest
     * @throws \Exception when no validation is found for the courier requested
     * @throws \InvalidArgumentException when validation constraints are found
     * for courier but not for a particular class.
     * @return array of CidrValidationViolation*
     */
    public function validate($courier, $task, $cidrRequest) {
        assert(3 === count(func_get_args()));

        assert(!is_null($courier));
        assert(is_string($courier));
        assert(in_array($courier, array_keys($this->validators)));

        assert(!is_null($task));
        assert(is_string($task));
        assert(in_array($task, Task::$Tasks));
        assert(in_array($task, array_keys($this->validators[$courier])));

        assert(!is_null($cidrRequest));

        // checks a validator exists for courier
        if (false === isset($this->validators[$courier])) {
            throw new \Exception(
                "no validator for courier $courier." 
                . " available couriers: "
                . print_r(array_keys($this->validators), true)
            );
        }

        $validator = $this->validators[$courier][$task];

        if (null === $validator) {
            throw new IllegalStateException("courier '$courier' doesn't support capability '$task'");
        }

        // checks that the the constraints have been found for cidrRequest
        $metadata = $validator
            ->getMetadataFactory()
            ->getMetadataFor($cidrRequest);

        if (0 ===
           count($metadata->members) 
           + count($metadata->properties) 
           + count($metadata->getters)
        ) {
            throw new \InvalidArgumentException(
                "can't find any metadata/constraints for"
                . get_class($cidrRequest)
            );
        } 
        
        // validates this object
        $result = $validator->validate($cidrRequest);


        return array_map(
            function ($violation) {
                return $this->cidrValidationViolationFactory->create(
                    $violation->getPropertyPath(),
                    $violation->getMessage(),
                    $violation->getInvalidValue()
                );
            },
            iterator_to_array($result)
        );
    }
}