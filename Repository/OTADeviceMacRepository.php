<?php

namespace UEGMobile\ArduinoOTAServerBundle\Repository;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTADeviceMac;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class OTADeviceMacRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllPaginated(
        array $sort = [],
        $limit = 20,
        $page = 1,
        ?string $MACFilter = null
    ){

        $queryBuilder = $this->createQueryBuilder($alias = 'device');
        $queryBuilder = $queryBuilder->setMaxResults($limit);
        $queryBuilder = $queryBuilder->setFirstResult(($page-1)*$limit);

        if (!empty($MACFilter)){
            $queryBuilder = $queryBuilder->andWhere('(device.mac like :MACFilter)');
            $queryBuilder = $queryBuilder->setParameter('MACFilter', '%'.$MACFilter.'%');
        }

        foreach ($sort as $property => $order) {
            if (!empty($order) ) {
                if(strcmp($property, 'program') == 0) {
                    $queryBuilder->addOrderBy('device.name', $order);
                } elseif (strcmp($property, 'mac') == 0) {
                    $queryBuilder->addOrderBy('device.mac', $order);
                } elseif (strcmp($property, 'updatedAt') == 0) {
                    $queryBuilder->addOrderBy('device.updatedAt', $order);
                } elseif (strcmp($property, 'createdAt') == 0) {
                    $queryBuilder->addOrderBy('device.createdAt', $order);
                }
            }
        }
        $queryBuilder->addOrderBy('device.createdAt', 'desc');
        
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }

    public function findByMACAddress(string $MACAddress){

        $queryBuilder = $this->createQueryBuilder($alias = 'device');
        $queryBuilder = $queryBuilder->andWhere('(device.mac = :MACAddress)');
        $queryBuilder = $queryBuilder->setParameter('MACAddress', $MACAddress);
        $queryBuilder = $queryBuilder->andWhere('(device.active = 1)');
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function add(OTADeviceMac $device): void
    {
        $this->_em->persist($device);
    }
}
