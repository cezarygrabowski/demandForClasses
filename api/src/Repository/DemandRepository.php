<?php

namespace App\Repository;

use App\Entity\Demand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demand::class);
    }

    public function findDemandByImportData(array $data): ?Demand
    {
        return $this->getEntityManager()->createQueryBuilder('d')
            ->select('d')
            ->from(Demand::class,'d')
            ->leftJoin('d.subject', 's')
            ->andWhere('d.group like :group')
            ->andWhere('s.name like :subjectName')
            ->andWhere('d.yearNumber like :yearNumber')
            ->andWhere('d.groupType like :groupType')
            ->andWhere('d.semester like :semester')
            ->andWhere('d.department like :department')
            ->andWhere('d.institute like :institute')
            ->setParameters([
                'group' => $data[0],
                'subjectName' => $data[1],
                'yearNumber' => $data[6],
                'groupType' => $data[7],
                'semester' => $data[8],
                'department' => $data[9],
                'institute' => $data[10]
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getEntityManager()
    {
        return parent::getEntityManager();
    }
}
