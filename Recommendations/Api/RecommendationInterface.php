<?php
namespace Uet\Recommendations\Api;

interface RecommendationInterface
{
    /**
     * Get personalized gift recommendations
     * @param int $userId
     * @param string $occasion
     * @param string $budget
     * @param int $limit
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getRecommendations($userId = null, $budget = null, $interval = 3);
}
