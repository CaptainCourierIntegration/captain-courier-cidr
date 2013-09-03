<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\ParcelForce\Api;

class InternationalInfo {
	/**
	 * @access public
	 * @var Parcels
	 */
	public $Parcels;
	/**
	 * @access public
	 * @var string
	 */
	public $ShipperExporterVatNo;
	/**
	 * @access public
	 * @var string
	 */
	public $RecipientImporterVatNo;
	/**
	 * @access public
	 * @var boolean
	 */
	public $DocumentsOnly;
	/**
	 * @access public
	 * @var string
	 */
	public $DocumentsDescription;
}
