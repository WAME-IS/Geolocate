<?php

namespace Wame\Geolocate\Vendor\Wame\ChameleonComponentsDoctrine\Registers\Types;

use Kdyby\Doctrine\QueryBuilder;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\Definition\DataSpace;
use Wame\ChameleonComponentsDoctrine\Registers\Types\IRelation;
use Wame\Core\Entities\BaseEntity;
use Wame\TagModule\Entities\TagEntity;
use Wame\TagModule\Entities\TagItemEntity;

class FromGeolocateRelation implements IRelation
{

    /** @var string */
    private $type;

    /** @var string */
    private $className;

    public function __construct($type, $className)
    {
        $this->type = $type;
        $this->className = $className;
    }

    /**
     * @return DataDefinitionTarget
     */
    public function getFrom()
    {
        return new DataDefinitionTarget(TagEntity::class, true);
    }

    /**
     * @return DataDefinitionTarget
     */
    public function getTo()
    {
        return new DataDefinitionTarget($this->className, false);
    }

    /**
     * @param QueryBuilder $qb
     * @param DataSpace $from
     * @param DataSpace $to
     * @param string $relationAlias
     */
    public function process(QueryBuilder $qb, $from, $to, $relationAlias)
    {
        $item = $to->getControl()->getStatus()->get($this->className);
        $mainAlias = $qb->getAllAliases()[0];

        $qb->innerJoin(TagItemEntity::class, $relationAlias);
        $qb->andWhere($mainAlias . ' = ' . $relationAlias . '.tag');
//        $qb->andWhere($mainAlias . '.type = :type')->setParameter('type', $this->type);
        $qb->andWhere($relationAlias . '.item_id = :item')->setParameter('item', $item->getId());

        /*
          if ($from->getDataDefinition()->getQueryType() == 'select') {
          $qb->select([$mainAlias, $relationAlias]);
          } else {

          } */
    }

    /**
     * @param BaseEntity[] $result
     * @param DataSpace $from
     * @param DataSpace $to
     */
    public function postProcess(&$result, $from, $to)
    {
        /*
          if ($from->getDataDefinition()->getQueryType() == 'select') {
          $filteredResult = [];

          $item = $to->getControl()->getStatus()->get($this->className);

          $entity = null;
          foreach ($result as $se) {
          if ($se instanceof TagEntity) {
          $entity = $se;
          }
          if ($se instanceof TagItemEntity) {
          if ($se->item_id == $item->getId()) {
          $filteredResult[] = $entity;
          }
          }
          }

          $result = $filteredResult;
          }
         */
    }

    /**
     * @param mixed $hint
     * @return boolean
     */
    public function matchHint($hint)
    {
        return false;
    }
}
