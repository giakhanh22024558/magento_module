<?php

namespace Uet\Calendar\Controller\Action;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Uet\Calendar\Model\CalendarFactory;
use Magento\Framework\Message\ManagerInterface;

class Save extends Action
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
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $occasion = $this->calendarFactory->create();
            $occasion->setData($data);
            try {
                $occasion->save();
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