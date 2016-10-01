<?php

namespace Wame\Geolocate\Forms;

use Wame\DynamicObject\Forms\BaseForm;
use Wame\DynamicObject\Forms\BaseFormBuilder;
use Wame\Geolocate\Components\GeolocateFilterControl;

class GeolocateFormBuilder extends BaseFormBuilder
{
    /** {@inheritDoc} */
    public function submit(BaseForm $form, array $values)
    {
		$geolocateFilterControl = $form->lookup(GeolocateFilterControl::class);
        $geolocateFilterControl->setAddress($values['AddressContainer']['address']);
        $geolocateFilterControl->setDistance($values['NearbyContainer']['nearby']);
    }

}
