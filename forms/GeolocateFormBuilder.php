<?php

namespace Wame\Geolocate\Forms;

use Wame\DynamicObject\Forms\BaseFormBuilder;

class GeolocateFormBuilder extends BaseFormBuilder
{
    /** {@inheritDoc} */
    public function submit($form, $values)
    {
		$geolocateFilterControl = $form->lookup(\Wame\Geolocate\Components\GeolocateFilterControl::class);
        $geolocateFilterControl->setAddress($values['address']);
        $geolocateFilterControl->setDistance($values['nearby']);
    }

}
