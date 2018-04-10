<?php
/**
 * Created by PhpStorm.
 * User: achref
 * Date: 09/04/2018
 * Time: 20:51
 */

namespace AppBundle\Plugin;

use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;

class PaypalPlugin extends AbstractPlugin
{
    public function processes($name)
    {
        return 'paypal' === $name;
    }
}