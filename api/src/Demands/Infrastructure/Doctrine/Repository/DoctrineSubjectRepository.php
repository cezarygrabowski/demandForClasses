<?php


namespace Demands\Infrastructure\Doctrine\Repository;


use Demands\Domain\Repository\SubjectRepository;
use Demands\Domain\Subject;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSubjectRepository implements SubjectRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DoctrineUserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByName(string $name): ?Subject
    {
        return $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from(Subject::class, 's')
            ->where('s.name LIKE :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
