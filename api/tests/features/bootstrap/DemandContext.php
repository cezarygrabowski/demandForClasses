<?php


use App\Domain\demands\Demand;
use App\Domain\demands\LectureSet;
use App\Domain\demands\Repository\DemandRepository;
use App\Domain\demands\Subject;
use App\Domain\users\Repository\UserRepository;
use App\Domain\users\User;
use App\Infrastructure\demands\InMemory\Repository\InMemoryDemandRepository;
use App\Infrastructure\users\InMemory\Repository\InMemoryUserRepository;
use Behat\Behat\Context\Context;
class DemandContext implements Context
{

    /**
     * @var DemandRepository
     */
    private $demandRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /** @var Demand */
    private $demand;

    /**
     * @Given There is demand with subject :name :shortenedName and lecture type :lectureType
     */
    public function thereIsADemandWithSubjectAndLectureType($name, $shortenedName, $lectureType)
    {
        $subject = new Subject($name, $shortenedName);
        $lectureSet = new LectureSet(LectureSet::LECTURE_TYPES_FOR_IMPORT[$lectureType], $subject);
        $this->demand = new Demand();
        $this->demand->addLectureSet($lectureSet);
    }

    /**
     * @Given There is lecturer :username
     */
    public function thereIsLecturer($username)
    {
        $user = new User($username);

        $this->userRepository = new InMemoryUserRepository([$user]);
    }

    /**
     * @When I change demand lecturer to :username in :lectureType lecture type
     */
    public function iChangeDemandLecturerToInLectureType($username, $lectureType)
    {
        $this->demand->changeLecturer(
            $this->userRepository->findByUsername($username),
            LectureSet::LECTURE_TYPES_FOR_IMPORT[$lectureType]
        );
    }

    /**
     * @Then user :username should see this demand on his list
     */
    public function userShouldSeeThisDemand($username)
    {
        $user = $this->userRepository->findByUsername($username);
        $this->demandRepository = new InMemoryDemandRepository([$this->demand]);
        $demands = $this->demandRepository->listAllForUser($user);

        \PHPUnit\Framework\Assert::assertSame($demands[0], $this->demand);
    }
}
