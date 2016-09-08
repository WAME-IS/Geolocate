<?php

namespace Wame\Geolocate\Components;

use Nette\DI\Container;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\Core\Components\BaseControl;
use Wame\Geolocate\Forms\GeolocateForm;

interface IGeolocateFilterControlFactory
{

    /**
     * @return GeolocateFilterControl
     */
    public function create();
}

class GeolocateFilterControl extends BaseControl implements DataLoaderControl
{

    /** @persistent */
    public $address;

    /** @persistent */
    public $distance;

    /** @var GeolocateForm */
    private $geolocateForm;

    public function __construct(Container $container, GeolocateForm $geolocateForm)
    {
        parent::__construct($container);

        $this->geolocateForm = $geolocateForm;
    }

    public function createComponentGeolocateForm()
    {
        $form = $this->geolocateForm->build();
        return $form;
    }

    public function render()
    {
        
    }

    public function getDataDefinition()
    {
        if ($this->address && $this->distance) {
            //TODO
            return new DataDefinition(new DataDefinitionTarget("*", true));
        }
    }

    function getAddress()
    {
        return $this->address;
    }

    function getDistance()
    {
        return $this->distance;
    }

    function setAddress($address)
    {
        $this->address = $address;
    }

    function setDistance($distance)
    {
        $this->distance = $distance;
    }
}
