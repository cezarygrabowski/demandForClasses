<?php


namespace Demands\Infrastructure\Doctrine\Repository;


use Demands\Domain\Group;
use Demands\Domain\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineGroupRepository implements GroupRepository
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

    public function find(string $groupName): ?Group
    {
        return $this->entityManager->createQueryBuilder()
            ->select('g')
            ->from(Group::class, 'g')
            ->where('g.name LIKE :groupName')
            ->setParameter('groupName', $groupName)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
