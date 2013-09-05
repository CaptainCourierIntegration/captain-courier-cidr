<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\ParcelForce\Tests;
use Bond\Di\DiTestCase;
use Cidr\Courier\ParcelForce\CreateConsignment;
use Cidr\Model\Task;
use Cidr\Milk;
use Cidr\Tag;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Tests\Provider\ProviderConfiguration
 * @resource __CLASS__
 * @service parcelForceCreateConsignmentTest
 */
class CreateConsignmentTest extends DiTestCase
{ 
    public $createConsignment;

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
            "parcelForceCreateConsignmentMocked",
            CreateConsignment::class,
            [
                new Reference("parcelForceMockedShipServiceFactory"),
                "ParcelForceTest"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add (
            "parcelForceCreateConsignmentTest",
            CreateConsignmentTest::class
        )->setProperty ("createConsignment", new Reference ("parcelForceCreateConsignmentMocked"));
    }
    
    public function testGetTaskReturnsCreateConsignment()
    {
        $this->assertEquals ( 
            Task::CREATE_CONSIGNMENT, 
            $this->createConsignment->getTask()
        );
    }

    public function testGetCourierReturnsParcelForce()
    {
        $this->assertEquals (
            "ParcelForceTest",
            $this->createConsignment->getCourier()
        );
    }








    
}