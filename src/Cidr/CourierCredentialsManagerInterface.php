<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Cidr\Exception\CourierNotFoundException;

interface CourierCredentialsManagerInterface
{

    /**
     * loads in the credentials
     * @param $requiredCouriers list of couriers that are required to have
     * credentials, otherwise throws exception
     * @return CourierCredentialsManagerInterface return itself
     * @throws CourierNotFoundException if all the requiredCouriers were not
     * satisfied
     */
    public function init(array $requiredCouriers = []);

    /**
     *
     * @return array of strings for all couriers that have credentials
     * @throws IllegalStateException if init not called
     */
    public function getCouriers();

    /**
     * @param $courier name of courier to get credentials of
     * @return array of credentials for that courier,
     * for example ParcelForce would have credentials
     * [username => "user123", password => "password1"]
     * @throws IllegalStateException if init not called
     * @throws CourierNotFoundException if $courier not in credentials
     */
    public function getCredentials($courier);

}