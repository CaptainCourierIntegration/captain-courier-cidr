<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\ParcelForce\Api;

class CompletedShipmentInfo
{
	/**
	 * @access public
	 * @var string
	 */
	public $LeadShipmentNumber;
	/**
	 * @access public
	 * @var date
	 */
	public $DeliveryDate;
	/**
	 * @access public
	 * @var string
	 */
	public $Status;
	/**
	 * @access public
	 * @var CompletedShipments
	 */
	public $CompletedShipments;
	/**
	 * @access public
	 * @var RequestedShipment
	 */
	public $RequestedShipment;
}
