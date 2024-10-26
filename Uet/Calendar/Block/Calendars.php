<?php
namespace Uet\Calendar\Block;

use Magento\Framework\View\Element\Template;
use Uet\Calendar\Model\CalendarFactory;

class Calendars extends Template
{
    // protected $calendar;
    protected $calendarFactory;

    public function __construct(Template\Context $context,  
        CalendarFactory $calendarFactory, 
        array $data = [])
    {   
        $this->calendarFactory = $calendarFactory;
        parent::__construct($context, $data);
    }

    public function getOccasions()
    {   
        $calendar = $this->calendarFactory->create();
        $collection = $calendar->getCollection();
        return $collection->getItems();
    }

}
