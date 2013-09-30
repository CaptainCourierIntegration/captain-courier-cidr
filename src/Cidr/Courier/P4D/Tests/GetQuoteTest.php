<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\P4D\Tests;

use Bond\Di\DiTestCase;
use Symfony\Component\DependencyInjection\Reference;
use Cidr\Model\Task;
use Cidr\CidrRequestContextGetQuote;
use Cidr\CidrRequest;
use Cidr\Model\Address;
use Cidr\Courier\P4D\GetQuote;
use Cidr\Tests\Provider\GetQuoteRequestProvider;

/**
 * @group integration
 * @group p4d
 * @resource Cidr\StandaloneConfiguration
 * @resource ./../Configuration.yml
 * @resource ./../../../Tests/Provider/ProviderConfiguration.yml
 * @resource __CLASS__
 */
 class GetQuoteTest extends DiTestCase
 {

 	public function __invoke($configurator, $container)
 	{
 		$configurator->add(
 			"getQuoteRequestProviderP4D",
 			GetQuoteRequestProvider::class,
 			["P4D"]
 		)
 			->setFactoryService("getQuoteRequestProviderFactory")
 			->setFactoryMethod("create");
 	}

 	public function requestProvider()
 	{
 		$container = $this->setup();

 		$getQuoteProvider = $container->get("getQuoteRequestProviderP4D");
		$requests = array_slice($getQuoteProvider->getData(), 0, 3);

		$dataset = [];
		foreach ($requests as $request) {
			$dataset[] = [$container->get("p4dGetQuote"), $request];
		}

		return $dataset;
 	}

 	/** @dataProvider requestProvider */
 	public function testSubmitRequestThrowsNotImplementedException(GetQuote $getQuote, CidrRequest $request)
 	{
		$response = $getQuote->submitCidrRequest($request);
		$this->assertNotNull($response);
 	}

 }