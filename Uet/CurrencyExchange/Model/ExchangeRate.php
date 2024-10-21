<?php

namespace Uet\CurrencyExchange\Model;

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
        $datetime = (string) $xml->DateTime;

        $rates = [];
        foreach ($xml->Exrate as $rate) {
            $currencyCode = (string) $rate['CurrencyCode'];
            $currencyName = (string) $rate['CurrencyName'];
            $transfer = (string) $rate['Transfer'];
            $buy = (string) $rate['Buy'];
            $sell = (string) $rate['Sell'];

            $rates[$currencyCode] = [
                'buy' => $buy,
                'sell' => $sell,
                'transfer' => $transfer,        
                'name' => $currencyName,
            ];
        }

        return [
            'datetime' => $datetime,
            'rates' => $rates,
        ];
    }
}
