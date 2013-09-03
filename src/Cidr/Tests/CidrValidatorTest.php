<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Tests;

use Cidr\CidrFactory;
use Cidr\Model\Task;
use Cidr\Validator;
use Symfony\Component\DependencyInjection\Reference;
use Bond\Di\Configurator;
use Cidr\CidrValidator;
use Bond\Di\DiTestCase;

/**
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Tests\ConsignmentGeneratorConfiguration
 * @resource __CLASS__
 * @service cidrValidatorTest
 */
class CidrValidatorTest extends DiTestCase
{
    public $cidrValidator;
    public $consignment;

    public function __invoke($configurator, $container)
    {
        $configurator->add(
            "cidrValidatorTest",
            self::class
        )
            ->setProperty("cidrValidator", new Reference("cidrValidator"))
            ->setProperty("consignment", new Reference("consignment"))
            ->setProperty("container", new Reference("service_container"));
    }

    public function testCidrValidatorIsAnInstanceOfCidrValidator()
    {
        $this->assertInstanceOf(
            CidrValidator::class,
            $this->cidrValidator
        );
    }

    public function testParcelForceIsARecognisedCourier()
    {
        $result = $this->cidrValidator->validate (
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $this->consignment->getCollectionAddress()
        );
    }

    /** @expectedException Exception */
    public function testValationAgainstNotExistentCourierThrowsException()
    {
        $this->cidrValidator->validate(
            "KingKong",
            Task::CREATE_CONSIGNMENT,
            $this->consignment->getCollectionAddress()
        );
    }

    /** @expectedException Exception */
    public function testValidationOfNullObjectThrowsException()
    {
        $this->cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            null
        );
    }

    public function testValidationOfConsignmentDoesNotThrowException()
    {
        $this->cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $this->consignment
        );
    }

    public function testParcelForceValidationHasNoViolationsForAValidAddress()
    {
        $collectionAddress = $this->consignment->getCollectionAddress();
        $violations = $this->cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $collectionAddress
        );

        $this->assertEquals(0, count($violations));
    }

    public function testValidationFailsOnParcelForceServiceCodeBeingSetToNull()
    {
        $this->consignment->setServiceCode(null);

        $violations = $this->cidrValidator->validate(
            "ParcelForce",
            Task::CREATE_CONSIGNMENT,
            $this->consignment
        );

        $this->assertEquals(1, count($violations));
        $this->assertEquals("serviceCode", $violations[0]->getPath());
    }

}