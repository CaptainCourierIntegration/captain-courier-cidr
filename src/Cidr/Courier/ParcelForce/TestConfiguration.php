<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce;

use Cidr\Courier\ParcelForce\Tests\MockedShipService;
use Symfony\Component\DependencyInjection\Reference;

use Cidr\Tag;

class TestConfiguration
{

    public function __invoke($configurator, $container)
    {

        $configurator->add(
            "parcelForceMockedShipService",
            MockedShipService::class,
            [
                "MK0730971",
                _DIR__ . "/Tests/pdfData.pdf"
            ],
            "prototype",
            true
        );

        $configurator->add(
            "parcelForceTestCreateShipment",
            CreateShipment::class,
            [
                new Reference("parcelForceMockedShipServiceFactory"),
                "ParcelForce"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add(
            "parcelForceTestGetTracking",
            GetTracking::class,
            [
                "ParcelForce"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add(
            "parcelForceTestPrintLabel",
            PrintLabel::class,
            [
                new Reference("parcelForceMockedShipServiceFactory"),
                "ParcelForce"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

    }

}