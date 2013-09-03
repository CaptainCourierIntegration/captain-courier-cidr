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

class Configuration
{

    public function __invoke ($configurator, $container) 
    {
        $container->setParameter (
            "p4dApiUrl",
            "https://www.p4d.co.uk/p4d/api/phpsystem/v2"
        );

        $container->setParameter (
            "p4dName",
            "P4D"
        );

        $configurator->add (
            "p4dCreateConsignment",
            "Cidr\\Courier\\P4D\\CreateConsignment",
            [
                "%p4dApiUrl%",
                "%p4dName%"
            ]
        )->addTag(Tag::CIDR_CAPABILITY);
    }

}