<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Cidr\Exception\CourierNotFoundException;
use Cidr\Exception\IllegalStateException;
use Cidr\Exception\InvalidFileFormatException;

/**
 * manages courier credentials loaded from yaml.
 * will only read from the yaml file, never writes.
 * Class CourierCredentialsManager
 * @package Cidr
 */
class YamlCourierCredentialsManager implements CourierCredentialsManagerInterface
{ use Milk;

    private $credentialsFile;
    private $yamlParser;

    private $credentials;
    private $init = false;

    protected static $propertiesNotManagedByMilk = ["credentials", "init"];

    /** @inheritDoc */
    public function init(array $requiredCouriers = [])
    {
        assert(!$this->init);
        assert(null !== $this->credentialsFile);
        assert(file_exists($this->credentialsFile));
        assert(null !== $this->yamlParser);

        $credentials = $this->yamlParser->parse(
            file_get_contents($this->credentialsFile)
        );

        // removed detected couriers from the required list
        // checks that the credentials have a flat structure, no nested substructures
        foreach ($credentials as $courier => $courierCredentials) {
            $requiredCouriers = array_values(array_diff($requiredCouriers, [$courier]));
            foreach ($courierCredentials as $key => $value) {
                if (is_array($value) or is_object($value)) {
                    throw new InvalidFileFormatException(
                        $this->credentialsFile,
                        "courier $courier cannot have nested structure for key '$key'"
                    );
                }
            }
        }

        // checks all required couriers have been satisfied
        if (count($requiredCouriers) > 0 ) {
            throw new CourierNotFoundException(
                $requiredCouriers[0],
                "loading in courier credentials"
            );
        }

        $this->credentials = $credentials;
        $this->init = true;
        return $this;
    }

    /** @inheritDoc */
    public function getCouriers()
    {
        if (!$this->init) {
            throw new IllegalStateException("init not called");
        }

        assert(null != $this->credentials);

        return array_keys($this->credentials);
    }

    /** @inheritDoc */
    public function getCredentials($courier)
    {
        if (!$this->init) {
           throw new IllegalStateException("init not called");
        }
        assert(null !== $this->credentials);
        if (!in_array($courier, $this->getCouriers())) {
            throw new CourierNotFoundException($courier, "getting courier credentials");
        }
        return $this->credentials[$courier];
    }

}