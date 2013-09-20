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
use Symfony\Component\DependencyInjection\Definition;
use Cidr\Model\Task;
use Cidr\CidrResponse;
use Cidr\CidrRequest;
use Cidr\CidrRequestContextGetTracking;
use Cidr\CidrResponseContextGetTracking;
use Cidr\Exception\InvalidArgumentException;

/**
 * @resource Cidr\StandaloneConfiguration
 * @resource Cidr\Courier\ParcelForce\Configuration
 * @resource __CLASS__
 * @service getTrackingTest
 *
 */
class GetTrackingTest extends DiTestCase
{
	public $getTracking;
	public $request;

	public function __invoke($configurator, $container)
	{
		$configurator->add(
			"getTrackingTest",
			self::class
		)
			->setProperties([
				"getTracking" => new Reference("parcelForceGetTracking"),
				"request" => new Reference("request")
			]);

		$configurator->add(
			"requestContext",
			CidrRequestContextGetTracking::class,
			[
				"MK0730971"//"MK0730971"
			]
		);

		$configurator->add(
			"parcelForceCredentials",
			\stdclass::class,
			["ParcelForce"]
		)
			->setFactoryService("courierCredentialsManager")
			->setFactoryMethod("getCredentials")
			->setPublic(false);

		$configurator->add(
			"request",
			CidrRequest::class,
			[  
				new Reference("requestContext"),
				Task::GET_TRACKING,
				new Reference("parcelForceCredentials"),
				[]
			]
		);
	}


	/*
	 *  METHOD - getTask 
	 */

	public function testGetTaskIsNotNull()
	{
		$this->assertNotNull($this->getTracking->getTask());
	}

	public function testGetTaskHasCorrectValue()
	{
		$this->assertEquals(Task::GET_TRACKING, $this->getTracking->getTask());
	}

	public function testGetTaskDoesNotThrowException()
	{
		$this->getTracking->getTask();
	}

	public function testGetTaskDoesNotChange()
	{
		$this->assertEquals(
			$this->getTracking->getTask(), 
			$this->getTracking->getTask()
		);
	}

	/*
	 * METHOD - getCourier
	 */

	public function testGetCourierIsNotNull()
	{
		$this->assertNotNull($this->getTracking->getCourier());
	}

	public function testGetCourierHasCorrectValue()
	{
		$this->assertEquals("ParcelForce", $this->getTracking->getCourier());
	}

	public function testGetCourierDoesNotThrowException()
	{
		$this->getTracking->getCourier();
	}

	public function testGetCourierValueDoesNotChange()
	{
		$this->assertEquals(
			$this->getTracking->getCourier(),
			$this->getTracking->getCourier()
		);
	}

	/*
	 * METHOD - submitCidrRequest
	 */

	public function testSubmitCidrRequestDoesNotReturnNull()
	{
		$response = $this->getTracking->submitCidrRequest($this->request);
		$this->assertNotNull($response);
		return $response;
	}

	/** @depends testSubmitCidrRequestDoesNotReturnNull */
	public function testSubmitCidrRequestReturnsCidrResponse($response)
	{
		$this->assertInstanceOf(
			CidrResponse::class,
			$response
		);
	}

	public function testSubmitCidrRequestDoesNotThrowException()
	{
		$this->getTracking->submitCidrRequest($this->request);
	}


	/** @depends testSubmitCidrRequestDoesNotReturnNull */
	public function testCidrResponseHasCidrResponseContextGetTracking($response)
	{
		$this->assertInstanceOf(
			CidrResponseContextGetTracking::class,
			$response->getResponseContext()
		);
		return $response;
	}

	/** @depends testSubmitCidrRequestDoesNotReturnNull */
	public function testSubmitCidrRequestReturnsResponseWithMultipleEntries($response)
	{
		$this->assertGreaterThan(
			0,
			count($response->getResponseContext()->getTrackingLog())
		);
	}


}