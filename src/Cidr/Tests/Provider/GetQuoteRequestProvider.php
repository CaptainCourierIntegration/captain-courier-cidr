<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider;

use Cidr\Milk;
use Cidr\Model\Task;

class GetQuoteRequestProvider implements DataProvider
{ use Milk;
	private $courierCredentialsManager;

	private $addressProvider;
	private $contactProvider;
	private $parcelsProvider;

	private $contextFactory;
	private $requestFactory;

	private $courierName;

	/** returns array of Cidr\CidrRequest */
	public function getData()
	{

 		$addresses = $this->addressProvider->getData();
 		$contacts = $this->contactProvider->getData();
 		$parcels = $this->parcelsProvider->getData();

 		$size = min(count($addresses), count($contacts));

 		$addresses = \Cidr\wrapCut($addresses, $size);
 		$contacts = \Cidr\wrapCut($contacts, $size);

 		$parcels = array_chunk(\Cidr\WrapCut($parcels, $size*3), 3);

 		$dataset = [];
 		for ($i = 1, $j = 0; $i < $size; $i += 2, $j++) {
 			$context = $this->contextFactory->create(
 				$addresses[$i-1],
 				$contacts[$i-1],
 				$addresses[$i],
 				$contacts[$i],
 				$parcels[$j]
 			);
 			$request = $this->requestFactory->create(
 				$context,
 				Task::GET_QUOTE,
 				$this->courierCredentialsManager->getCredentials($this->courierName),
 				[]
 			);
 			$dataset[] = $request;
 		}
 		return $dataset;
 	}

}