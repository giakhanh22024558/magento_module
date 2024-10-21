<?php

namespace Uet\NewsFeed\Model;

use Magento\Framework\HTTP\Client\Curl;

class RssReader
{
    protected $httpClient;
    protected $rssUrl = 'https://vnexpress.net/rss/giai-tri.rss'; // Hardcoded RSS URL

    public function __construct(Curl $httpClient)
    {   
        $this->httpClient = $httpClient;
    }

    public function getNews()
    {   
        
        $this->httpClient->get($this->rssUrl);
        $response = $this->httpClient->getBody();

        $xml = simplexml_load_string($response);
        $newsItems = [];

        foreach ($xml->channel->item as $item) {

            $description = (string) $item->description;

            // Extract image URL and text from the description
            $linkTag = '';
            $text = $description;

            if (preg_match('/<a[^>]*>.*?<\/a>/', $description, $matches)) {
                $linkTag = $matches[0];
                $text = preg_replace('/<a[^>]*>.*?<\/a>/', '', $description); // Remove the <a> tag from the text
            }
            
            $text = preg_replace('/<\/br\s*\/?>/', '', $text);

            $newsItems[] = [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'description' => $text,
                'image' => $linkTag,
                'pubDate' => (string) $item->pubDate,
            ];
        }

        return $newsItems;
    }
}