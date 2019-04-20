<?php


namespace Users\Application\Service;


use Demands\Domain\Repository\DemandRepository;
use Demands\Domain\Week;
use Users\Domain\Repository\UserRepository;
use Users\Domain\User;

class UserService
{
    /**
     * @var DemandRepository
     */
    private $demandRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserService constructor.
     * @param DemandRepository $demandRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        DemandRepository $demandRepository,
        UserRepository $userRepository)
    {
        $this->demandRepository = $demandRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllocatedHoursInMonth(int $monthNumber) {

    }

    /**
     * @param User $user
     * @param int $weekNumber
     * @return int
     */
    public function getAllocatedHoursInWeek(User $user, int $weekNumber) {
        $demands = $this->demandRepository->listAllForUser($user);

        $hours = 0;
        foreach ($demands as $demand) {
            foreach ($demand->getLectureSets() as $lectureSet) {
                $hours = $lectureSet->getAllocatedHoursInWeek($weekNumber);
            }
        }

        return $hours;
    }

    public function getAllocatedHoursInAllWeeks(User $user): array
    {
        $weeksWithAllocatedHours = [];
        foreach (Week::WEEKS_IN_SEMESTER as $weekNumber) {
            $weeksWithAllocatedHours[$weekNumber] = $this->getAllocatedHoursInWeek($user, $weekNumber);
        }

        return $weeksWithAllocatedHours;
    }

    /**
     * @return \Users\Domain\Query\User[]
     */
    public function getAllLecturers(): array
    {
        $lecturers = $this->userRepository->findAllLecturers();

        return \Users\Domain\Query\User::fromUsersCollection($lecturers);
    }
}