<?php

namespace Uet\Calendar\Controller\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Uet\Calendar\Model\CalendarFactory;
use Uet\Calendar\Model\ResourceModel\Calendar as CalendarResource;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Save extends Action
{
    protected $calendarFactory;
    protected $messageManager;
    protected $calendarResource;
    protected $customerSession;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        CalendarFactory $calendarFactory,
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        CalendarResource $calendarResource,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->calendarFactory = $calendarFactory;
        $this->messageManager = $messageManager;
        $this->calendarResource = $calendarResource;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $message = null;

        if ($data) {
            $occasionId = isset($data['id']) ? $data['id'] : null;
            if(!$occasionId) {
                $getData = [
                    'customer_id' => $data['customer_id'] ?? null,
                    'occasion' => $data['occasion'] ?? null,
                    'note' => $data['note'] ?? null,
                    'date' => $data['date'] ?? null,
                ];
                $message = 'Occasion added successfully.';
            } else {
                $getData = $data;
                $message = 'Occasion updated successfully.';
            }
            $occasion = $this->calendarFactory->create();
            $occasion->setData($getData);
            try {
                $this->calendarResource->save($occasion);
                //$this->_eventManager->dispatch('calendar_after', ['occasion' => $occasion]);
                // $this->messageManager->addSuccessMessage(__($message));
            } catch (\Exception $e) {
                // $this->messageManager->addErrorMessage(__('Error saving occasion.'));
                $message = $e->getMessage();
            }
        } else {
            // $this->messageManager->addErrorMessage(__('Invalid data.'));
            $message = 'Invalid data.';
        }

        $result = $this->resultJsonFactory->create();
        return $result->setData(['message' => $message]);
    }
}