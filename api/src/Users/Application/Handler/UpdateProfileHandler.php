<?php


namespace Users\Application\Handler;


use Doctrine\ORM\EntityManagerInterface;
use Users\Application\Command\UpdateProfile;

class UpdateProfileHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UpdateProfileHandler constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(UpdateProfile $command)
    {
        $user = $command->getUser();
        $user->setEmail($command->getData()['email']);
        if(isset($command->getData()['automaticallySendDemands'])) {
            $user->setAutomaticallySendDemands($command->getData()['automaticallySendDemands']);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}