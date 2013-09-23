<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Bond\Di\Configurator;
use Bond\Di\Factory;

class Cidr
{

    private $courierCapabilities;
    private $cidrCapabilityFactory;

    public function __construct()
    {
        $args = func_get_args();
        if (0 === count($args)) {
            $this->initWithoutDi();
        } elseif (2 === count($args)) {
            $this->initWithDi($args[0], $args[1]);
        } else {
            throw new \InvalidArgumentException("constructor either takes no arguments or takes arguments: array courierCapabilitities, Factory cidrCapabilityFactory");
        }

    }

    private function initWithoutDi()
    {
        $container = new ContainerBuilder();
        $configurator = new Configurator($container);
        $configurator->load(StandaloneConfiguration::class);
        $container->compile();

        $capIds = array_keys($container->findTaggedServiceIds(Tag::CIDR_CAPABILITY));

        $this->initWithDi(
            array_map(function($id)use($container){return $container->get($id);}, $capIds),
            $container->get("cidrCapabilityFactory")
        );
    }

    private function initWithDi(
        array $courierCapabilities,
        Factory $cidrCapabilityFactory
    ) {

        $this->courierCapabilities = $courierCapabilities;
        $this->cidrCapabilityFactory = $cidrCapabilityFactory;
    }

    public function getCapability($courier, $task)
    {
        $candidates = [];
        foreach ($this->courierCapabilities as $cap) {
            if ($cap->getCourier() === $courier and $cap->getTask() === $task) {
                $candidates[] = $cap;
            }
        }

        if (!$candidates) {
            throw new \InvalidArgumentException (
                "no capability for courier $courier and task $task"
            );
        }
        if (count($candidates) > 1) {
            throw new \LogicException (
                "multiple capabilitites for courier $courier and task $task"
            );
        }

        return $this->cidrCapabilityFactory->create($candidates[0]);
    }

    public function getSupportedCouriers()
    {
        return array_unique(array_map(
            function($cap){ return $cap->getCourier(); },
            $this->courierCapabilities
        ));
    }

    public function getSupportedCapabilities($courier)
    {
        return array_map(
            function ($cap) { return $cap->getTask(); },
            array_filter(
                $this->courierCapabilities,
                function ($cap) use($courier) {
                    return $cap->getCourier() === $courier;
                }
            )
        );
    }

}