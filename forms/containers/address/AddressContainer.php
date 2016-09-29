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
    public function configure() 
	{
        $this->addHidden('geo');
        
        $this->addText('address', ('City'))
                ->setAttribute('autocomplete', 'off')
                ->setAttribute('class', 'form-control')
                ->setAttribute('data-type', '(cities)')
                // TODO: vykomentovane pretoze pri vyhladavani nechceme ukladat
//                ->setAttribute('data-url', '/location/city?do=createCityFromGoogleMapApi')
                ->setAttribute('class', 'form-control');
        
        // TODO: stary sposob cez extension (pri znovupouzitelnych containeroch nema zmysel extension)
//		$this->addGeolocateAutocomplete('address', _('City'))
//                ->setAttribute('placeholder', _('Begin typing the name of the city'))
//				->setRequired(_('Please enter city'));
    }

}