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
use Cidr\Courier\P4D\GetQuote;

/**
 * 
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Courier\P4D\Configuration
 * @resource Cidr\Tests\Provider\ProviderConfiguration
 * 
 */
 class GetQuoteTest extends DiTestCase
 {

 	public function requestProvider()
 	{
 		$container = $this->setup();

 		$courierCredentialsManager = $container->get("courierCredentialsManager");
 		$addressProvider = $container->get("addressProvider");
 		$contactProvider = $container->get("contactProvider");
 		$parcelsProvider = $container->get("parcelProvider");

 		$addresses = \Cidr\wrapCut($addressProvider->getData(), 10);
 		$contacts = \Cidr\wrapCut($contactProvider->getData(), 10);
 		$parcels = array_chunk(\Cidr\WrapCut($parcelsProvider->getData(), 30), 3);

 		$credentialsFactory = $container->get("courierCredentialsManager");
 		$contextFactory = $container->get("cidrRequestContextGetQuoteFactory");
 		$requestFactory = $container->get("cidrRequestFactory");


 		$dataset = [];
 		$numAddresses = count($addresses);
 		for ($i = 1, $j = 0; $i < $numAddresses; $i += 2, $j++) {
 			$context = $contextFactory->create(
 				$addresses[$i-1],
 				$contacts[$i-1],
 				$addresses[$i],
 				$contacts[$i],
 				$parcels[$j]
 			);
 			$request = $requestFactory->create(
 				$context,
 				Task::GET_QUOTE,
 				$credentialsFactory->getCredentials("P4D"),
 				[]
 			);
 			$dataset[] = [$container->get("p4dGetQUote"), $request];
 		}
 		return $dataset;

 	}

 	/** @dataProvider requestProvider */
 	public function testSubmitRequestThrowsNotImplementedException(GetQuote $getQuote, CidrRequest $request)
 	{
		$response = $getQuote->submitCidrRequest($request);
		$this->assertNotNull($response);
		print_r($response->getResponseContext());
 	}

 }