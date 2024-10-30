<?php

namespace Uet\ZaloPayment\Model\Adminhtml\Source;

use Uet\ZaloPayment\Helper\Data as HelperData;

class Environment implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Possible environment types
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => HelperData::ENVIRONMENT_SANDBOX,
                'label' => 'Sandbox',
            ],
            [
                'value' => HelperData::ENVIRONMENT_PRODUCTION,
                'label' => 'Production'
            ]
        ];
    }
}
