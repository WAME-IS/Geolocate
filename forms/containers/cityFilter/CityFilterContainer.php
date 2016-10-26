<?php

namespace Wame\Geolocate\Forms\Containers;

use Wame\DynamicObject\Forms\Containers\BaseContainer;
use Wame\DynamicObject\Registers\Types\IBaseContainer;

interface ICityFilterContainerFactory extends IBaseContainer
{
	/** @return CityFilterContainer */
	public function create();
}

class CityFilterContainer extends BaseContainer
{
    /** {@inheritDoc} */
    public function configure() 
	{
        $this->addHidden('latitude');
        $this->addHidden('longitude');
        
        $this->addText('city', ('City'))
                ->setAttribute('autocomplete', 'off')
                ->setAttribute('class', 'form-control autocomplete')
                ->setAttribute('data-type', '(cities)')
                ->setAttribute('data-autocomplete', 'geolocate')
                ->setAttribute('data-el-latitude', 'CityFilterContainer[latitude]')
                ->setAttribute('data-el-longitude', 'CityFilterContainer[longitude]');
    }

}