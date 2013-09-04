<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Tests;

use Bond\Di\Factory;
use Cidr\Cidr;
use Cidr\CidrCapability;
use Cidr\CidrRequestContextCreateConsignment;
use Cidr\CidrRequestContextPrintLabel;
use Cidr\CidrResponseContextCreateConsignment;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Bond\Di\Configurator;
use Cidr\Model\Task;

use Cidr\CidrFactory;
use Cidr\CidrRequest;
use Cidr\CidrResponse;

use Cidr\Model\Address;
use Cidr\Model\Contact;
use Cidr\Model\Consignment;
use Cidr\Model\Parcel;


use Bond\Di\DiTestCase;

/**
 * @service stdClass
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Tests\ConsignmentGeneratorConfiguration
 * @resource Cidr\Tests\CidrRequestFactoryConfiguration
 * @resource __CLASS__
 */
class CidrIntegrationTest extends DiTestCase
{

    public function __invoke($configurator, $container)
    {
        $configurator->add("cidr", Cidr::class);
    }

    public function createConsignmentCapabilityProvider()
    {
        $container = $this->setup();
        $cidr = $container->get("cidr");

        foreach ($cidr->getSupportedCouriers() as $courier) {
            $cap = $cidr->getCapability(
                $courier,
                Task::CREATE_CONSIGNMENT
            );
            $testCases[] = [
                    $cap,
                    $container->get("cidrRequestFactory")
            ];
        }

        return $testCases;
    }

    public function printLabelCapabilityProvider()
    {
        $container = $this->setup();
        $cidr = $container->get("cidr");

        foreach ($cidr->getSupportedCouriers() as $courier) {
            $cap = $cidr->getCapability(
                $courier,
                Task::PRINT_LABEL
            );
            $credentialsManager = $container->get("courierCredentialsManager");
            $credentialsManager->init();
            $testCases[] = [
                $cap,
                $credentialsManager->getCredentials($cap->getCourier())
            ];
        }

        return $testCases;
    }

    /** @dataProvider createConsignmentCapabilityProvider */
    public function testCreateConsignmentRequestOnApiHasSucceeded(
        CidrCapability $cidrCapability,
        CidrRequestFactory $cidrRequestFactory
    )
    {
        $request = $cidrRequestFactory->create($cidrCapability->getCourier());

        $cidrResponse = $cidrCapability->submitCidrRequest($request);
        $this->assertInstanceOf(CidrResponse::class, $cidrResponse);

        $this->assertInstanceOf(
            CidrResponseContextCreateConsignment::class, 
            $cidrResponse->getResponseContext()
        );
    }

    /** @dataProvider printLabelCapabilityProvider */
    public function testPrintLabelRequestOnApiHasSucceeded(
        CidrCapability $cap,
        $credentials
    )
    {
        if($cap->getCourier() === "ParcelForce") {
            $consignmentNumber = "MK0738330";
            $context = new CidrRequestContextPrintLabel($consignmentNumber);
            $request = new CidrRequest($context, Task::PRINT_LABEL, $credentials);
            $response = $cap->submitCidrRequest($request);
            print_r($response->getResponseContext());
        }

    }

    
    
}