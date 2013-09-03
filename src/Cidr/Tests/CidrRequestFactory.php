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

/**
 * @depends Cidr\Tests\ConsignmentGeneratorConfiguration
 * Class CidrRequestGeneratorConfiguration
 * @package Cidr\Tests
 */
class CidrRequestFactory
{
    use Milk {
        Milk::__construct as __milk__construct;
    }

    public function __construct(
        Factory $consignmentFactory,
        CourierCredentialsManagerInterface $courierCredentialsManager
    )
    {
        $this->__milk__construct(
            $consignmentFactory,
            $courierCredentialsManager
        );
        $courierCredentialsManager->init();
    }

    private $consignmentFactory;
    private $courierCredentialsManager;

    public function create($courier)
    {
        $consignment = $this->consignmentFactory->create();
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