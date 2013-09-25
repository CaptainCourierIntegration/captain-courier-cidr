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

class StandaloneConfiguration
{

    public function __invoke(Configurator $configurator, $container)
    {
        $bootstrapContainer = $this->buildBootstrapContainer();
        $bootstrapContainer->compile();
        $pluginDetector = $bootstrapContainer->get ("courierPluginDetector");
        $productionContainer = $this->buildContainer(
            $pluginDetector->detectCouriers()
        );
        $container->merge($productionContainer);
    }

    private function buildBootstrapContainer()
    {
        $container = new ContainerBuilder();
        $configurator = new Configurator($container);
        $configurator->load(BootstrapConfiguration::class);
        return $container;
    }

    private function buildContainer(array $courierPlugins)
    {
        $container = new ContainerBuilder();
        $configurator = new Configurator($container);
        $configurator->load(new Configuration($courierPlugins));
        foreach($courierPlugins as $plugin) {
            foreach ($plugin->getConfigurationResources() as $resource => $type) {
                $configurator->load($resource);
            }
        }
        return $container;
    }

}