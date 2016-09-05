<?php

namespace Wame\Geolocate\Forms;

class GeolocateForm extends \Wame\DynamicObject\Forms\BaseForm
{
    /** {@inheritDoc} */
    public function submit($form, $values)
    {
        parent::submit($form, $values);
        
        $form->getPresenter()->redirect(":Search:Search:default", ['query' => $values['query']]);
    }

}
