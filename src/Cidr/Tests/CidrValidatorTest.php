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
 * @resource ./Provider/ProviderConfiguration.yml
 */
class CidrValidatorTest extends DiTestCase
{
    public function provideShipment()
    {
        $container = $this->setup();

        $args = [];

        $cidrValidator = $container->get("cidrValidator");
        $shipmentProvider = $container->get("realWorldShipmentProvider");

        foreach ($shipmentProvider->getData() as $shipment) {
            $args[] = [$cidrValidator, $shipment];
        }

        return $args;
    }

    /** 
     * @dataProvider provideShipment 
     */
    public function testCidrValidatorIsAnInstanceOfCidrValidator($cidrValidator, $shipment)
    {
        $this->assertInstanceOf(
            CidrValidator::class,
            $cidrValidator
        );
    }

    /** @dataProvider provideShipment */
    public function testParcelForceIsARecognisedCourier($cidrValidator, $shipment)
    {
        $result = $cidrValidator->validate (
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $shipment->getCollectionAddress()
        );
    }

    /** @dataProvider provideShipment */
    public function testValationAgainstNotExistentCourierThrowsException($cidrValidator, $shipment)
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $cidrValidator->validate(
            "KingKong",
            Task::CREATE_CONSIGNMENT,
            $shipment->getCollectionAddress()
        );
    }

    /** @dataProvider provideShipment */
    public function testValidationOfNullObjectThrowsException($cidrValidator, $shipment)
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            null
        );
    }

    /** @dataProvider provideShipment */
    public function testValidationOfShipmentDoesNotThrowException($cidrValidator, $shipment)
    {
        $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $shipment
        );
    }

    /** @dataProvider provideShipment */
    public function testParcelForceValidationHasNoViolationsForAValidAddress($cidrValidator, $shipment)
    {
        $collectionAddress = $shipment->getCollectionAddress();
        $violations = $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $collectionAddress
        );

        $this->assertEquals(0, count($violations));
    }

    /** @dataProvider provideShipment */
    public function testValidationFailsOnParcelForceServiceCodeBeingSetToNull($cidrValidator, $shipment)
    {
        $shipment->setServiceCode(null);

        $violations = $cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $shipment
        );

        $this->assertEquals(1, count($violations));
        $this->assertEquals("serviceCode", $violations[0]->getPath());
    }

}