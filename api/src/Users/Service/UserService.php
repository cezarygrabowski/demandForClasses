<?php


namespace Users\Application\Service;


use Demands\Domain\Repository\DemandRepository;
use Demands\Domain\Week;
use Users\Domain\User;

class UserService
{
    /**
     * @var DemandRepository
     */
    private $demandRepository;

    /**
     * UserService constructor.
     * @param DemandRepository $demandRepository
     */
    public function __construct(DemandRepository $demandRepository)
    {
        $this->demandRepository = $demandRepository;
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
}