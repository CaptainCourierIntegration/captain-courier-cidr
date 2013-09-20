<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce;

use Cidr\Courier\ParcelForce\Api\ShipService;
use Symfony\Component\DependencyInjection\Reference;

use Cidr\Tag;

class Configuration
{

    public function __invoke($configurator, $container)
    {
        $configurator->add(
            "parcelForceShipService",
            ShipService::class,
            [],
            "prototype",
            true
        );

        $configurator->add(
            "parcelForceCreateShipment",
            CreateShipment::class,
            [
                new Reference("parcelForceShipServiceFactory"),
                "ParcelForce"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add(
            "parcelForceGetTracking",
            GetTracking::class,
            [
                "ParcelForce"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add(
            "parcelForceGetQuote",
            GetQuote::class,
            [
                "ParcelForce"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add(
            "parcelForcePrintLabel",
            PrintLabel::class,
            [
                new Reference("parcelForceShipServiceFactory"),
                "ParcelForce"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

    }

}