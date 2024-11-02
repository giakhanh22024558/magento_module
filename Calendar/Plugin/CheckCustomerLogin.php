<?php
namespace Uet\Calendar\Plugin;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\UrlInterface;

class CheckCustomerLogin
{
    protected $customerSession;
    protected $redirect;
    protected $url;

    public function __construct(
        CustomerSession $customerSession,
        RedirectInterface $redirect,
        UrlInterface $url
    ) {
        $this->customerSession = $customerSession;
        $this->redirect = $redirect;
        $this->url = $url;
    }

    public function beforeDispatch(Action $subject, RequestInterface $request)
    {
        // Check if the front name is 'calendar'
        if ($request->getFrontName() === 'calendar' && !$this->customerSession->isLoggedIn()) {
            $this->customerSession->setBeforeAuthUrl($this->url->getCurrentUrl());
            $this->redirect->redirect($subject->getResponse(), 'customer/account/login');
        }
    }
}