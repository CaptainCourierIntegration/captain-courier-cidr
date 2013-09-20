<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Model;

/**
  * hello world
  */
class Task
{
    // TODO capitalise this
    const CREATE_CONSIGNMENT = "CreateShipment";
    const PRINT_LABEL = "PrintLabel";

    static $Tasks = array(
        self::CREATE_CONSIGNMENT,
        self::PRINT_LABEL
    );
}

