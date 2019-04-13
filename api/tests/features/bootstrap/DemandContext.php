<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Demands\Application\Command\AcceptDemand;
use Demands\Application\Command\ExportDemands;
use Demands\Application\Handler\AcceptDemandHandler;
use Demands\Application\FileManagers\CsvFileMaker;
use Demands\Application\Handler\ExportDemandsHandler;
use Demands\Application\Service\DemandService;
use Demands\Domain\Demand;
use Demands\Domain\Export\Csv\Positions;
use Demands\Domain\Group;
use Demands\Domain\LectureSet;
use Demands\Domain\Place;
use Demands\Domain\Repository\DemandRepository;
use Demands\Domain\Repository\GroupRepository;
use Demands\Domain\Repository\PlaceRepository;
use Demands\Domain\StatusResolver;
use Demands\Domain\Subject;
use Demands\Domain\Week;
use Demands\Infrastructure\InMemory\Repository\InMemoryDemandRepository;
use Demands\Infrastructure\InMemory\Repository\InMemoryGroupRepository;
use Demands\Infrastructure\InMemory\Repository\InMemoryPlaceRepository;
use Users\Domain\Repository\UserRepository;
use Users\Domain\Role;
use Users\Domain\User;
use Users\Infrastructure\InMemory\Repository\InMemoryUserRepository;
use Webmozart\Assert\Assert;

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
     * @var string
     */
    private $content;

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
        $this->lectureSet = new LectureSet(LectureSet::LECTURE_TYPES_STRING_TO_INT[$lectureType]);
        $this->demand->addLectureSet($this->lectureSet);
    }

    /**
     * @Given There is user :username
     */
    public function thereIsUser($username)
    {
        $user = new User($username);

        if(!$this->userRepository) {
            $this->userRepository = new InMemoryUserRepository([$user]);
        } else {
            $this->userRepository->addUser($user);
        }

    }

    /**
     * @When I change demand lecturer to :username in :lectureType lecture set
     */
    public function iChangeDemandLecturerToInLectureType($username, $lectureType)
    {
        $this->demand->changeLecturer(
            $this->userRepository->findByUsername($username),
            LectureSet::LECTURE_TYPES_STRING_TO_INT[$lectureType]
        );
    }

    /**
     * @Then user :username should see this demand
     */
    public function userShouldSeeThisDemand($username)
    {
        $user = $this->userRepository->findByUsername($username);
        $this->demandRepository = new InMemoryDemandRepository([$this->demand]);
        $demandService = new DemandService($this->demandRepository, new StatusResolver());
        $demands = $demandService->listAllForUser($user);

        Assert::same($demands[0], $this->demand);
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
     * @throws Exception
     */
    public function iBookHoursInWeek($hours, $weekNumber)
    {
        $week = new Week($weekNumber, $hours);
        $this->lectureSet->allocateWeek($week);
    }

    /**
     * @Then in :weekNumber week should be :hours hours allocated
     * @throws Exception
     */
    public function inWeekShouldBeHoursAllocated(int $weekNumber, int $hours)
    {
        $week = $this->lectureSet->getAllocatedWeek($weekNumber);
        Assert::same($week->getAllocatedHours(), $hours);
    }

    /**
     * @Then lecture set :lectureType should have :hours undistributed hours
     */
    public function lectureTypeShouldHaveUndistributedHours($lectureType, int $hours)
    {
        Assert::same($this->lectureSet->getUndistributedHours(), $hours);
    }

    /**
     * @Given There is a place with building :building and room :room
     */
    public function thereIsAPlaceWithBuildingAndRoom(int $building, int $room)
    {
        $place = new Place($building, $room);
        if(!$this->placeRepository) {
            $this->placeRepository = new InMemoryPlaceRepository([$place]);
        } else {
            $this->placeRepository->addPlace($place);
        }
    }

    /**
     * @When I choose building :building and room :room in :weekNumber week
     * @throws Exception
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
     * @throws Exception
     */
    public function demandShouldHaveBuildingAndRoomInWeek(int $building, int $room, int $weekNumber)
    {
        $week = $this->lectureSet->getAllocatedWeek($weekNumber);
        if($week->getPlace()) {
            Assert::same($week->getPlace()->getRoom(), $room);
            Assert::same($week->getPlace()->getBuilding(), $building);
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
        Assert::same($this->lectureSet->getNotes(), $notes);
    }

    /**
     * 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15 (numer tygodnia)
     * @Given has following informations:
     */
    public function hasFollowingInformations(TableNode $table)
    {
        $row = $table->getRow(0);

        $lecturer = $this->userRepository->findByUsername($row[Positions::LECTURER]);
        $group = $this->groupRepository->find($row[Positions::GROUP]);
        $place1 = $this->placeRepository->findOneByBuildingAndRoom($row[11], $row[12]);
        $week1 = new Week(1, $row[10], $place1);
        $place2 = $this->placeRepository->findOneByBuildingAndRoom($row[14], $row[15]);
        $week2 = new Week(2, $row[13], $place2);

        $this->lectureSet = new LectureSet(LectureSet::LECTURE_TYPES_STRING_TO_INT[
            $row[Positions::LECTURE_TYPE]
        ]);
        $this->lectureSet
            ->setLecturer($lecturer)
            ->setHoursToDistribute($row[9])
            ->setDemand($this->demand)
            ->allocateWeek($week1)
            ->allocateWeek($week2);

        $this->demand
            ->addLectureSet($this->lectureSet)
            ->setStatus(Demand::STATUS_ACCEPTED_BY_DEAN)
            ->setSchoolYear($row[Positions::SCHOOL_YEAR])
            ->setGroup($group)
            ->setSemester($row[6])
            ->setInstitute($row[7])
            ->setDepartment($row[8]);

        $this->demandRepository = new InMemoryDemandRepository([$this->demand]);
    }

    /**
     * @Then Exported demands should have status :status
     */
    public function exportedDemandsShouldHaveStatus($status)
    {
        Assert::same($this->demand->getStatus(), Demand::STATUSES_STRING_TO_INT[$status]);
    }

    /**
     * @Given There is group :name with type :type
     */
    public function thereIsGroupWithType(string $name, string $type)
    {
        $group = new Group($name, Group::GROUP_TYPE_STRING_TO_INT[$type]);
        $this->groupRepository = new InMemoryGroupRepository([$group]);
    }

    /**
     * @When user :userName export accepted demands
     */
    public function userExportAcceptedDemands(string $userName)
    {
        $command = new ExportDemands(
            [$this->demand->getUuid()],
            $this->userRepository->findByUsername($userName)
        );
        $handler = new ExportDemandsHandler(new CsvFileMaker(), $this->demandRepository);

        $this->content = $handler->handle($command);
    }

    /**
     * @Then created file should have following informations:
     */
    public function rowOfCreatedFileShouldHaveFollowingInformations(TableNode $table)
    {
        $row = $table->getRow(0);
        foreach ($row as $key => $value) {
            Assert::true(strpos($this->content, $value) !== false);
        }
    }

    /**
     * @Given user :userName has role :roleName
     */
    public function userHasRole(string $userName, string $roleName)
    {
        $user = $this->userRepository->findByUsername($userName);
        $role = new Role();
        $role
            ->setUser($user)
            ->setName(Role::ROLES_STRING_TO_INT[$roleName]);
        $user->addRole($role);
    }

    /**
     * @When user :userName accept a demand
     */
    public function userAcceptADemand(string $userName)
    {
        $user = $this->userRepository->findByUsername($userName);

        $command = new AcceptDemand($this->demand, $user);
        $handler = new AcceptDemandHandler(new StatusResolver());

        $handler->handle($command);
    }  
}
