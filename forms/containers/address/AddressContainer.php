<?php

namespace Wame\Geolocate\Forms\Containers;

use Wame\DynamicObject\Forms\Containers\BaseContainer;
use Wame\DynamicObject\Registers\Types\IBaseContainer;
use Wame\LocationModule\Repositories\AddressRepository;
use Wame\LocationModule\Repositories\CityRepository;
use Wame\LocationModule\Repositories\RegionRepository;
use Wame\LocationModule\Repositories\StateRepository;
use Wame\LocationModule\Repositories\ContinentRepository;
use Wame\LocationModule\Entities\AddressEntity;
use Wame\LocationModule\Entities\CityEntity;
use Wame\LocationModule\Entities\CityLangEntity;
use Wame\LocationModule\Entities\RegionEntity;
use Wame\LocationModule\Entities\RegionLangEntity;

interface IAddressContainerFactory extends IBaseContainer
{
	/** @return AddressContainer */
	public function create();
}

class AddressContainer extends BaseContainer
{
    /** @var AddressRepository */
    protected $addressRepository;
    
    /** @var CityRepository */
    protected $cityRepository;
    
    /** @var RegionRepository */
    protected $regionRepository;
    
    /** @var StateRepository */
    protected $stateRepository;
    
    /** @var ContinentRepository */
    protected $continentRepository;
    
    
    public function __construct(
        AddressRepository $addressRepository,
        CityRepository $cityRepository,
        RegionRepository $regionRepository,
        StateRepository $stateRepository,
        ContinentRepository $continentRepository
    ) {
        parent::__construct();
        
        $this->addressRepository = $addressRepository;
        $this->cityRepository = $cityRepository;
        $this->regionRepository = $regionRepository;
        $this->stateRepository = $stateRepository;
        $this->continentRepository = $continentRepository;
    }
    
    
    /** {@inheritDoc} */
    public function configure() 
	{
        $container = 'AddressContainer';
        
        $this->addHidden('street');
        $this->addHidden('houseNumber');
        $this->addHidden('city');
        $this->addHidden('state');
        $this->addHidden('zipCode');
        $this->addHidden('latitude');
        $this->addHidden('longitude');
        $this->addHidden('importId');
        $this->addHidden('region');
        
        $this->addText('address', ('Address'))
                ->setAttribute('autocomplete', 'off')
                ->setAttribute('class', 'form-control autocomplete')
                ->setAttribute('data-type', 'geocode')
                ->setAttribute('data-autocomplete', 'geolocate')
                ->setAttribute('data-el-route', $container . "[street]")
                ->setAttribute('data-el-street_number', $container . "[houseNumber]")
                ->setAttribute('data-el-locality', $container . "[city]")
                ->setAttribute('data-el-country', $container . "[state]")
                ->setAttribute('data-el-postal_code', $container . "[zipCode]")
                ->setAttribute('data-el-latitude', $container . "[latitude]")
                ->setAttribute('data-el-longitude', $container . "[longitude]")
                ->setAttribute('data-el-place_id', $container . "[importId]")
                ->setAttribute('data-el-administrative_area_level_1', $container . "[region]");
    }
    
    /** {@inheritDoc} */
    public function postUpdate($form, $values)
    {
        $lang = $this->addressRepository->lang;
        
        $state = $this->stateRepository->get(['code' => $values['state']]);
        
        $region = $values['region'] ? (new RegionEntity)
                ->addLang($lang, (new RegionLangEntity)->setLang($lang)->setTitle($values['region']))
                ->setState($state) : null;
        
        $city = (new CityEntity)
                ->setRegion($region)
                ->setState($state)
                ->setImportId($values['importId'])
                ->setZipCode($values['zipCode'])
                ->setLatitude($values['latitude'])
                ->setLongitude($values['longitude'])
                ->addLang($lang, (new CityLangEntity)->setLang($lang)->setTitle($values['city']));
        
        $address = $this->addressRepository->create(
            (new AddressEntity)
                ->setTitle($values['address'])
                ->setState($state)
                ->setStreet($values['street'])
                ->setHouseNumber($values['houseNumber'])
                ->setCity($city)
        );
        
        $this->getRelationEntity($form)->setAddress($address);
    }
    
    /** {@inheritDoc} */
    public function setDefaultValues($entity, $langEntity = null)
    {
        $address = $entity->getAddress();
        
        if($address) {
            $this['address']->setDefaultValue($address->getTitle());
            $this['street']->setDefaultValue($address->getStreet());
            $this['houseNumber']->setDefaultValue($address->getHouseNumber());
            
            $state = $address->getState();
            if($state) {
                $this['state']->setDefaultValue($state->getCode());
            }

            $city = $address->getCity();
            if($city) {
                $this['city']->setDefaultValue($city->getTitle());
                $this['zipCode']->setDefaultValue($city->getZipCode());
                $this['latitude']->setDefaultValue($city->getLatitude());
                $this['longitude']->setDefaultValue($city->getLongitude());
                $this['importId']->setDefaultValue($city->getImportId());
//                $this['region']->setDefaultValue($city->getRegion()->getTitle());
            }
        }
    }
    
    /**
     * Get relation entity
     * 
     * @return BaseEntity
     */
    protected function getRelationEntity()
    {
        return $this->getForm()->getEntity();
    }

}