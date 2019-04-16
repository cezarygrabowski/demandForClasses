<?php

namespace App\Repository;

use App\Entity\TEst;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TEst|null find($id, $lockMode = null, $lockVersion = null)
 * @method TEst|null findOneBy(array $criteria, array $orderBy = null)
 * @method TEst[]    findAll()
 * @method TEst[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TEstRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TEst::class);
    }

    // /**
    //  * @return TEst[] Returns an array of TEst objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TEst
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
