<?php
namespace Uet\Calendar\Model;

use Uet\Calendar\Api\RecommendationInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Framework\App\RequestInterface;
use Uet\Calendar\Block\Calendars;

class Recommendation implements RecommendationInterface
{
    protected $customerRepository;
    protected $productCollectionFactory;
    protected $timezone;
    protected $categoryFactory;
    protected $tokenFactory;
    protected $request;
    protected $calendar;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CollectionFactory $productCollectionFactory,
        TimezoneInterface $timezone,
        CategoryFactory $categoryFactory,
        TokenFactory $tokenFactory,
        RequestInterface $request,
        Calendars $calendar
    ) {
        $this->customerRepository = $customerRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->timezone = $timezone;
        $this->categoryFactory = $categoryFactory;
        $this->tokenFactory = $tokenFactory;
        $this->request = $request;
        $this->calendar = $calendar;
    }

    public function getRecommendations($userId = null, $budget = null, $interval = 3)
    {
        // Example logic to fetch products
        $currentDate = new \DateTime($this->timezone->date()->format('Y-m-d'));
        $customerId = $userId ? $userId : $this->getCustomerIdFromToken();
        $occasions = $this->calendar->getOccasions($this->getCustomerIdFromToken());

        // $occasions = [
        //     'christmas' => new \DateTime('2024-12-25'),
        //     'new_year' => new \DateTime('2025-01-01'),
        //     'birthday' => new \DateTime('2024-10-27'),
        //     //'3days-next' => '2024-10-25'
        //     // Add more occasions here
        // ];

        foreach ($occasions as $occasion) {
            $occasionDate = new \DateTime($occasion['date']);
            if ($occasionDate >= $currentDate && $currentDate->diff($occasionDate)->days <= $interval) {
                return $this->getProductsForOccasion($customerId, $budget, $occasion['occasion']);
            }
        }

        return "Look like no occasion is assigned";
    }

    private function getProductsForOccasion($userId, $budget, $occasion)
    {
        // Implement logic to filter products based on the criteria (occasion, budget, etc.)
        $categoryId = $this->getCategoryIdByName($occasion);

        if (!$categoryId) {
            return "No category found for this occasion";  // 
        }

        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        $collection->joinField(
            'category_id', 
            'catalog_category_product', 
            'category_id', 
            'product_id = entity_id', 
            null, 
            'left'
        );

        $collection->addAttributeToFilter('category_id', ['eq' => $categoryId]);
        $collection->setPageSize(10);

        return $collection->getItems();
        
    }

    private function getCategoryIdByName($categoryName)
    {
        $category = $this->categoryFactory->create()
            ->getCollection()
            ->addAttributeToFilter('name', $categoryName)
            ->getFirstItem();
        
        return $category->getId();
    }   

    protected function getCustomerIdFromToken()
    {
        $authorizationHeader = $this->request->getHeader('Authorization');
        if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
            $tokenModel = $this->tokenFactory->create()->loadByToken($token);
            return $tokenModel->getCustomerId();
        }
        return null;
    }

}
