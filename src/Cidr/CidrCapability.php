<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Cidr\Exception\NotImplementedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * basically a wrapper for a cidr interface.
 * provides validation methods that combine
 * the yml validation with advanced validation defined on Cidr itnerface.
 * each instance will be specific to that cidr's courier and that task.
 */
class CidrCapability implements CourierCapability
{ use Milk;

    /** CourierCapability */
    private $courierCapability;

    /** CidrValidator */
    private $cidrValidator;

    public function getTask()
    {
        return $this->courierCapability->getTask();
    }

    public function getCourier()
    {
        return $this->courierCapability->getCourier();
    }

    public function validate(CidrRequest $request)
    {
        return $this->cidrValidator->validate(
            $this->getCourier(),
            $this->getTask(),
            $request
        );
    }

    /**
     * @return CidrResponseContextFailed
     */
    public function submitCidrRequest(CidrRequest $request)
    {
        $violations = $this->validate($request);
        if (true === $violations or 0 === count($violations)) {
            return $this->courierCapability->submitCidrRequest($request);
        } else {
            return new CidrResponse(
                $request,
                $this,
                CidrResponse::STATUS_FAILED,
                new CidrResponseContextValidationFailed($violations)
            );
        }
    }

    function __toString()
    {
        return "CidrCapability{courier={$this->getCourier()}, task={$this->getTask()}}";
    }

}