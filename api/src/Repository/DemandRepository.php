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
//            ->leftJoin('d.subject', 's')
//            ->where('d.group = :group')
//            ->andWhere('s.name = :subjectName')
//            ->andWhere('d.yearNumber = :yearNumber')
//            ->andWhere('d.groupType = :groupType')
//            ->andWhere('d.semester = :semester')
//            ->andWhere('d.department = :department')
//            ->andWhere('d.institute = :institute')
//            ->setParameters([
//                'group' => $data[0],
//                'subjectName' => $data[1],
//                'yearNumber' => $data[6],
//                'groupType' => $data[7],
//                'semester' => $data[8],
//                'department' => $data[9],
//                'institute' => $data[10]
//            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getEntityManager()
    {
        return parent::getEntityManager();
    }
}
