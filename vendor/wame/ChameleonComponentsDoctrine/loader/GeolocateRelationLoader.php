<?php

namespace Wame\Geolocate\Vendor\Wame\ChameleonComponentsDoctrine\Loader;

use Nette\DI\Container;
use Wame\Geolocate\Vendor\Wame\ChameleonComponentsDoctrine\Registers\Types\FromGeolocateRelation;
use Wame\Geolocate\Vendor\Wame\ChameleonComponentsDoctrine\Registers\Types\ToGeolocateRelation;
use Wame\ChameleonComponentsDoctrine\Registers\RelationsRegister;
use Wame\Core\Registers\StatusTypeRegister;

class GeolocateRelationLoader
{
    /** @var Container */
    private $container;

    /** @var StatusTypeRegister */
    private $statusTypeRegister;

    
    public function __construct(Container $container, StatusTypeRegister $statusTypeRegister)
    {
        $this->container = $container;
        $this->statusTypeRegister = $statusTypeRegister;
    }

    
    public function initialize(RelationsRegister $relationsRegister)
    {
        foreach ($this->statusTypeRegister as $statusType) {
            $relationsRegister->add(new FromGeolocateRelation($statusType->getAlias(), $statusType->getEntityName()));
            $relationsRegister->add(new ToGeolocateRelation($statusType->getAlias(), $statusType->getEntityName()));
        }
    }
    
}
