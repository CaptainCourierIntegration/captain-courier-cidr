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

/**
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Courier\ParcelForce\Configuration
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
		)
			->setProperties([
				"getQuote" => new Reference("parcelForceGetQuote"),
				"courierCredentialsManager" => new Reference("courierCredentialsManager")
		]);

	}

	public function testGetQuoteDoesNotThrowException()
	{
		$request = new CidrRequest(
			new CidrRequestContextGetQuote("OX17 1RR", "OX17 1RR", 12),
			Task::GET_QUOTE,
			$this->courierCredentialsManager->getCredentials("ParcelForce"),
			[]
		);
		$response = $this->getQuote->submitCidrRequest($request);
		return $response;
	}

	/** @depends testGetQuoteDoesNotThrowException */
	public function testGetQuoteDoesNotReturnNull($response)
	{
		$this->assertNotNull( $response );
	}

	

}