<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 *
 * generates unique identifies. guarentees that nextId will never return a repeated id.
 *
 */
interface IdGenerator
{

	/**
	 * @param mixed $seed set's the generators seed. 
	 */
	public function setSeed($seed);

	/**
	 * @return mixed next unique id
	 */
	public function nextId();

}

