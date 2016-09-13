<?php

namespace Wame\Geolocate\Components;

use Nette\DI\Container;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\Core\Components\BaseControl;
use Wame\Geolocate\Forms\GeolocateFormBuilder;

interface IGeolocateFilterControlFactory
{
    /** @return GeolocateFilterControl */
    public function create();
}

class GeolocateFilterControl extends BaseControl implements DataLoaderControl
{
    /** @persistent */
    public $address;

    /** @persistent */
    public $distance;

    /** @var GeolocateFormBuilder */
    private $geolocateFormBuilder;

    
    public function __construct(Container $container, GeolocateFormBuilder $geolocateFormBuilder)
    {
        parent::__construct($container);

        $this->geolocateFormBuilder = $geolocateFormBuilder;
    }
    
    
    /** {@inheritDoc} */
    public function render()
    {
        
    }

    /** {@inheritDoc} */
    public function getDataDefinition()
    {
        if ($this->address && $this->distance) {
            //TODO
            return new DataDefinition(new DataDefinitionTarget("*", true));
        }
    }

    /**
     * Get address
     * 
     * @return string
     */
    function getAddress()
    {
        return $this->address;
    }

    /**
     * Get distance
     * 
     * @return string
     */
    function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set address
     * 
     * @param string $address   address
     * @return \Wame\Geolocate\Components\GeolocateFilterControl
     */
    function setAddress($address)
    {
        $this->address = $address;
        
        return $this;
    }

    /**
     * Set distance
     * 
     * @param string $distance  distance
     * @return \Wame\Geolocate\Components\GeolocateFilterControl
     */
    function setDistance($distance)
    {
        $this->distance = $distance;
        
        return $this;
    }
    
    
    protected function createComponentGeolocateForm()
    {
        $form = $this->geolocateFormBuilder->build();
        return $form;
    }
    
}
