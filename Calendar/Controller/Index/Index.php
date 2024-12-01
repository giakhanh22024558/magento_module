<?php
namespace Uet\Calendar\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\RedirectFactory;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $resultRedirectFactory;

    public function __construct(
        Context $context, 
        PageFactory $resultPageFactory,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }

    public function execute()
    {      
        return $this->resultPageFactory->create();
    }
}
