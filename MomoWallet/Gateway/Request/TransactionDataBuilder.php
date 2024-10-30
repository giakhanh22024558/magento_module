<?php

declare(strict_types=1);

namespace Uet\MomoWallet\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class TransactionDataBuilder extends AbstractDataBuilder implements BuilderInterface
{
    /**
     * Method
     */
    public const METHOD = 'method';

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject): array
    {
        $paymentDO = $buildSubject['payment'];
        $payment = $paymentDO->getPayment();

        return [
            self::REQUEST_TYPE => $payment->getAdditionalInformation('request_type')
        ];
    }
}
