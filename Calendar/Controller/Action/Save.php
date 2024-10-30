<?php

namespace Uet\Calendar\Controller\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Uet\Calendar\Model\CalendarFactory;
use Uet\Calendar\Model\ResourceModel\Calendar as CalendarResource;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Message\ManagerInterface;

class Save extends Action
{
    protected $calendarFactory;
    protected $messageManager;
    protected $calendarResource;
    protected $customerSession;

    public function __construct(
        Context $context,
        CalendarFactory $calendarFactory,
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        CalendarResource $calendarResource,
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->calendarFactory = $calendarFactory;
        $this->messageManager = $messageManager;
        $this->calendarResource = $calendarResource;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        //dd($data);
        if ($data) {
            $occasionId = isset($data['id']) ? $data['id'] : null;
            if(!$occasionId) {
                $getData = [
                    'customer_id' => $data['customer_id'] ?? null,
                    'occasion' => $data['occasion'] ?? null,
                    'note' => $data['note'] ?? null,
                    'date' => $data['date'] ?? null,
                ];
            } else {
                $getData = $data;
            }
            $occasion = $this->calendarFactory->create();
            // dd($getData);
            $occasion->setData($getData);
            try {
                $this->calendarResource->save($occasion);
                $this->_eventManager->dispatch('calendar_after', ['occasion' => $occasion]);
                $this->messageManager->addSuccessMessage(__('Occasion saved successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error saving occasion.'));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Invalid data.'));
        }

        return $this->_redirect('calendar/index/index');
    }
}