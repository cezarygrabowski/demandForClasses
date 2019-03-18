<?php

namespace App\Repository;

use App\DTO\ScheduleRow;
use App\Entity\Demand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demand::class);
    }

    public function findDemandByScheduleData(ScheduleRow $scheduleRow): ?Demand
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
                'group' => $scheduleRow->group,
                'subjectName' => $scheduleRow->subject,
                'yearNumber' => $scheduleRow->yearNumber,
                'groupType' => $scheduleRow->groupType,
                'semester' => $scheduleRow->semester,
                'department' => $scheduleRow->department,
                'institute' => $scheduleRow->institute
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getEntityManager()
    {
        return parent::getEntityManager();
    }

    public function findAllForNauczyciel(\App\Entity\User $user)
    {
        return $this->getEntityManager()->createQueryBuilder('d')
            ->select('d')
            ->from(Demand::class,'d')
            ->leftJoin('d.lectures', 'l')
            ->leftJoin('l.lecturer', 'user')
            ->andWhere('user.id = :user')
            ->setParameters(['user' => $user->getId()])
            ->getQuery()
            ->getResult();
    }

    public function findAllForKierownikZakladu()
    {
        return $this->getEntityManager()->createQueryBuilder('d')
            ->select('d')
            ->from(Demand::class,'d')
            ->leftJoin('d.lectures', 'l')
            ->leftJoin('l.lecturer', 'user')
            ->orWhere('user is null')
            ->orWhere('d.status = :acceptedByNauczyciel')
            ->setParameter('acceptedByNauczyciel', Demand::STATUS_ACCEPTED_BY_TEACHER)
            ->getQuery()
            ->getResult();
    }

    public function findAllForDyrektorInstytutu()
    {
        return $this->getEntityManager()->createQueryBuilder('d')
            ->select('d')
            ->from(Demand::class,'d')
            ->orWhere('d.status = :acceptedByKierownikZakladu')
            ->setParameter('acceptedByKierownikZakladu', Demand::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER)
            ->getQuery()
            ->getResult();
    }
}
