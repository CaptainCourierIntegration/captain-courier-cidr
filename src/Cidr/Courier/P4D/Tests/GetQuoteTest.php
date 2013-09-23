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
 		$this->setup();
 		$addresses = $addressprovider->getData();
 		$requests = [];
 		foreach ($addresses as $address) {

 		}
 	}

 	public function testSubmitRequestThrowsNotImplementedException()
 	{
 		$addresses = $this->addressProvider->getData();
		$request = new CidrRequest(
			new CidrRequestContextGetQuote($addresses[0], $addresses[1], 12),
			Task::GET_QUOTE,
			$this->courierCredentialsManager->getCredentials("ParcelForce"),
			[]
		);

		$response = $this->getQuote->submitCidrRequest($request);
 	}

 }