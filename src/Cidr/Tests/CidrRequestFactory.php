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
use Cidr\CidrRequestContextCreateConsignment;
use Cidr\CourierCredentialsManagerInterface;
use Cidr\Milk;
use Cidr\Model\Task;
use Cidr\Tests\Provider\DataProvider;

/**
 * @depends Cidr\Tests\ConsignmentGeneratorConfiguration
 * Class CidrRequestGeneratorConfiguration
 * @package Cidr\Tests
 */
class CidrRequestFactory
{
    private $consignmentProvider;
    private $courierCredentialsManager;

    private $consignments = [];

    protected  static $propertiesNotManagedByMilk = ["consignments"];

    public function __construct(
        DataProvider $consignmentProvider,
        CourierCredentialsManagerInterface $courierCredentialsManager
    )
    {
        assert(2 === count(func_get_args()));
        assert(null !== $consignmentProvider);
        assert(null != $courierCredentialsManager);

        $this->consignmentProvider = $consignmentProvider;
        $this->courierCredentialsManager = $courierCredentialsManager;
        $courierCredentialsManager->init();
    }

    private function getConsignment()
    {
        if (0 === count($this->consignments)) {
            $this->consignments = $this->consignmentProvider->getData();
            shuffle($this->consignments);
        }
        return array_shift($this->consignments);
    }

    public function create($courier)
    {
        $consignment = $this->getConsignment();
        $context = new CidrRequestContextCreateConsignment(
            $consignment->getCollectionAddress(),
            $consignment->getCollectionContact(),
            $consignment->getCollectionTime(),
            $consignment->getDeliveryAddress(),
            $consignment->getDeliveryContact(),
            $consignment->getDeliveryTime(),
            $consignment->getParcels(),
            $consignment->getContractNumber(),
            $consignment->getServiceCode()
        );
        $request = new CidrRequest(
            $context,
            Task::CREATE_CONSIGNMENT,
            $this->courierCredentialsManager->getCredentials($courier)
        );

        return $request;
    }

} 