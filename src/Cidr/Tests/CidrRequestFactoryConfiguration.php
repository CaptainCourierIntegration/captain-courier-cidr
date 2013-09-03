<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 02/09/2013
 * Time: 11:38
 */

namespace Cidr\Tests;


use Bond\Di\Configurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CidrRequestFactoryConfiguration
 * @package Cidr\Tests
 * @depends Cidr\StandaloneConfiguration
 */
class CidrRequestFactoryConfiguration
{
    public function __invoke(Configurator $configurator, ContainerBuilder $container)
    {
        $configurator->add(
            "cidrRequestFactory",
            CidrRequestFactory::class,
            [
                new Reference("consignmentFactory"),
                new Reference("courierCredentialsManager")
            ]
        );
    }
} 