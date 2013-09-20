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
use Cidr\CidrRequestContextCreateShipment;
use Cidr\CidrRequestContextPrintLabel;
use Cidr\CidrResponseContextCreateShipment;
use Cidr\CidrResponseContextValidationFailed;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Bond\Di\Configurator;
use Cidr\Model\Task;

use Cidr\CidrFactory;
use Cidr\CidrRequest;
use Cidr\CidrResponse;

use Cidr\Model\Address;
use Cidr\Model\Contact;
use Cidr\Model\Shipment;
use Cidr\Model\Parcel;

use Bond\Di\DiTestCase;

/**
 * @service stdClass
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Tests\CidrRequestFactoryConfiguration
 * @resource Cidr\Tests\Provider\ProviderConfiguration
 * @resource __CLASS__
 */
class CidrIntegrationTest extends DiTestCase
{

    public function __invoke($configurator, $container)
    {
        $configurator->add("cidr", Cidr::class);
    }

    public function createShipmentCapabilityProvider()
    {
        $container = $this->setup();
        $cidr = $container->get("cidr");

        foreach ($cidr->getSupportedCouriers() as $courier) {
            $cap = $cidr->getCapability(
                $courier,
                Task::CREATE_CONSIGNMENT
            );

            $cidrRequestFactory = $container->get("cidrRequestFactory");

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
            if ($courier === "ParcelForce") {

                $credentialsManager = $container->get("courierCredentialsManager");
                $credentialsManager->init();

                $testCases[] = [
                    $cidr->getCapability($courier, TASK::PRINT_LABEL),
                    $cidr->getCapability($courier, TASK::CREATE_CONSIGNMENT),
                    $container->get("cidrRequestFactory"),
                    $credentialsManager->getCredentials($courier)
                ];
            }
        }

        return $testCases;
    }

    /**
     * @dataProvider createShipmentCapabilityProvider
     */
    public function testCreateShipmentRequestOnApiHasSucceeded(
        CidrCapability $cidrCapability,
        CidrRequestFactory $cidrRequestFactory
    )
    {
        $request = $cidrRequestFactory->create($cidrCapability->getCourier());

        $cidrResponse = $cidrCapability->submitCidrRequest($request);

        $this->assertInstanceOf(CidrResponse::class, $cidrResponse);

        $this->assertInstanceOf(
            CidrResponseContextCreateShipment::class,
            $cidrResponse->getResponseContext()
        );

        return $cidrResponse->getResponseContext()->getShipmentNumber();
    }

    /**
     * @dataProvider printLabelCapabilityProvider
     */
    public function testPrintLabelRequestOnApiHasSucceeded(
        CidrCapability $printLabelCapability,
        CidrCapability $createShipmentCapability,
        CidrRequestFactory $cidrRequestFactory,
        $credentials
    )
    {
            $consignmentNumber = $this->testCreateShipmentRequestOnApiHasSucceeded($createShipmentCapability, $cidrRequestFactory);

            $context = new CidrRequestContextPrintLabel($consignmentNumber);
            $request = new CidrRequest($context, Task::PRINT_LABEL, $credentials, []);

            $response = $printLabelCapability->submitCidrRequest($request);

            $pdf= $response->getResponseContext()->getPdf();
            $this->assertNotNull($pdf);

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->assertEquals(
                'application/pdf',
                finfo_buffer($finfo, $pdf)
            );
            finfo_close($finfo);

    }

    /**
     * @dataProvider printLabelCapabilityProvider
     */
    public function testPrintLabelResponseHasValidationFailedContextForInvalidRequest(
        CidrCapability $printLabelCapability,
        CidrCapability $createShipmentCapability,
        CidrRequestFactory $cidrRequestFactory,
        $credentials
    )
    {
        $context = new CidrRequestContextPrintLabel("");
        $request = new CidrRequest($context, Task::PRINT_LABEL, $credentials, []);
        $response = $printLabelCapability->submitCidrRequest($request);
        $this->assertEquals(CidrResponse::STATUS_FAILED, $response->getStatus());
        $this->assertInstanceOf(
            CidrResponseContextValidationFailed::class,
            $response->getResponseContext()
        );
    }

}