<?php

namespace Uet\Calendar\Model;
use Magento\Framework\Model\AbstractModel;

class Calendar extends AbstractModel
{   

    protected function _construct()
    {
        $this->_init('Uet\Calendar\Model\ResourceModel\Calendar');
    }

}
