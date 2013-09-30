<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Tests;
use Bond\Di\DiTestCase;
use Cidr\Courier\ParcelForce\CreateShipment;
use Cidr\Model\Task;
use Cidr\Milk;
use Cidr\Tag;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @resource Cidr\StandaloneConfiguration
 * @resource ./../../../Tests/Provider/ProviderConfiguration.yml
 * @resource __CLASS__
 * @service parcelForceCreateShipmentTest
 */
class CreateShipmentTest extends DiTestCase
{
    public $createShipment;

    public function __invoke($configurator, $container)
    {
        $configurator->add(
            "parcelForceMockedShipService",
            MockedShipService::class,
            ["MK0730971"],
            "prototype",
            true
        );

        $configurator->add(
            "parcelForceCreateShipmentMocked",
            CreateShipment::class,
            [
                new Reference("parcelForceMockedShipServiceFactory"),
                "ParcelForceTest",
                new Reference("shipmentIdGenerator")
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add (
            "parcelForceCreateShipmentTest",
            CreateShipmentTest::class
        )->setProperty ("createShipment", new Reference ("parcelForceCreateShipmentMocked"));
    }

    public function testGetTaskReturnsCreateShipment()
    {
        $this->assertEquals (
            Task::CREATE_CONSIGNMENT,
            $this->createShipment->getTask()
        );
    }

    public function testGetCourierReturnsParcelForce()
    {
        $this->assertEquals (
            "ParcelForceTest",
            $this->createShipment->getCourier()
        );
    }

}