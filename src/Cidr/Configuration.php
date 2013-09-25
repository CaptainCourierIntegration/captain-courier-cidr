<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Curl\Curl;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Cidr\Di\ValidatorFactory;
use Bond\Di\Inject;
use Symfony\Component\Yaml\Parser;

class Configuration
{ use Milk;

    private $courierPlugins;

    public function __invoke($configurator, $container)
    {
        $configurator->add("stdClass", "stdClass");
        //loads libs
        $this->loadCurl($configurator, $container);
        $this->loadCourierPlugins($configurator, $container);
        $this->loadCidrValidator($configurator, $container);
        $this->loadCidrCapability($configurator, $container);
        $this->loadCourierCredentialsManager($configurator, $container);

    }

    private function loadCurl ($configurator, $container) {
        $configurator->add("curl", Curl::class, [], "prototype", true);
    }

    private function loadCidrCapability($configurator, $container)
    {
        $configurator->add(
            "cidrCapability",
            CidrCapability::class,
            [ new Inject(), new Reference("cidrValidator") ]
        );

        $configurator->addFactory(
            "cidrCapabilityFactory",
            "cidrCapability"
        );
    }

    private function loadCidrValidator($configurator, $container)
    {
        $courierValidators = [];
        foreach ($this->courierPlugins as $plugin) {
            $courierValidators[$plugin->getCourierName()] =
                $plugin->getValidationFiles();
        }
        $container->setParameter("courierValidators", $courierValidators);

        $configurator->add(
            "cidrValidator",
            CidrValidator::class,
            [
                "%courierValidators%",
                new Reference("cidrValidationViolationFactory"),
                new Reference("validatorFactory")
            ]
        )->addMethodCall("init", []);

        $configurator->add(
            "validatorFactory",
            ValidatorFactory::class
        );

        $configurator->add(
            "cidrValidationViolation",
            CidrValidationViolation::class,
            [new Inject(), new Inject(), new Inject()]
        );
        $configurator->addFactory(
            "cidrValidationViolationFactory",
            "cidrValidationViolation"
        );
    }

    private function loadCourierPlugins($configurator, $container)
    {
        foreach ($this->courierPlugins as $plugin) {
            foreach ($plugin->getConfigurationResources() as $resource => $type) {
                $configurator->load($resource);
            }
        }
    }

    private function loadCourierCredentialsManager($configurator, ContainerBuilder $container)
    {
        $container->setParameter(
            "credentialsFile",
            __DIR__ . "/../../res/credentials.yml"
        );

        $configurator->add(
            "yamlCourierCredentialsManager",
            YamlCourierCredentialsManager::class,
            [
                "%credentialsFile%",
                new Reference("yamlParser")
            ]
        );

        $container->setAlias(
            "courierCredentialsManager",
            "yamlCourierCredentialsManager"
        );

        $configurator->add(
            "yamlParser",
            Parser::class
        );
    }

}