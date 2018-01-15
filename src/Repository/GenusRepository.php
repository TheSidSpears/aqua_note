<?php

namespace App\Repository;


use App\Entity\Genus;
use Doctrine\ORM\EntityRepository;

class GenusRepository extends EntityRepository
{

    /**
     * @return Genus[]
     */
    public function findAllPublishedOrderedByRecentlyActive()
    {
        return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->leftJoin('genus.notes', 'genus_note')
            ->orderBy('genus_note.createdAt', 'DESC')
            //->leftJoin('genus.genusScientists', 'genusScientist')
            //->addSelect('genusScientist')
            ->getQuery()
            ->execute();
    }

    public function getGenusCount()
    {
        return $this->createQueryBuilder('genus')
            ->select('count(genus.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPublishedGenusCount()
    {
        return $this->createQueryBuilder('genus')
            ->select('count(genus.id)')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findRandomGenus()
    {
        $idList = $this->findIds();

        return $this->createQueryBuilder('genus')
            ->andWhere('genus.id = :id')
            ->setParameter('id', $idList[array_rand($idList)]['id'])
            ->getQuery()
            ->getSingleResult();
    }

    private function findIds()
    {
        return $this->createQueryBuilder('genus')
            ->select('genus.id')
            ->getQuery()
            ->getResult();
    }
}
