<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\P4D;

use Bond\Di\DiTestCase;
use Symfony\Component\DependencyInjection\Reference;
use Cidr\Model\Task;
use Cidr\CidrRequestContextGetQuote;
use Cidr\CidrRequest;
use Cidr\Model\Address;

/**
 * 
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Courier\P4D\Configuration
 * @resource Cidr\Tests\Provider\ProviderConfiguration
 * @resource __CLASS__
 * 
 */
 class GetQuoteTest extends DiTestCase
 {
 	public $getQuote;
 	public $courierCredentialsManager;
 	public $addressProvider;

 	public function __invoke($configurator, $container)
 	{
 		$configurator->add(
 			"getQuoteTest",
 			self::class
 		)->setProperties([
 			"getQuote" => new Reference("p4dGetQuote"),
 			"courierCredentialsManager" => new Reference("courierCredentialsManager"),
 			"addressProvider" => new Reference("addressprovider")
 		]);
 	}

 	public function requestProvider()
 	{
 		$container = $this->setup();

 		$getQuote = $container->get("getQuote");
 		$courierCredentialsManager = $container->get("courierCredentialsManager");
 		$addressProvider = $container->get("addressProvider");
 		$contactProvider = $container->get("contactProvider");

 		$addresses = $addressprovider->getData();
 		$contacts = $contactProvider->getData();
 		
 		$requests = [];

 		$numAddresses = count($addresses);
 		for ($i = 1; $i < $numAddresses; $i++) {
 			$requests[] = [$addresses[$i-1], $addresses[$i], rand(1, 15)];
 		}
 		return $requests;
 	}

 	/** @dataProvider requestProvider */
 	public function testSubmitRequestThrowsNotImplementedException(Address $collectionAddress, Address $deliveryAddress, $weight)
 	{
		$request = new CidrRequest(
			new CidrRequestContextGetQuote($collectionAddress, $deliveryAddress, $weight),
			Task::GET_QUOTE,
			$this->courierCredentialsManager->getCredentials("ParcelForce"),
			[]
		);

		$response = $this->getQuote->submitCidrRequest($request);
 	}

 }