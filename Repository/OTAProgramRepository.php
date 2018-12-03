<?php

namespace UEGMobile\ArduinoOTAServerBundle\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class OTAProgramRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllPaginated(
        array $sort = [],
        $limit = 500,
        $page = 1,
        ?string $programNameFilter = null,
        ?string $globalFilter = null
    ){

        $queryBuilder = $this->createQueryBuilder($alias = 'program');
        $queryBuilder = $queryBuilder->setMaxResults($limit);
        $queryBuilder = $queryBuilder->setFirstResult(($page-1)*$limit);

        if (!empty($programNameFilter)){
            $queryBuilder = $queryBuilder->andWhere('(program.name like :programNameFilter)');
            $queryBuilder = $queryBuilder->setParameter('programNameFilter', '%'.$programNameFilter.'%');
        }

        if (!empty($globalFilter)){
            $queryBuilder = $queryBuilder->andWhere('program.name like :programNameFilter');
            $queryBuilder = $queryBuilder->setParameter('programNameFilter', '%'.$programNameFilter.'%');
        }

        foreach ($sort as $property => $order) {
            if (!empty($order) ) {
                if(strcmp($property, 'name') == 0) {
                    $queryBuilder->addOrderBy('program.name', $order);
                } elseif (strcmp($property, 'created_At') == 0) {
                    $queryBuilder->addOrderBy('program.created_At', $order);
                }
            }
        }

        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }

    public function emptyListPaginated()
    {
        return new Pagerfanta(new ArrayAdapter([]));
    }
}
