<?php
namespace Uet\Recommendations\Model;

use Uet\Recommendations\Api\RecommendationInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Recommendation implements RecommendationInterface
{
    protected $customerRepository;
    protected $productCollectionFactory;
    protected $timezone;
    protected $categoryFactory;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CollectionFactory $productCollectionFactory,
        TimezoneInterface $timezone,
        CategoryFactory $categoryFactory
    ) {
        $this->customerRepository = $customerRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->timezone = $timezone;
        $this->categoryFactory = $categoryFactory;
    }

    public function getRecommendations($userId = null, $budget = null)
    {
        // Example logic to fetch products
        $currentDate = $this->timezone->date()->format('Y-m-d');
        $targetDate = $this->timezone->date()->modify('+3 days')->format('Y-m-d');
        //$products = $this->productRepository->getList($this->getSearchCriteria($userId, $occasion, $budget, $limit));
        
        $occasions = [
            'christmas' => '2024-12-25',
            'new_year' => '2025-01-01',
            'valentines_day' => '2025-02-14',
            'birthday' => '2025-10-25',
            //'3days-next' => '2024-10-25'
            // Add more occasions here
        ];

        foreach ($occasions as $occasion => $date) {
            if ($targetDate == $date) {
                return $this->getProductsForOccasion($userId ,$budget, $occasion);
            }
        }

        return "Look like no occasion is assigned with " . $targetDate;
    }

    private function getProductsForOccasion($occasion)
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

}
