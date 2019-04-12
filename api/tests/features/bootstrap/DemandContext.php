<?php

use App\Domain\demands\DTO\DemandExportDTO;
use App\Domain\demands\Group;
use App\Domain\demands\Repository\GroupRepository;
use App\Infrastructure\demands\InMemory\Repository\InMemoryGroupRepository;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use App\Domain\demands\Place;
use App\Domain\demands\Repository\PlaceRepository;
use App\Infrastructure\demands\InMemory\Repository\InMemoryPlaceRepository;
use App\Domain\demands\Week;
use App\Domain\demands\Demand;
use App\Domain\demands\LectureSet;
use App\Domain\demands\Repository\DemandRepository;
use App\Domain\demands\Subject;
use App\Domain\users\Repository\UserRepository;
use App\Domain\users\User;
use App\Infrastructure\demands\InMemory\Repository\InMemoryDemandRepository;
use App\Infrastructure\users\InMemory\Repository\InMemoryUserRepository;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

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

    /**
     * @var Demand
     */
    private $demand;

    /**
     * @var LectureSet
     */
    private $lectureSet;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @Given There is demand with subject :name :shortenedName
     */
    public function thereIsADemandWithSubject($name, $shortenedName)
    {
        $subject = new Subject($name, $shortenedName);
        $this->demand = new Demand($subject);
    }

    /**
     * @Given lecture set :lectureType
     */
    public function lectureSet($lectureType)
    {
        $this->lectureSet = new LectureSet(LectureSet::LECTURE_TYPES_FOR_IMPORT[$lectureType]);
        $this->demand->addLectureSet($this->lectureSet);
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
     * @When I change demand lecturer to :username in :lectureType lecture set
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

        Assert::assertSame($demands[0], $this->demand);
    }

    /**
     * @Given Demand lecture set :lectureType has :hours undistributed hours
     */
    public function demandLectureTypeHasHoursUndistributed($lectureType, $hours)
    {
        $this->lectureSet->setHoursToDistribute($hours);
    }

    /**
     * @When I book :hours hours in :weekNumber week
     */
    public function iBookHoursInWeek($hours, $weekNumber)
    {
        $week = new Week($weekNumber, $hours);
        $this->lectureSet->allocateWeek($week);
    }

    /**
     * @Then in :weekNumber week should be :hours hours allocated
     */
    public function inWeekShouldBeHoursAllocated(int $weekNumber, int $hours)
    {
        $week = $this->lectureSet->getAllocatedWeek($weekNumber);
        Assert::assertSame($week->getAllocatedHours(), $hours);
    }

    /**
     * @Then lecture set :lectureType should have :hours undistributed hours
     */
    public function lectureTypeShouldHaveUndistributedHours($lectureType, int $hours)
    {
        Assert::assertSame($this->lectureSet->getUndistributedHours(), $hours);
    }

    /**
     * @Given There is a place with building :building and room :room
     */
    public function thereIsAPlaceWithBuildingAndRoom($building, $room)
    {
        $place = new Place($building, $room);
        $this->placeRepository = new InMemoryPlaceRepository([$place]);
    }

    /**
     * @When I choose building :building and room :room in :weekNumber week
     */
    public function iChooseBuildingAndRoomInWeek(int $building, int $room, int $weekNumber)
    {
        $week = $this->lectureSet->getAllocatedWeek($weekNumber);
        $place = $this->placeRepository->findOneByBuildingAndRoom($building, $room);
        if ($place) {
            $week->setPlace($place);
        }
    }

    /**
     * @Then Demand should have building :building and room :room in :weekNumber week
     */
    public function demandShouldHaveBuildingAndRoomInWeek(int $building, int $room, int $weekNumber)
    {
        $week = $this->lectureSet->getAllocatedWeek($weekNumber);
        if($week->getPlace()) {
            Assert::assertSame($week->getPlace()->getRoom(), $room);
            Assert::assertSame($week->getPlace()->getBuilding(), $building);
        }
    }

    /**
     * @When I add notes :notes
     */
    public function iAddNotes($notes)
    {
        $this->lectureSet->addNotes($notes);
    }

    /**
     * @Then Lecture set should have :notes notes
     */
    public function lectureSetShouldHaveNotes($notes)
    {
        Assert::assertSame($this->lectureSet->getNotes(), $notes);
    }

    /**
     * 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15 (numer tygodnia)
     * @Given has following informations:
     */
    public function hasFollowingInformations(TableNode $table)
    {
        $row = $table->getRow(0);

        $lecturer = $this->userRepository->findByUsername($row[DemandExportDTO::LECTURER]);
        $group = $this->groupRepository->find($row[DemandExportDTO::GROUP]);

        $this->lectureSet = new LectureSet($row[DemandExportDTO::LECTURE_TYPE]);
        $this->lectureSet
            ->setLecturer($lecturer)
            ->setHoursToDistribute($row[DemandExportDTO::HOURS]);

        $this->demand
            ->addLectureSet($this->lectureSet)
            ->setStatus(Demand::STATUS_ACCEPTED_BY_DEAN)
            ->setSchoolYear($row[DemandExportDTO::SCHOOL_YEAR])
            ->setGroup($group)
            ->setInstitute($row[DemandExportDTO::INSTITUTE])
            ->setDepartment($row[DemandExportDTO::DEPARTMENT])
            ->setSemester($row[DemandExportDTO::SEMESTER]);
        //TODO HERE
    }

    /**
     * @Given I have role :arg1
     */
    public function iHaveRole($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I export accepted demands
     */
    public function iExportAcceptedDemands()
    {
        throw new PendingException();
    }

    /**
     * @Then Exported demands should have status :arg1
     */
    public function exportedDemandsShouldHaveStatus($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then :arg1 row of created file should look like that:
     */
    public function rowOfCreatedFileShouldLookLikeThat($arg1, TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given There is group :name with type :type
     */
    public function thereIsGroupWithType($name, $type)
    {
        $group = new Group($name, $type);
        $this->groupRepository = new InMemoryGroupRepository([$group]);
    }
}
