<?php

namespace App\Repository;

use App\Entity\Lecture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class LectureRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lecture::class);
        $em = $this->_em;
    }

    public function getEm() {
        return $this->em;
    }
}
