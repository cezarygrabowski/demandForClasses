<?php


namespace Demands\Application\Command;


use Demands\Domain\Import\StudyPlan\StudyPlan;
use Users\Domain\User;

class ImportStudyPlans
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var StudyPlan[]
     */
    private $studyPlans;

    /**
     * ImportStudyPlans constructor.
     * @param User $user
     * @param StudyPlan[] $studyPlans
     */
    public function __construct(User $user, array $studyPlans)
    {
        $this->user = $user;
        $this->studyPlans = $studyPlans;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return StudyPlan[]
     */
    public function getStudyPlans(): array
    {
        return $this->studyPlans;
    }
}
