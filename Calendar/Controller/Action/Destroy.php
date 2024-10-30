<?php

namespace Uet\Calendar\Controller\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Uet\Calendar\Model\CalendarFactory;
use Magento\Framework\Message\ManagerInterface;

class Destroy extends Action
{
    protected $calendarFactory;
    protected $messageManager;

    public function __construct(
        Context $context,
        CalendarFactory $calendarFactory,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->calendarFactory = $calendarFactory;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $occasionId = $this->getRequest()->getParam('id'); // Assuming the record ID is passed as a parameter

        if ($occasionId) {
            try {
                $occasion = $this->calendarFactory->create();
                $occasion->load($occasionId);
                if ($occasion->getData('id')) {
                    $occasion->delete();
                    $this->_eventManager->dispatch('calendar_after', ['occasion' => $occasion]);
                    $this->messageManager->addSuccessMessage(__('Record deleted successfully.'));
                } else {
                    $this->messageManager->addErrorMessage(__('Record does not exist.'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage(__('Record ID is not specified.'));
        }

        return $this->_redirect('calendar/index/index'); // Redirect to the index page
    }
}

//$occasionId = $this->getRequest()->getParam('id'); // Assuming the record ID is passed as a parameter

        // if ($occasionId) {
        //     try {
        //         $occasion = $this->calendarFactory->create();
        //         $occasion->load($occasionId);
        //         if ($occasion->getData('id')) {
        //             $occasion->delete();
        //             $this->_eventManager->dispatch('calendar_after', ['occasion' => $occasion]);
        //             $this->messageManager->addSuccessMessage(__('Record deleted successfully.'));
        //         } else {
        //             $this->messageManager->addErrorMessage(__('Record does not exist.'));
        //         }
        //     } catch (\Exception $e) {
        //         $this->messageManager->addErrorMessage($e->getMessage());
        //     }
        // } else {
        //     $this->messageManager->addErrorMessage(__('Record ID is not specified.'));
        // }

        // return $this->_redirect('calendar/index/index'); // Redirect to the index page