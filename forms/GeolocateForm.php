<?php

namespace Wame\Geolocate\Forms;

use Wame\DynamicObject\Forms\BaseFormBuilder;

class GeolocateForm extends BaseFormBuilder
{
    /** {@inheritDoc} */
    public function submit($form, $values)
    {
        parent::submit($form, $values);
        
        $form->getPresenter()->redirect(":Search:Search:default", ['query' => $values['query']]);
    }

}
