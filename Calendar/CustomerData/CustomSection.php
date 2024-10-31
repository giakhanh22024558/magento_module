<?php
namespace Uet\Calendar\CustomerData;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Uet\Calendar\Block\Calendars;

class CustomSection implements SectionSourceInterface
{   
    protected $calendars;

    public function __construct(Calendars $calendars)
    {
        $this->calendars = $calendars;
    }

	public function getSectionData()
	{
        //dd($this->calendars->getOccasions());
    	return [
        	'occasions' => $this->calendars->getOccasions(),
        	'customer_id' => $this->calendars->getCustomerId(),
    	];
	}
}