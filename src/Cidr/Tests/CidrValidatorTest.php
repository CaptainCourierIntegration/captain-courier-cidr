<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Tests;

use Cidr\CidrFactory;
use Cidr\Exception\InvalidArgumentException;
use Cidr\Model\Task;
use Cidr\Validator;
use Symfony\Component\DependencyInjection\Reference;
use Bond\Di\Configurator;
use Cidr\CidrValidator;
use Bond\Di\DiTestCase;

/**
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Tests\Provider\ProviderConfiguration
 * @service stdClass
 */
class CidrValidatorTest extends DiTestCase
{
    public function provideConsignment()
    {
        $container = $this->setup();

        $args = [];

        $cidrValidator = $container->get("cidrValidator");
        $consignmentProvider = $container->get("realWorldConsignmentProvider");

        foreach ($consignmentProvider->getData() as $consignment) {
            $args[] = [$cidrValidator, $consignment];
        }

        return $args;
    }

    /** @dataProvider provideConsignment */
    public function testCidrValidatorIsAnInstanceOfCidrValidator($cidrValidator, $consignment)
    {
        $this->assertInstanceOf(
            CidrValidator::class,
            $cidrValidator
        );
    }

    /** @dataProvider provideConsignment */
    public function testParcelForceIsARecognisedCourier($cidrValidator, $consignment)
    {
        $result = $cidrValidator->validate (
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $consignment->getCollectionAddress()
        );
    }

    /** @dataProvider provideConsignment */
    public function testValationAgainstNotExistentCourierThrowsException($cidrValidator, $consignment)
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $cidrValidator->validate(
            "KingKong",
            Task::CREATE_CONSIGNMENT,
            $consignment->getCollectionAddress()
        );
    }

    /** @dataProvider provideConsignment */
    public function testValidationOfNullObjectThrowsException($cidrValidator, $consignment)
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            null
        );
    }

    /** @dataProvider provideConsignment */
    public function testValidationOfConsignmentDoesNotThrowException($cidrValidator, $consignment)
    {
        $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $consignment
        );
    }

    /** @dataProvider provideConsignment */
    public function testParcelForceValidationHasNoViolationsForAValidAddress($cidrValidator, $consignment)
    {
        $collectionAddress = $consignment->getCollectionAddress();
        $violations = $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $collectionAddress
        );

        $this->assertEquals(0, count($violations));
    }

    /** @dataProvider provideConsignment */
    public function testValidationFailsOnParcelForceServiceCodeBeingSetToNull($cidrValidator, $consignment)
    {
        $consignment->setServiceCode(null);

        $violations = $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $consignment
        );

        $this->assertEquals(1, count($violations));
        $this->assertEquals("serviceCode", $violations[0]->getPath());
    }

}