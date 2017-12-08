<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class SubFamilyRepository extends EntityRepository
{
    public function createAlphabeticalQueryBuilder()
    {
        return $this->createQueryBuilder('sub_family')
            ->orderBy('sub_family.name', 'ASC');
    }

    public function findAny()
    {
        $idList = $this->findIds();

        return $this->createQueryBuilder('sub_family')
            ->andWhere('sub_family.id = :id')
            ->setParameter('id', $idList[array_rand($idList)]['id'])
            ->getQuery()
            ->getSingleResult();
    }

    private function findIds()
    {
        return $this->createQueryBuilder('sub_family')
            ->select('sub_family.id')
            ->getQuery()
            ->getResult();
    }
}
