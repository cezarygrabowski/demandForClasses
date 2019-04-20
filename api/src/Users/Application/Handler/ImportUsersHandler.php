<?php


namespace Users\Application\Handler;


use DateTime;
use Demands\Domain\Repository\SubjectRepository;
use Demands\Domain\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Users\Application\Command\ImportUsers;
use Users\Domain\Calendar;
use Users\Domain\Import\ImportUser;
use Users\Domain\Month;
use Users\Domain\Qualification;
use Users\Domain\Repository\UserRepository;
use Users\Domain\User;

class ImportUsersHandler
{
    /**
     * @var SubjectRepository
     */
    private $subjectRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        SubjectRepository $subjectRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->subjectRepository = $subjectRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ImportUsers $command
     * @return array|User[]
     * @throws Exception
     */
    public function handle(ImportUsers $command)
    {
        return $this->createUsersFromImportUsers($command->getImportUsers(), $command->getImportedBy());
    }

    /**
     * @param ImportUser[] $importUsers
     * @param User $importedBy
     * @return User[]
     * @throws Exception
     */
    private function createUsersFromImportUsers(array $importUsers, User $importedBy): array
    {
        $users = [];
        $subjects = [];
        foreach ($importUsers as $importUser) {
            $user = $this->createUserFromImportUser($importUser, $importedBy, $subjects);
            if($user){
                $users[] = $user;
                $this->entityManager->persist($user);
            }
        }

        return $users;
    }

    private function createUserFromImportUser(ImportUser $importUser, User $importedBy, &$subjects): ?User
    {
        if($this->userRepository->findByUsername($importUser->userName)){
            return null;
        }

        $user = new User($importUser->userName);
        $user->setRoles([$importUser->roleName]);

        foreach ($importUser->qualifications as $qualification) {
            $subject = $this->subjectRepository->findByName($qualification->name);

            if(array_key_exists($qualification->name, $subjects)) {
                $subject = $subjects[$qualification->name];
            } elseif (!$subject) {
                $subject = new Subject($qualification->name, $qualification->shortName);
                $subjects[$qualification->name] = $subject;
            }

            $qualification = new Qualification($subject);
            $qualification->setUser($user);
            $user->addQualification($qualification);
        }

        $calendar = new Calendar($importUser->semester);
        foreach ($importUser->workingHours as $monthNumber => $workingHours) {
            $calendar->addMonth(new Month($monthNumber, $workingHours, $calendar));
        }

        $user->setCalendar($calendar);
        $user->setImportedBy($importedBy);
        $user->setImportedAt(new DateTime());
        $user->setPassword($this->passwordEncoder->encodePassword($user, User::DEFAULT_PASSWORD));

        return $user;
    }
}
