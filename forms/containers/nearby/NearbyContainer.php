<?php

namespace Wame\Geolocate\Forms\Containers;

use Wame\DynamicObject\Forms\Containers\BaseContainer;
use Wame\DynamicObject\Registers\Types\IBaseContainer;

interface INearbyContainerFactory extends IBaseContainer
{
	/** @return NearbyContainer */
	public function create();
}

class NearbyContainer extends BaseContainer
{
	public function configure()
    {
		$this->addText('nearby', _('Nearby'))
				->setType('number');
	}

}
