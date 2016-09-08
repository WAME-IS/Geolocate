<?php

namespace Wame\Geolocate\Vendor\Wame\ComponentModule;

use Nette\Application\LinkGenerator;
use Wame\ComponentModule\Registers\IComponent;
use Wame\Geolocate\Components\IGeolocateFilterControlFactory;
use Wame\MenuModule\Models\Item;

class GeolocateFilterComponent implements IComponent
{

    /** @var LinkGenerator */
    private $linkGenerator;

    /** @var IGeolocateFilterControlFactory */
    private $IGeolocateFilterControlFactory;

    public function __construct(
        LinkGenerator $linkGenerator, IGeolocateFilterControlFactory $IGeolocateFilterControlFactory
    )
    {
        $this->linkGenerator = $linkGenerator;
        $this->IGeolocateFilterControlFactory = $IGeolocateFilterControlFactory;
    }

    public function addItem()
    {
        $item = new Item();
        $item->setName($this->getName());
        $item->setTitle($this->getTitle());
        $item->setDescription($this->getDescription());
        $item->setLink($this->getLinkCreate());
        $item->setIcon($this->getIcon());

        return $item->getItem();
    }

    public function getName()
    {
        return 'geolocateFilter';
    }

    public function getTitle()
    {
        return _('GeolocateFilter');
    }

    public function getDescription()
    {
        return _('Create geolocate filter component');
    }

    public function getIcon()
    {
        return 'fa fa-filter';
    }

    public function getLinkCreate()
    {
        return $this->linkGenerator->link('Admin:GeolocateFilterControl:create');
    }

    public function getLinkDetail($componentEntity)
    {
        return $this->linkGenerator->link('Admin:GeolocateFilterControl:edit', ['id' => $componentEntity->id]);
    }

    public function createComponent()
    {
        $control = $this->IGeolocateFilterControlFactory->create();
        return $control;
    }
}
