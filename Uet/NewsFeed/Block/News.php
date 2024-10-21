<?php
namespace Uet\NewsFeed\Block;

use Magento\Framework\View\Element\Template;
use Uet\NewsFeed\Model\RssReader;

class News extends Template
{
    protected $rssReader;

    public function __construct(Template\Context $context, RssReader $rssReader, array $data = [])
    {   
        $this->rssReader = $rssReader;
        parent::__construct($context, $data);
    }

    public function getNewsFeed()
    {   
        return $this->rssReader->getNews();
    }
}
