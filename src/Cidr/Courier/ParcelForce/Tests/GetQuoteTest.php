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
 * @resource Cidr\Tests\Provider\ProviderConfiguration
 * @resource __CLASS__
 * @service getQuoteTest
 */
class GetQuoteTest extends DiTestCase
{
	public $getQuote;
	public $courierCredentialsManager;
	public $addressProvider;

	public function __invoke($configurator, $container)
	{
		print "configurating now\n";
		$configurator->add(
			"getQuoteTest",
			self::class
		)->setProperties([
			"getQuote" => new Reference("parcelForceGetQuote"),
			"courierCredentialsManager" => new Reference("courierCredentialsManager"),
			"addressProvider" => new Reference("addressProvider")
		]);

	}

	public function testGetQuoteDoesNotThrowException()
	{
		$addresses = $this->addressProvider->getData();

		$request = new CidrRequest(
			new CidrRequestContextGetQuote($addresses[0], $addresses[1], 12),
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