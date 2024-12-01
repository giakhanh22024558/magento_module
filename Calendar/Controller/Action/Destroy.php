<?php

namespace Uet\Calendar\Controller\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Uet\Calendar\Model\CalendarFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Destroy extends Action
{
    protected $calendarFactory;
    protected $messageManager;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        CalendarFactory $calendarFactory,
        ManagerInterface $messageManager,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->calendarFactory = $calendarFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $occasionId = $this->getRequest()->getParam('id'); // Assuming the record ID is passed as a parameter
        $message = null;

        if ($occasionId) {
            try {
                $occasion = $this->calendarFactory->create();
                $occasion->load($occasionId);
                if ($occasion->getData('id')) {
                    $occasion->delete();
                    //$this->_eventManager->dispatch('calendar_after', ['occasion' => $occasion]);
                    // $this->messageManager->addSuccessMessage(__('Record deleted successfully.'));
                    $message = "Occasion deleted successfully.";
                } else {
                    // $this->messageManager->addErrorMessage(__('Record does not exist.'));
                    $message = "Occasion does not exist.";
                }
            } catch (\Exception $e) {
                // $this->messageManager->addErrorMessage($e->getMessage());
                $message = $e->getMessage();
            }
        } else {
            // $this->messageManager->addErrorMessage(__('Record ID is not specified.'));
            $message = "Occasion ID is not specified.";
        }

        $result = $this->resultJsonFactory->create();
        return $result->setData(['message' => $message]); // Redirect to the index page
    }
}

