<?php

namespace Wame\Geolocate\Forms;

class GeolocateForm extends \Wame\DynamicObject\Forms\BaseForm
{
    /** {@inheritDoc} */
    public function submit($form, $values)
    {
		$geolocateFilterControl = $form->lookup(\Wame\Geolocate\Components\GeolocateFilterControl::class);
        $geolocateFilterControl->setAddress($values['address']);
        $geolocateFilterControl->setDistance($values['nearby']);
    }

}
