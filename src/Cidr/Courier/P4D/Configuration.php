<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\P4D;

use Symfony\Component\DependencyInjection\Reference;

use Cidr\Tag;

/**
 * Class Configuration
 * @package Cidr\Courier\P4D
 * @depends Cidr\Configuration
 */
class Configuration
{

    public function __invoke ($configurator, $container)
    {
        $container->setParameter (
            "p4dApiUrl",
            "https://www.p4d.co.uk/p4d/api/v2"
        );

        $container->setParameter (
            "p4dName",
            "P4D"
        );

        $configurator->add (
            "p4dCreateShipment",
            CreateShipment::class,
            [
                "%p4dApiUrl%",
                "%p4dName%",
                new Reference("curl")
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add(
            "p4dGetQuote",
            GetQuote::class,
            [
                "%p4dName%",
                "%p4dApiUrl%",
                new Reference("curl"),
                new Reference("cidrResponseFactory"),
                new Reference("cidrResponseContextGetQuoteFactory")
            ]
        )->addTag(Tag::CIDR_CAPABILITY);

        $configurator->add (
            "p4dPrintLabel",
            PrintLabel::class,
            [
                "%p4dApiUrl%",
                "%p4dName%"
            ]
        );//->addTag(Tag::CIDR_CAPABILITY);
    }

}