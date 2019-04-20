<?php


namespace Users\Infrastructure\Doctrine\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Users\Domain\Repository\UserRepository;
use Users\Domain\User;

class DoctrineUserRepository implements UserRepository
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

    public function findByUsername(string $username): ?User
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.username LIKE :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @return User[]
     */
    public function findAllLecturers(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.roles like :teacherRole')
            ->setParameter('teacherRole', '%'.User::ROLE_LECTURER.'%')
            ->getQuery()
            ->getResult();
    }

    public function findByUuid(string $assignorUuid): ?User
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.uuid LIKE :uuid')
            ->setParameter('uuid', $assignorUuid)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
