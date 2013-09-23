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
 * @resource __CLASS__
 *
 */
 class GetQuoteTest extends DiTestCase
 {
 	public $getQuote;
 	public $courierCredentialsManager;

 	public function __invoke($configurator, $container)
 	{
 		$configurator->add(
 			"getQuoteTest",
 			self::class
 		)->setProperties([
 			"getQuote" => new Reference("p4dGetQuote"),
 			"courierCredentialsManager" => new Reference("courierCredentialsManager")
 		]);
 	}

 	public function testSubmitRequestThrowsNotImplementedException()
 	{
		$request = new CidrRequest(
			new CidrRequestContextGetQuote("OX17 1RR", "OX17 1RR", 12),
			Task::GET_QUOTE,
			$this->courierCredentialsManager->getCredentials("ParcelForce"),
			[]
		);

		$response = $this->getQuote->submitCidrRequest($request);
		print_r($response);
 	}

 }