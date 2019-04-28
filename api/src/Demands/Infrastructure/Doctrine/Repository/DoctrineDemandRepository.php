<?php


namespace Demands\Infrastructure\Doctrine\Repository;


use Demands\Domain\Demand;
use Demands\Domain\Repository\DemandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Users\Domain\User;

class DoctrineDemandRepository implements DemandRepository
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

    /**
     * @param User $user
     * @return Demand[]
     */
    public function listAllForUser(User $user): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('d')
            ->from(Demand::class, 'd')
            ->leftJoin('d.lectureSets', 'lS')
            ->where('lS.lecturer = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $uuids
     * @return Demand[]
     */
    public function findByIdentifiers(array $uuids): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('d')
            ->from(Demand::class, 'd')
            ->where('d.uuid IN (:uuids)')
            ->setParameter('uuids', $uuids)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $statuses
     * @return Demand[]
     */
    public function listAllWithStatuses(array $statuses): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('d')
            ->from(Demand::class, 'd')
            ->leftJoin('d.lectureSets', 'lS')
            ->where('d.status IN (:statuses)')
            ->setParameter('statuses', $statuses)
            ->getQuery()
            ->getResult();
    }

    public function findOneByUuid(string $demandUuid): ?Demand
    {
        return $this->entityManager->createQueryBuilder()
            ->select('d')
            ->from(Demand::class, 'd')
            ->where('d.uuid LIKE :uuid')
            ->setParameter('uuid', $demandUuid)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
