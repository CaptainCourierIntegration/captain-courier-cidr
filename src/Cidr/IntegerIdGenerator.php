<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class IntegerIdGenerator implements IdGenerator
{
	private $nextId = 0;

	/** @inheritdoc */
	public function setSeed($seed)
	{
		$this->nextId = $seed;
	}

	/** @inheritdoc */
	public function nextId()
	{
		return $this->nextId++;
	}

}