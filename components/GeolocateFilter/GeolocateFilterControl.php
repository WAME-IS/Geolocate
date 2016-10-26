<?php

namespace Wame\Geolocate\Components;

use Doctrine\Common\Collections\Criteria;
use Nette\DI\Container;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\Core\Components\BaseControl;
use Wame\Geolocate\Forms\GeolocateFormBuilder;
use Wame\LocationModule\Entities\CityEntity;

interface IGeolocateFilterControlFactory
{
    /** @return GeolocateFilterControl */
    public function create();
}

class GeolocateFilterControl extends BaseControl implements DataLoaderControl
{
    /** @var float @persistent */
    public $latitude;
    
    /** @var float @persistent */
    public $longitude;

    /** @var float @persistent */
    public $radius;

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
        if ($this->getLatitude() && $this->getLongitude() && $this->getRadius()) {
            $this->getPresenter()->getStatus()->set("latitude", $this->getLatitude());
            $this->getPresenter()->getStatus()->set("longitude", $this->getLongitude());
            $this->getPresenter()->getStatus()->set("radius", $this->getRadius());
        
            $criteria = Criteria::create();
            $criteria->andWhere($criteria->expr()->eq("id", 1));
            
            $dataDefinition = new DataDefinition(new DataDefinitionTarget("*", true));
            $dataDefinition->addRelation(new DataDefinitionTarget(CityEntity::class, false), $criteria);
            
            return $dataDefinition;
        }
    }

    /**
     * Get latitude
     * 
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * Get longitude
     * 
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get distance
     * 
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * Set latitude
     * 
     * @param float $latitude   latitude
     * @return \Wame\Geolocate\Components\GeolocateFilterControl
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        
        return $this;
    }
    
    /**
     * Set longitude
     * 
     * @param float $longitude  longitude
     * @return \Wame\Geolocate\Components\GeolocateFilterControl
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        
        return $this;
    }

    /**
     * Set radius
     * 
     * @param float $radius radius
     * @return \Wame\Geolocate\Components\GeolocateFilterControl
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
        
        return $this;
    }
    
    
    /** components ************************************************************/
    
    protected function createComponentGeolocateForm()
    {
        $form = $this->geolocateFormBuilder->build();
        
        if($this->getRadius()) {
            $form['NearbyContainer']['nearby']->setValue($this->getRadius());
        }
        
        return $form;
    }
    
}
