<?php

namespace Wame\Geolocate\Vendor\Wame\ChameleonComponentsDoctrine\Registers\Types;

use Kdyby\Doctrine\QueryBuilder;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\Definition\DataSpace;
use Wame\ChameleonComponentsDoctrine\Registers\Types\IRelation;
use Wame\Core\Entities\BaseEntity;
use Wame\LocationModule\Entities\CityEntity;
use Wame\SiteModule\Entities\SiteItemEntity;
use Wame\SiteModule\Entities\SiteEntity;
use Wame\UserModule\Entities\CompanyEntity;
use Wame\LocationModule\Entities\AddressEntity;
use Doctrine\ORM\Query\Expr\Join;

class ToGeolocateRelation implements IRelation
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
        return new DataDefinitionTarget($this->className, true);
    }

    /**
     * @return DataDefinitionTarget
     */
    public function getTo()
    {
        return new DataDefinitionTarget(CityEntity::class, false);
    }

    /**
     * @param QueryBuilder $qb
     * @param DataSpace $from
     * @param DataSpace $to
     * @param string $relationAlias
     */
    public function process(QueryBuilder $qb, $from, $to, $relationAlias)
    {
        $tag = $to ? $to->getControl()->getStatus()->get($this->className) : null;
        $mainAlias = $qb->getAllAliases()[0];

        $qb->innerJoin(SiteItemEntity::class, $relationAlias, Join::WITH, "$relationAlias.item_id = $mainAlias.id");
        $qb->innerJoin(SiteEntity::class, $relationAlias, Join::WITH, "$relationAlias.item_id = $mainAlias.id");
        $qb->innerJoin(CompanyEntity::class, $relationAlias, Join::WITH, "$relationAlias.item_id = $mainAlias.id");
        $qb->innerJoin(AddressEntity::class, $relationAlias, Join::WITH, "$relationAlias.item_id = $mainAlias.id");
        $qb->innerJoin(CityEntity::class, $relationAlias, Join::WITH, "$relationAlias.item_id = $mainAlias.id");
        
//        SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;
        
        $qb->andWhere($relationAlias . '.item_id = ' . $mainAlias . '.id');
//        $qb->andWhere($relationAlias . '.type = :type')->setParameter('type', $this->type);
        if ($tag) {
            $qb->andWhere($relationAlias . '.tag = :tag')->setParameter('tag', $tag);
        }
    }

    /**
     * @param BaseEntity[] $result
     * @param DataSpace $from
     * @param DataSpace $to
     */
    public function postProcess(&$result, $from, $to)
    {
        
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
