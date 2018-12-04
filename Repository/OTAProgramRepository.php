<?php

namespace UEGMobile\ArduinoOTAServerBundle\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use UEGMobile\ArduinoOTAServerBundle\Entity\OTAProgram;

class OTAProgramRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllPaginated(
        array $sort = [],
        $limit = 20,
        $page = 1,
        ?string $programNameFilter = null
    ){

        $queryBuilder = $this->createQueryBuilder($alias = 'program');
        $queryBuilder = $queryBuilder->setMaxResults($limit);
        $queryBuilder = $queryBuilder->setFirstResult(($page-1)*$limit);

        if (!empty($programNameFilter)){
            $queryBuilder = $queryBuilder->andWhere('(program.name like :programNameFilter)');
            $queryBuilder = $queryBuilder->setParameter('programNameFilter', '%'.$programNameFilter.'%');
        }

        foreach ($sort as $property => $order) {
            if (!empty($order) ) {
                if(strcmp($property, 'name') == 0) {
                    $queryBuilder->addOrderBy('program.name', $order);
                } elseif (strcmp($property, 'updated_at') == 0) {
                    $queryBuilder->addOrderBy('program.updated_at', $order);
                } elseif (strcmp($property, 'created_at') == 0) {
                    $queryBuilder->addOrderBy('program.created_at', $order);
                }
            }
        }
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }


    public function add(OTAProgram $program): void
    {
        $this->_em->persist($program);
    }
}
