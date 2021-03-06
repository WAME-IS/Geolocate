<?php

namespace App\AdminModule\Presenters;

use Wame\Core\Presenters\Traits\UseParentTemplates;


class GeolocateFilterControlPresenter extends AbstractComponentPresenter
{	
    use UseParentTemplates;
    
    
    protected function getComponentIdentifier()
    {
        return 'GeolocateFilterComponent';
    }
    
    
    protected function getComponentName()
    {
        return _('GeolocateFilter component');
    }
 
}
