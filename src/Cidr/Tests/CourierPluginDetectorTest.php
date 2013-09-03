<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Tests;

use Bond\Di\DiTestCase;
use Cidr\CourierPluginMetadata;
use Symfony\Component\DependencyInjection\Reference;
use Cidr\CourierPluginDetector;
use Bond\Di\Inject;
use Cidr\Milk;

/**
 * @resource Cidr\Tests\CourierPluginDetectorTest
 * @service test
 */
class CourierPluginDetectorTest extends DiTestCase
{  

    public $courierPluginDetector;

    public function __invoke ($configurator, $container)
    {
        $configurator->add (
            "courierPlugin", 
            CourierPluginMetadata::class,
            [new Inject(), new Inject(), new Inject()]
        );
        
        $configurator->addFactory(
            "courierPluginFactory",
            "courierPlugin"
        );

        $configurator->add (
            "detector",
            CourierPluginDetector::class,
            [ 
                "/home/captain/Cidr/src/Cidr/Courier",
                "Cidr/Courier",
                "Validation.yml",
                "Configuration.php",
                new Reference("courierPluginFactory")
            ]
        );
        
        $configurator->add (
            "test",
            CourierPluginDetectorTest::class
        )->setProperty (
            "courierPluginDetector",
            new Reference("detector")
        );

    }

    public function testCourierPluginDetectorIsOfCorrectType()
    {
        $this->assertInstanceOf (
            CourierPluginDetector::class,
            $this->courierPluginDetector
        );
    }


}