<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Demands\Application\Command\AcceptDemand;
use Demands\Application\Command\AssignDemand;
use Demands\Application\Command\DeclineDemand;
use Demands\Application\Command\ExportDemands;
use Demands\Application\Command\ImportStudyPlans;
use Demands\Application\Handler\AcceptDemandHandler;
use Demands\Application\FileManagers\CsvFileMaker;
use Demands\Application\Handler\AssignDemandHandler;
use Demands\Application\Handler\DeclineDemandHandler;
use Demands\Application\Handler\ExportDemandsHandler;
use Demands\Application\Handler\ImportStudyPlansHandler;
use Demands\Application\Service\DemandService;
use Demands\Domain\Demand;
use Demands\Domain\Export\Csv\Positions;
use Demands\Domain\Group;
use Demands\Domain\Import\StudyPlan\StudyPlansExtractor;
use Demands\Domain\LectureSet;
use Demands\Domain\Place;
use Demands\Domain\Printing\PrintDemand;
use Demands\Domain\Repository\DemandRepository;
use Demands\Domain\Repository\GroupRepository;
use Demands\Domain\Repository\PlaceRepository;
use Demands\Domain\Repository\SubjectRepository;
use Demands\Domain\StatusResolver;
use Demands\Domain\Subject;
use Demands\Domain\Week;
use Demands\Infrastructure\InMemory\Repository\InMemoryDemandRepository;
use Demands\Infrastructure\InMemory\Repository\InMemoryGroupRepository;
use Demands\Infrastructure\InMemory\Repository\InMemoryPlaceRepository;
use Demands\Infrastructure\InMemory\Repository\InMemorySubjectRepository;
use Users\Domain\Qualification;
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
     * @var SubjectRepository
     */
    private $subjectRepository;
    /**
     * @var PrintDemand
     */
    private $printedDemand;

    /**
     * @Given There is demand with subject :name :shortenedName
     */
    public function thereIsADemandWithSubject($name, $shortenedName)
    {
        $subject = new Subject($name, $shortenedName);
        $this->subjectRepository = new InMemorySubjectRepository([$subject]);
        $this->demand = new Demand();
        $this->demand->setSubject($subject);

        $this->demandRepository = new InMemoryDemandRepository([$this->demand]);
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
        $demandService = new DemandService($this->demandRepository, new StatusResolver(), $this->subjectRepository, new InMemoryGroupRepository([]));
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

    /**
     * @Then user :userName should not see this demand
     */
    public function userShouldNotSeeThisDemand($userName)
    {
        $user = $this->userRepository->findByUsername($userName);
        $demandService = new DemandService($this->demandRepository, new StatusResolver(), $this->subjectRepository, new InMemoryGroupRepository([]));
        $demands = $demandService->listAllForUser($user);
        Assert::isEmpty($demands);
    }

    /**
     * @When user :userName decline a demand
     */
    public function userDeclineADemand($userName)
    {
        $user = $this->userRepository->findByUsername($userName);
        $command = new DeclineDemand($this->demand, $user);
        $handler = new DeclineDemandHandler();

        $handler->handle($command);
    }

    /**
     * @Then demand should have status :status
     */
    public function demandShouldHaveStatus($status)
    {
        Assert::same($this->demand->getStatus(), Demand::STATUSES_STRING_TO_INT[$status]);
    }

    /**
     * @When user :assignor assign a demand to user :assignee in type :lectureSetType
     */
    public function userAssignADemand($assignor, $assignee, $lectureSetType)
    {
        $assignor = $this->userRepository->findByUsername($assignor);
        $assignee = $this->userRepository->findByUsername($assignee);
        $command = new AssignDemand($this->demand, [LectureSet::LECTURE_TYPES_STRING_TO_INT[$lectureSetType]], $assignor, $assignee);
        $handler = new AssignDemandHandler();

        $handler->handle($command);
    }

    /**
     * @Given demand has status :status
     */
    public function demandHasStatus($status)
    {
        $this->demand->setStatus(Demand::STATUSES_STRING_TO_INT[$status]);
    }

    /**
     * @When user :userName will import :fileName study plans that contains the following:
     */
    public function userWillImportFile($userName, $fileName, TableNode $table)
    {
        $row = $table->getRow(0);
//        var_dump($row);die;
        $fp = fopen($fileName, 'w');
        fputcsv($fp, ['MOCKED HEADER']);
        fputcsv($fp, $row);
        fclose($fp);
        $user = $this->userRepository->findByUsername($userName);
        $demandService = new DemandService(new InMemoryDemandRepository([]), new StatusResolver(), new InMemorySubjectRepository([]), new InMemoryGroupRepository([]));

        $command = new ImportStudyPlans($user, StudyPlansExtractor::extract($fileName));
        $handler = new ImportStudyPlansHandler($demandService);
        $createdDemands = $handler->handle($command);
        $this->demand = $createdDemands[0];
        $this->demandRepository = new InMemoryDemandRepository($createdDemands);
        unlink($fileName);
    }

    /**
     * @Then demand should have :name group
     */
    public function demandShouldHaveGroup($name)
    {
        Assert::same($this->demand->getGroup()->getName(), $name);
    }

    /**
     * @Then demand should have :type group type
     */
    public function demandShouldHaveGroupType($type)
    {
        Assert::same($this->demand->getGroup()->getType(), Group::GROUP_TYPE_STRING_TO_INT[$type]);
    }

    /**
     * @Then demand should have :name :shortName subject
     */
    public function demandShouldHaveSubject($name, $shortName)
    {
        Assert::same($this->demand->getSubject()->getName(), $name);
        Assert::same($this->demand->getSubject()->getShortName(), $shortName);
    }

    /**
     * @Then demand should have :type lecture set type
     */
    public function demandShouldHaveLectureSetType($type)
    {
        Assert::notNull($this->demand->getLectureSet(LectureSet::LECTURE_TYPES_STRING_TO_INT[$type]));
    }

    /**
     * @Then demand should have :institute institute
     */
    public function demandShouldHaveInstitute($institute)
    {
        Assert::same($this->demand->getInstitute(), $institute);
    }

    /**
     * @Then demand should have :department department
     */
    public function demandShouldHaveDepartment($department)
    {
        Assert::same($this->demand->getDepartment(), $department);
    }

    /**
     * @Then demand should have :yearNumber year number
     */
    public function demandShouldHaveYearNumber($yearNumber)
    {
        Assert::same($this->demand->getSchoolYear(), $yearNumber);
    }

    /**
     * @Then lecture set :type should have :hours hours
     */
    public function lectureSetShouldHaveHours(string $type, int $hours)
    {
        Assert::same($this->demand->getLectureSet(LectureSet::LECTURE_TYPES_STRING_TO_INT[$type])->getHoursToDistribute(), $hours);
    }

    /**
     * @When I book :hours hours in :weekNumber week in :lectureSetType lecture set
     */
    public function iBookHoursInWeekInLectureSet(int $hours, int $weekNumber, string $lectureSetType)
    {
        $lectureSet = $this->demand->getLectureSet(LectureSet::LECTURE_TYPES_STRING_TO_INT[$lectureSetType]);
        if (!$lectureSet) {
            throw new Exception("Nie ma takiego zestawu zajęć");
        }
        $week = new Week($weekNumber, $hours);
        $lectureSet->allocateWeek($week);
    }

    /**
     * @When I choose building :building and room :room in :weekNumber week in :lectureSetType lecture set
     */
    public function iChooseBuildingAndRoomInWeekInLectureSet(int $building, int $room, int $weekNumber, string $lectureSetType)
    {
        $lectureSet = $this->demand->getLectureSet(LectureSet::LECTURE_TYPES_STRING_TO_INT[$lectureSetType]);
        if (!$lectureSet) {
            throw new Exception("Nie ma takiego zestawu zajęć");
        }
        $week = $lectureSet->getAllocatedWeek($weekNumber);
        $place = $this->placeRepository->findOneByBuildingAndRoom($building, $room);
        if ($place) {
            $week->setPlace($place);
        }
    }

    /**
     * @Then Demand should have building :building and room :room in :weekNumber week in :lectureSetType lecture set
     */
    public function demandShouldHaveBuildingAndRoomInWeekInLectureSet(int $building, int $room, int $weekNumber, string $lectureSetType)
    {
        $lectureSet = $this->demand->getLectureSet(LectureSet::LECTURE_TYPES_STRING_TO_INT[$lectureSetType]);
        if (!$lectureSet) {
            throw new Exception("Nie ma takiego zestawu zajęć");
        }
        $week = $lectureSet->getAllocatedWeek($weekNumber);
        if($week->getPlace()) {
            Assert::same($week->getPlace()->getRoom(), $room);
            Assert::same($week->getPlace()->getBuilding(), $building);
        }
    }

    /**
     * @Given user :userName has :name :shortName qualification
     */
    public function userHasQualification($userName, $name, $shortName)
    {
        $user = $this->userRepository->findByUsername($userName);
        $user->addQualification(new Qualification(new Subject($name, $shortName)));
    }

    /**
     * @When I print the demand
     */
    public function iPrintTheDemand()
    {
         $this->printedDemand = PrintDemand::create($this->demand);
    }

    /**
     * @When printed demand should have :groupName group name
     */
    public function printedDemandShouldHaveGroupName($groupName)
    {
        Assert::same($groupName, $this->printedDemand->groupName);
    }

    /**
     * @When printed demand should have :groupType group type
     */
    public function printedDemandShouldHaveGroupType($groupType)
    {
        Assert::same($groupType, $this->printedDemand->groupType);
    }

    /**
     * @When printed demand should have :subjectName subject name
     */
    public function printedDemandShouldHaveSubjectName($subjectName)
    {
        Assert::same($subjectName, $this->printedDemand->subjectName);
    }

    /**
     * @When printed demand should have :shortName subject short name
     */
    public function printedDemandShouldHaveSubjectShortName($shortName)
    {
        Assert::same($shortName, $this->printedDemand->subjectShortName);
    }

    /**
     * @When printed demand should have :schoolYear school year
     */
    public function printedDemandShouldHaveSchoolYear($schoolYear)
    {
        Assert::same($schoolYear, $this->printedDemand->yearNumber);
    }

    /**
     * @When printed demand should have :semester semester
     */
    public function printedDemandShouldHaveSemester($semester)
    {
        Assert::same($semester, $this->printedDemand->semester);
    }

    /**
     * @When printed demand should have :department department
     */
    public function printedDemandShouldHaveDepartment($department)
    {
        Assert::same($department, $this->printedDemand->department);
    }

    /**
     * @When printed demand should have :institute institute
     */
    public function printedDemandShouldHaveInstitute($institute)
    {
        Assert::same($institute, $this->printedDemand->institute);
    }

    /**
     * @When lecture set :lectureType of printed demand should have :hours hours booked in :weekNumber week in :building building and :room room
     */
    public function lectureSetOfPrintedDemandShouldHaveHoursBookedInWeekInBuildingAndRoom($lectureType, int $hours, int $weekNumber, int $building, int $room)
    {
        $lectureSet = $this->printedDemand->allocatedWeeks[$lectureType];
        Assert::same($hours, $lectureSet[$weekNumber]['hours']);
        Assert::same($building, $lectureSet[$weekNumber]['building']);
        Assert::same($room, $lectureSet[$weekNumber]['room']);
    }
}
