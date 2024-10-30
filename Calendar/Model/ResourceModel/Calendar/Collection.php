<?php

namespace Uet\Calendar\Model\ResourceModel\Calendar;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Uet\Calendar\Model\Calendar', 'Uet\Calendar\Model\ResourceModel\Calendar');
    }
}