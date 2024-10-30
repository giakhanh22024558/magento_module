<?php

namespace Uet\ZaloPayment\Controller\Payment;

use Uet\ZaloPayment\Helper\ZaloPayHelper;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart;

class Redirect extends Action
{
    protected $_zaloPayHelper;
    protected $_cart;
    
    private RedirectInterface $redirect;

    private ResponseInterface $response;

    /**
     * @param Context $context
     * @param ZaloPayHelper $zaloPayHelper
     * @param Cart $cart
     */
    public function __construct(
        Context $context,
        ZaloPayHelper $zaloPayHelper,
        Cart $cart
    ) {
        parent::__construct($context);
        $this->_zaloPayHelper = $zaloPayHelper;
        $this->_cart = $cart;
        $this->redirect = $context->getRedirect();
        $this->response = $context->getResponse();
    }
    
    /**
     * set order is paid
     */
    public function execute()
    {
//        \Uet\ZaloPayment\Helper\Log::logRedirect();
        # EMPTY CART
        $this->_cart->truncate();
        $this->_cart->saveQuote();
        $data = $this->getRequest()->getParams();
        if (isset($data['checksum'])
            && isset($data['appid'])
            && isset($data['apptransid'])
            && isset($data['pmcid'])
            && isset($data['bankcode'])
            && isset($data['amount'])
            && isset($data['discountamount'])
            && isset($data['status'])
        ) {
            $isValidRedirect = $this->_zaloPayHelper->verifyRedirect($data);
            if ($isValidRedirect) {
                if ($data['status'] == 1) {
                    # CUSTOMER NOTIFICATION
                    $this->messageManager->addSuccess(
                        __('You paid via ZaloPay successfully.')
                    );
                    $this->_redirect('checkout/onepage/success');
                    return;
                } elseif ($data['status'] == -49) {
                    # CUSTOMER NOTIFICATION
                    $this->messageManager->addWarning(
                        __("You have canceled your order")
                    );
                    $this->_redirect('checkout/onepage/failure');
                    return;
                }
            }
        }
        
        $this->messageManager->addError(
            __('You pay via ZaloPay failed.')
        );
        $this->_redirect('checkout/onepage/failure');
    }
    
    protected function _redirect($path, $arguments = []): ResponseInterface
    {
        $this->redirect->_redirect($this->response, $path, $arguments);
        return $this->response;
    }
}
