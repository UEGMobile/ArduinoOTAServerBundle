<?php

namespace UEGMobile\ArduinoOTAServerBundle\Repository;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class OTABinaryRepository extends \Doctrine\ORM\EntityRepository
{


    public function findAllPaginated(
        array $sort = [],
        $limit = 20,
        $page = 1,
        ?string $binaryName = null,
        ?string $binaryVersion = null,
        ?string $userAgent = null,
        ?string $sdkVersion = null
    ){

        $queryBuilder = $this->createQueryBuilder($alias = 'binary');
        $queryBuilder = $queryBuilder->setMaxResults($limit);
        $queryBuilder = $queryBuilder->setFirstResult(($page-1)*$limit);

        if (!empty($binaryName)){
            $queryBuilder = $queryBuilder->andWhere('(binary.binaryName like :binaryName)');
            $queryBuilder = $queryBuilder->setParameter('binaryName', '%'.$binaryName.'%');
        }
        if (!empty($binaryVersion)){
            $queryBuilder = $queryBuilder->andWhere('(binary.binaryVersion like :binaryVersion)');
            $queryBuilder = $queryBuilder->setParameter('binaryVersion', '%'.$binaryVersion.'%');
        }
        if (!empty($userAgent)){
            $queryBuilder = $queryBuilder->andWhere('(binary.userAgent like :userAgent)');
            $queryBuilder = $queryBuilder->setParameter('userAgent', '%'.$userAgent.'%');
        }
        if (!empty($binaryName)){
            $queryBuilder = $queryBuilder->andWhere('(binary.sdkVersion like :sdkVersion)');
            $queryBuilder = $queryBuilder->setParameter('sdkVersion', '%'.$sdkVersion.'%');
        }

        foreach ($sort as $property => $order) {
            if (!empty($order) ) {
                if(strcmp($property, 'name') == 0) {
                    $queryBuilder->addOrderBy('binary.binaryName', $order);
                } elseif (strcmp($property, 'version') == 0) {
                    $queryBuilder->addOrderBy('binary.version', $order);
                } elseif (strcmp($property, 'updated_at') == 0) {
                    $queryBuilder->addOrderBy('binary.updated_at', $order);
                } elseif (strcmp($property, 'created_at') == 0) {
                    $queryBuilder->addOrderBy('binary.created_at', $order);
                }
            }
        }
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }
    
    public function add(OTABinary $binary): void
    {
        $this->_em->persist($binary);
    }
}
