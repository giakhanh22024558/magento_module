<?php

namespace MyVendor\CurrencyExchange\Model;

class ExchangeRate
{
    protected $httpClient;

    public function __construct(\Magento\Framework\HTTP\Client\Curl $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getRates()
    {
        $url = 'https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx?b=68';
        
        // Make the HTTP request
        $this->httpClient->get($url);
        $response = $this->httpClient->getBody();
        
        // Load the XML response
        $xml = simplexml_load_string($response);

        $rates = [];
        foreach ($xml->Exrate as $rate) {
            $currencyCode = (string) $rate['CurrencyCode'];
            $buy = (string) $rate['Buy'];
            $sell = (string) $rate['Sell'];

            $rates[$currencyCode] = [
                'buy' => $buy,
                'sell' => $sell,
            ];
        }

        return $rates;
    }
}
