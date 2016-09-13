<?php

namespace Wame\Geolocate\Forms\Containers;

use Wame\DynamicObject\Forms\Containers\BaseContainer;
use Wame\DynamicObject\Registers\Types\IBaseContainer;

interface IAddressContainerFactory extends IBaseContainer
{
	/** @return AddressContainer */
	public function create();
}

class AddressContainer extends BaseContainer
{
    /** @var string */
    private $type;

    /** @var string */
    private $url;
    
    
    public function configure() 
	{
        $this->addHidden('geo');
        
        $this->addText('address', ('City'))
                ->setAttribute('id', 'google-map-api-autocomplete')
                ->setAttribute('autocomplete', 'off')
                ->setAttribute('class', 'form-control')
//                ->setAttribute('data-input', $this->getHtmlId())
                ->setAttribute('data-type', '(cities)')
                ->setAttribute('data-url', '/location/city?do=createCityFromGoogleMapApi')
                ->setAttribute('class', 'form-control');
        
//		$this->addGeolocateAutocomplete('address', _('City'))
//                ->setAttribute('placeholder', _('Begin typing the name of the city'))
//				->setRequired(_('Please enter city'));
    }

}