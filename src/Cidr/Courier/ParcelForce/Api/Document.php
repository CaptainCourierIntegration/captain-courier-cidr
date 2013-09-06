<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\ParcelForce\Api;

class Document
{
	/**
	 * @access public
	 * @var string this is the text that needs to be put in a file.
     * no decoding necessary, just write the contents of this to a file then
     * open it with a pdf reader.
	 */
	public $Data;
}
