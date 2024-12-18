<?php

namespace Uet\MomoWallet\Gateway\Request;

use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class OrderDetailsDataBuilder
 *
 * @package Uet\MomoWallet\Gateway\Request
 */
class ReturnNotifyUrlDataBuilder extends AbstractDataBuilder implements BuilderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * ReturnNotifyUrlDataBuilder constructor.
     *
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        return [
            self::RETURN_URL => $this->urlBuilder->getUrl('momo/payment/return'),
            self::NOTIFY_URL => $this->urlBuilder->getUrl('momo/payment/ipn')
        ];
    }
}
