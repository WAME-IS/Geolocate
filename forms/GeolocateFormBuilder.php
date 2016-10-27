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
        $geolocateFilterControl->setCity($values['CityFilterContainer']['city']);
        $geolocateFilterControl->setLatitude($values['CityFilterContainer']['latitude']);
        $geolocateFilterControl->setLongitude($values['CityFilterContainer']['longitude']);
        $geolocateFilterControl->setRadius($values['NearbyContainer']['nearby']);
    }

}
