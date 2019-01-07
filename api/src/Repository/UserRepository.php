<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getByQualification(Subject $subject) {
        $qb = $this->createQueryBuilder('e')
            ->select('u')
            ->from(User::class, 'u')
            ->where(':qualification MEMBER OF e.qualifications')
            ->setParameter(':qualification', $subject)
            ->getQuery()
            ->getResult();

        return $qb;
    }
}