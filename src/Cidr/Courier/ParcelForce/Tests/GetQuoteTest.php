<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Tests;

use Bond\Di\DiTestCase;
use Symfony\Component\DependencyInjection\Reference;
use Cidr\CidrRequest;
use Cidr\Model\Task;
use Cidr\CidrRequestContextGetQuote;
use Cidr\CidrResponse;

/**
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Courier\ParcelForce\Configuration
 * @resource Cidr\Tests\Provider\ProviderConfiguration
 * @resource __CLASS__
 */
class GetQuoteTest extends DiTestCase
{

 	public function __invoke($configurator, $container)
 	{
 		$configurator->add(
 			"getQuoteRequestProviderParcelForce",
 			GetQuoteRequestProvider::class,
 			["ParcelForce"]
 		)
 			->setFactoryService("getQuoteRequestProviderFactory")
 			->setFactoryMethod("create");
 	}

 	public function requestProvider()
 	{
 		$container = $this->setup();

 		$getQuoteProvider = $container->get("getQuoteRequestProviderParcelForce");
		$requests = array_slice($getQuoteProvider->getData(), 0, 3);

		$dataset = [];
		foreach ($requests as $request) {
			$dataset[] = [$container->get("parcelForceGetQuote"), $request];
		}

		return $dataset;
 	}

 	/** @dataProvider requestProvider */
	public function testGetQuoteDoesNotThrowException($getQuote, $request)
	{
		$response = $getQuote->submitCidrRequest($request);
	}

 	/** @dataProvider requestProvider */
	public function testGetQuoteDoesNotReturnNull($getQuote, $request)
	{
		$response = $getQuote->submitCidrRequest($request);		
		$this->assertNotNull( $response );
	}

 	/** @dataProvider requestProvider */
	public function testGetQuoteReturnsInstanceOfCidrResponse($getQuote, $request)
	{
		$response = $getQuote->submitCidrRequest($request);		
		$this->assertInstanceOf(CidrResponse::class, $response);
	}
	

}