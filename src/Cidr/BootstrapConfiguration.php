<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Cidr;

use Cidr\CourierPluginMetadata;

use Bond\Di\Inject;
use Symfony\Component\DependencyInjection\Reference;

class BootstrapConfiguration
{

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
            "courierPluginDetector",
            CourierPluginDetector::class,
            [
                // todo make this path relative
                __DIR__ . "/Courier",
                "Cidr\\Courier",
                "Validation.yml",
                "Configuration.php",
                new Reference("courierPluginFactory")
            ]
        );        
    }

}