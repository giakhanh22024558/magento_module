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
        //dd($this->customerSession->isLoggedIn());
        $calendar = $this->calendarFactory->create();
        $collection = $calendar->getCollection();
        $collection->addFieldToFilter('customer_id', $customerId);
        //dd($collection->getItems());
        return $collection->getItems();
    }
    
    public function getCategories()
    {
        // Load the parent category by name
        $parentCategory = $this->categoryFactory->create()->load(3);
        //$parentCategory->loadByAttribute('name', 'occasions');
        $childCategories = $parentCategory->getChildrenCategories();
        //$parentId = $parentCategory->getData('entity_id');

        //dd($childCategories);

        // Load child categories
        $categoriesData = [];
        foreach ($childCategories as $category) {
            // dd($category->getData('name'));
            $categoriesData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }

        //dd($categoriesData);

        // dd($childCategories);

        return $categoriesData;
    }

    public function getCustomerId(){
        $customer = $this->customerSession->create();
        return $customer->getCustomer()->getId();
    }

}
