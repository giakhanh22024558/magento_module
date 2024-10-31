<?php
namespace Uet\Calendar\Block;

use Magento\Framework\View\Element\Template;
use Uet\Calendar\Model\CalendarFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Customer\Model\SessionFactory as CustomerSession;

class Calendars extends Template
{
    // protected $calendar;
    protected $calendarFactory;
    protected $categoryFactory;
    protected $customerSession;

    public function __construct(Template\Context $context,  
        CalendarFactory $calendarFactory,
        CustomerSession $customerSession,
        CategoryFactory $categoryFactory, 
        array $data = [])
    {   
        $this->categoryFactory = $categoryFactory;
        $this->customerSession = $customerSession;
        $this->calendarFactory = $calendarFactory;
        parent::__construct($context, $data);
    }

    public function getOccasions()
    {   
        $customerId = $this->getCustomerId();
        //dd($customerId);
        $calendar = $this->calendarFactory->create();
        $collection = $calendar->getCollection();
        $collection->addFieldToFilter('customer_id', $customerId);
        $occasions = [];
        foreach ($collection->getItems() as $item) {
            $occasions[] = [
                'id' => $item->getId(),
                'customer_id' => $item->getData('customer_id'),
                'occasion' => $item->getData('occasion'),
                'date' => $item->getData('date'),
                'note' => $item->getData('note')
            ];
        }
        //dd($occasions);
        return $occasions;
    }
    
    public function getCategories()
    {
        // Load the parent category by name
        $parentCategory = $this->categoryFactory->create()->load(3);
        $childCategories = $parentCategory->getChildrenCategories();
        
        $categoriesData = [];
        foreach ($childCategories as $category) {
            $categoriesData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }

        return $categoriesData;
    }

    public function getCustomerId(){
        $customer = $this->customerSession->create();
        return $customer->getCustomer()->getId();
    }

}
