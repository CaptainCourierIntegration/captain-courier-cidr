<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr;

interface CourierCapability
{
    /**
     * @return string task type, such as CREATE_CONSIGNMENT.
     */
    function getTask();

    /**
     * @return string name of courier, such as PARCEL_FORCE
     */
    function getCourier();

    /**
     * @return CidrResponse
     */
    function submitCidrRequest(CidrRequest $request); 

    /** 
     * @return mixed CidrValidationViolation[] or true/false
     */
    function validate(CidrRequest $request);
}
