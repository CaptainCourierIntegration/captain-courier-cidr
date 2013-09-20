<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 28/08/2013
 * Time: 15:48
 */

namespace Cidr\Tests;

use Bond\Di\Factory;
use Cidr\CidrRequest;
use Cidr\CidrRequestContextCreateShipment;
use Cidr\CourierCredentialsManagerInterface;
use Cidr\Milk;
use Cidr\Model\Task;
use Cidr\Tests\Provider\DataProvider;

/**
 * @depends Cidr\Tests\ShipmentGeneratorConfiguration
 * Class CidrRequestGeneratorConfiguration
 * @package Cidr\Tests
 */
class CidrRequestFactory
{
    private $shipmentProvider;
    private $courierCredentialsManager;

    private $shipments = [];

    protected  static $propertiesNotManagedByMilk = ["shipments"];

    public function __construct(
        DataProvider $shipmentProvider,
        CourierCredentialsManagerInterface $courierCredentialsManager
    )
    {
        assert(2 === count(func_get_args()));
        assert(null !== $shipmentProvider);
        assert(null != $courierCredentialsManager);

        $this->shipmentProvider = $shipmentProvider;
        $this->courierCredentialsManager = $courierCredentialsManager;
        $courierCredentialsManager->init();
    }

    private function getShipment()
    {
        if (0 === count($this->shipments)) {
            $this->shipments = $this->shipmentProvider->getData();
            shuffle($this->shipments);
        }
        return array_shift($this->shipments);
    }

    public function create($courier)
    {
        $shipment = $this->getShipment();
        $context = new CidrRequestContextCreateShipment(
            $shipment->getCollectionAddress(),
            $shipment->getCollectionContact(),
            $shipment->getCollectionTime(),
            $shipment->getDeliveryAddress(),
            $shipment->getDeliveryContact(),
            $shipment->getDeliveryTime(),
            $shipment->getParcels(),
            $shipment->getContractNumber(),
            $shipment->getServiceCode()
        );
        $request = new CidrRequest(
            $context,
            Task::CREATE_CONSIGNMENT,
            $this->courierCredentialsManager->getCredentials($courier),
            []
        );

        return $request;
    }

}