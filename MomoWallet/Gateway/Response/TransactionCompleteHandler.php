<?php

declare(strict_types=1);

namespace Uet\MomoWallet\Gateway\Response;

use Magento\Framework\Exception\LocalizedException as LocalizedExceptionAlias;
use Magento\Sales\Model\Order\Payment;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Uet\MomoWallet\Gateway\Validator\AbstractResponseValidator;

class TransactionCompleteHandler implements HandlerInterface
{
    /**
     * @var array
     */
    private array $additionalInformationMapping = [
        'transaction_id' => AbstractResponseValidator::TRANSACTION_ID
    ];

    /**
     * Handle
     *
     * @param array $handlingSubject
     * @param array $response
     * @throws LocalizedExceptionAlias
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = SubjectReader::readPayment($handlingSubject);
        /** @var Payment $orderPayment */
        $orderPayment = $paymentDO->getPayment();
        $orderPayment->setTransactionId($response[AbstractResponseValidator::TRANSACTION_ID]);
        $orderPayment->setIsTransactionClosed(false);
        $orderPayment->setShouldCloseParentTransaction(true);

        foreach ($this->additionalInformationMapping as $informationKey => $responseKey) {
            if (isset($response[$responseKey])) {
                $orderPayment->setAdditionalInformation($informationKey, ucfirst($response[$responseKey]));
            }
        }
    }
}