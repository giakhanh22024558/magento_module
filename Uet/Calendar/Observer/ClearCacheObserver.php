<?php

namespace Uet\Calendar\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class ClearCacheObserver implements ObserverInterface
{
    protected $cacheTypeList;
    protected $cacheFrontendPool;

    public function __construct(
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
    }

    public function execute(Observer $observer)
    {
        $types = ['config', 'layout', 'block_html', 'collections', 'reflection', 'db_ddl', 'eav', 'config_integration', 'config_integration_api', 'full_page', 'translate', 'config_webservice', 'compiled_config'];
        foreach ($types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}