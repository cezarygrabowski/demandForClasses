<?php


namespace Demands\Infrastructure\Doctrine\Repository;


use Demands\Domain\Place;
use Demands\Domain\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePlaceRepository implements PlaceRepository
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

    public function findOneByBuildingAndRoom(int $building, int $room): ?Place
    {
        return $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Place::class, 'p')
            ->where('p.building = :building')
            ->where('p.room = :room')
            ->setParameter('room', $building)
            ->setParameter('building', $room)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @return Place[]
     */
    public function findAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Place::class, 'p')
            ->getQuery()
            ->getResult();
    }
}
