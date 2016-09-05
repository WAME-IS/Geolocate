<?php

namespace Wame\Geolocate\Components;

use Doctrine\Common\Collections\Criteria;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\Core\Components\BaseControl;
use Wame\SearchModule\Forms\SearchForm;
use Wame\SearchModule\Repositories\ISearchRepository;
use Nette\DI\Container;

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
    public $query;
    
    /** @var SearchForm */
    private $nearbyForm;

    /** @var ISearchRepository */
    private $searchRepository;

    
    public function __construct(Container $container, SearchForm $searchForm, ISearchRepository $searchRepository)
    {
        parent::__construct($container);
        
        $this->searchForm = $searchForm;
        $this->searchRepository = $searchRepository;
    }

    public function createComponentSearchForm()
    {
        $form = $this->searchForm->build();
        return $form;
    }

    public function render()
    {
        
    }

    public function getDataDefinition()
    {
        $query = $this->query;
        if ($query) {
            $ids = $this->searchRepository->searchIds(['query' => $query, 'size' => 100], $type);

            $criteria = Criteria::create(Criteria::expr()->in('id', $ids));

            $dataDefinition = new DataDefinition(new DataDefinitionTarget("*", true), $criteria);

            return $dataDefinition;
        }
    }
}
