<?php

namespace Wame\Geolocate\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;

interface IAddressFormContainerFactory extends \Wame\DynamicObject\Registers\Types\IBaseFormContainerType
{
	/** @return AddressFormContainer */
	public function create();
}

class AddressFormContainer extends BaseFormContainer
{
    public function configure() 
	{
		$form = $this->getForm();

		$form->addGeolocateAutocomplete('address', _('City'))
                ->setAttribute('placeholder', _('Begin typing the name of the city'))
				->setRequired(_('Please enter city'));
    }

}