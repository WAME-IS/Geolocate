<?php

namespace Wame\Geolocate\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;

interface INearbyContainerFactory extends \Wame\DynamicObject\Registers\Types\IBaseFormContainerType
{
	/** @return NearbyContainer */
	public function create();
}

class NearbyContainer extends BaseFormContainer
{
	public function configure()
    {
		$form = $this->getForm();

		$form->addText('nearby', _('Nearby'))
				->setType('number');
	}

}
