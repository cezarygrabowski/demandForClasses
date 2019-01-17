<?php

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleRepository extends ServiceEntityRepository
{
    public $em;
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Schedule::class);
        $this->em = $entityManager;
    }
}
