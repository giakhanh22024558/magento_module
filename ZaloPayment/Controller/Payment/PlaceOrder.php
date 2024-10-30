<?php

namespace Uet\ZaloPayment\Controller\Payment;

use Uet\ZaloPayment\Model\Payment\ZaloPayment;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Model\OrderFactory;

class PlaceOrder extends Action
{
    protected $_orderFactory;
    protected $_zaloPayment;
    protected $_checkoutSession;
    protected $_quoteRepository;

    /**
     * @param Context $context
     * @param CartRepositoryInterface $quoteRepository
     * @param OrderFactory $orderFactory
     * @param Session $checkoutSession
     * @param ZaloPayment $zaloPayment
     */
    public function __construct(
        Context $context,
        CartRepositoryInterface $quoteRepository,
        OrderFactory $orderFactory,
        Session $checkoutSession,
        ZaloPayment $zaloPayment
    ) {
        parent::__construct($context);
        $this->_quoteRepository = $quoteRepository;
        $this->_orderFactory = $orderFactory;
        $this->_zaloPayment = $zaloPayment;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCheckout()
    {
        return $this->_objectManager->get('Magento\Checkout\Model\Session');
    }

    public function execute()
    {
        $id = $this->_checkoutSession->getLastOrderId();
        $order = $this->_orderFactory->create()->load($id);
        if (!$order->getIncrementId()) {
            $this->getResponse()->setBody(json_encode([
                'status' => false,
                'reason' => 'Order Not Found',
            ]));
            return;
        }

        # RESTORES CART
        $quote = $this->_quoteRepository->get($order->getQuoteId());
        $quote->setIsActive(1);
        $this->_quoteRepository->save($quote);

        $this->getResponse()->setBody(json_encode($this->_zaloPayment->getZaloPaymentRequest($order)));
    }

}
