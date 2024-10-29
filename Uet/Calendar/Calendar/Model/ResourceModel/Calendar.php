<?php

namespace Uet\Calendar\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Calendar extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('occasions', 'id');
    }
}