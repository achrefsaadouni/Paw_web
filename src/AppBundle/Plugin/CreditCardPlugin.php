<?php
/**
 * Created by PhpStorm.
 * User: achref
 * Date: 09/04/2018
 * Time: 20:54
 */

namespace AppBundle\Plugin;

use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;
use JMS\Payment\CoreBundle\Model\PaymentInstructionInterface;
use JMS\Payment\CoreBundle\Plugin\ErrorBuilder;

class CreditCardPlugin extends AbstractPlugin
{
    public function checkPaymentInstruction(PaymentInstructionInterface $instruction)
    {
        $errorBuilder = new ErrorBuilder();
        $data = $instruction->getExtendedData();

        if (!$data->get('holder')) {
            $errorBuilder->addDataError('holder', 'form.error.required');
        }
        if (!$data->get('number')) {
            $errorBuilder->addDataError('number', 'form.error.required');
        }

        if ($instruction->getAmount() > 10000) {
            $errorBuilder->addGlobalError('form.error.credit_card_max_limit_exceeded');
        }

        // more checks here ...

        if ($errorBuilder->hasErrors()) {
            throw $errorBuilder->getException();
        }
    }

    public function processes($method)
    {
        return 'credit_card' === $method;
    }
}