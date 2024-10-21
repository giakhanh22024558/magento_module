<?php

namespace Uet\CurrencyExchange\Block;

use Magento\Framework\View\Element\Template;
use Uet\CurrencyExchange\Model\ExchangeRate;

class ExchangeRates extends Template
{
    protected $exchangeRate;

    public function __construct(
        Template\Context $context,
        ExchangeRate $exchangeRate,
        array $data = []
    ) {
        $this->exchangeRate = $exchangeRate;
        parent::__construct($context, $data);
    }

    public function getExchangeRates()
    {
        return $this->exchangeRate->getRates();
    }
}
