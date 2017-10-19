<?php

namespace Wame\Geolocate\Forms\Containers;

use Nette\Application\LinkGenerator;
use Wame\DynamicObject\Forms\Containers\BaseContainer;
use Wame\DynamicObject\Registers\Types\IBaseContainer;
use Wame\LocationModule\Repositories\AddressRepository;
use Wame\LocationModule\Repositories\CityRepository;


interface ICityContainerFactory extends IBaseContainer
{
	/** @return CityContainer */
	public function create();
}


class CityContainer extends BaseContainer
{
    /** @var LinkGenerator */
    private $linkGenerator;

    /** @var CityRepository */
    private $cityRepository;


    public function __construct(
        \Nette\DI\Container $container,
        LinkGenerator $linkGenerator,
        CityRepository $cityRepository
    ) {
        parent::__construct($container);

        $this->linkGenerator = $linkGenerator;
        $this->cityRepository = $cityRepository;
    }


    /** {@inheritDoc} */
    public function configure()
	{
        $this->addText('city', _('City'))
                ->setAttribute('autocomplete', 'off')
                ->setAttribute('placeholder', _('Start typing the city or zip code'))
                ->setAttribute('class', 'form-control autocomplete')
                ->setAttribute('data-type', '(regions)')
                ->setAttribute('data-autocomplete', 'geolocate')
                ->setAttribute('data-url', $this->linkGenerator->link('RestApi:RestApi:default', ['apiVersion' => '1', 'apiResource' => 'create-city']));
    }


    /** {@inheritDoc} */
    public function postUpdate($form, $values)
    {
        $cityEntity = $this->cityRepository->get(['id' => $values['city']]);

        $entity = $form->getEntity();
        $entity->setCity($cityEntity);
        $entity->setState($cityEntity->getState());
    }


    /** {@inheritDoc} */
    public function setDefaultValues($entity, $langEntity = null)
    {
        $this['city']->setDefaultValue($entity->getCity()->getFullTitle())->setAttribute('data-id', $entity->getCity()->getId());
    }

}
