<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider;


use Symfony\Component\DependencyInjection\Reference;

class ProviderConfiguration
{

    public function __invoke($configurator, $container)
    {
        $configurator->add("addressProvider", AddressProvider::class);
        $configurator->add("contactProvider", ContactProvider::class);
        $configurator->add("parcelProvider", ParcelProvider::class);
        $configurator->add(
            "consignmentProvider",
            ConsignmentProvider::class,
            [
                new Reference("addressProvider"),
                new Reference("contactProvider"),
                new Reference("parcelProvider")
            ]
        );
        $configurator->add("realWorldConsignmentProvider", RealWorldConsignmentProvider::class);
    }

} 