<?php


namespace Users\Application\Handler;


use DateTime;
use Demands\Domain\Repository\SubjectRepository;
use Demands\Domain\Subject;
use Exception;
use Users\Application\Command\ImportUsers;
use Users\Domain\Calendar;
use Users\Domain\Import\ImportUser;
use Users\Domain\Month;
use Users\Domain\Qualification;
use Users\Domain\Repository\UserRepository;
use Users\Domain\Role;
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

    public function __construct(
        SubjectRepository $subjectRepository,
        UserRepository $userRepository
    ) {
        $this->subjectRepository = $subjectRepository;
        $this->userRepository = $userRepository;
    }

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
        foreach ($importUsers as $importUser) {
            $users[] = $this->createUserFromImportUser($importUser, $importedBy);
        }

        return $users;
    }

    private function createUserFromImportUser(ImportUser $importUser, User $importedBy)
    {
        if($this->userRepository->findByUsername($importUser->userName)){
            throw new Exception("Użytkownik z nazwą " . $importUser->userName . " już istnieje");
        }

        $role = new Role();
        $role->setName($importUser->roleName);
        $user = new User($importUser->userName);
        $user->addRole($role);

        foreach ($importUser->qualifications as $qualification) {
            $subject = $this->subjectRepository->findByName($qualification->name);
            if(!$subject) {
                $subject = new Subject($qualification->name, $qualification->shortName);
            }

            $qualification = new Qualification($subject);
            $user->addQualification($qualification);
        }


        $months = [];
        $calendar = new Calendar($importUser->semester);
        foreach ($importUser->workingHours as $monthNumber => $workingHours) {
            $calendar->addMonth(new Month($monthNumber, $workingHours));
        }

        $user->setCalendar($calendar);
        $user->setImportedBy($importedBy);
        $user->setImportedAt(new DateTime());

        return $user;
    }
}