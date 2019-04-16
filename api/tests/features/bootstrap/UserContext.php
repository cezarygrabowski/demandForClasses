<?php


use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;
use Demands\Infrastructure\InMemory\Repository\InMemorySubjectRepository;
use Users\Application\Command\ImportUsers;
use Users\Application\Handler\ImportUsersHandler;
use Users\Domain\Calendar;
use Users\Domain\Import\CsvExtractor;
use Users\Domain\Month;
use Users\Domain\Repository\UserRepository;
use Users\Domain\Role;
use Users\Domain\User;
use Users\Infrastructure\InMemory\Repository\InMemoryUserRepository;
use Webmozart\Assert\Assert;

class UserContext implements Context
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var User
     */
    private $user;

    /**
     * @Given There is user :userName
     */
    public function thereIsUser($userName)
    {
        $this->user = new User($userName);
        $this->userRepository = new InMemoryUserRepository([$this->user]);
    }

    /**
     * @Given user :userName has role :roleName
     */
    public function userHasRole($userName, $roleName)
    {
        $user = $this->userRepository->findByUsername($userName);
        $role = new Role();
        $role->setName(Role::ROLES_STRING_TO_INT[$roleName])
            ->setUser($user);

        $user->addRole($role);
    }

    /**
     * @When user :userName import :fileName lecturers file that contains the following:
     */
    public function userImportLecturersFileThatContainsTheFollowing($userName, $fileName, TableNode $table)
    {
        $user = $this->userRepository->findByUsername($userName);
        $row = $table->getRow(0);
        $fp = fopen($fileName, 'w');
        fputcsv($fp, ['MOCKED HEADER']);
        fputcsv($fp, $row);
        fclose($fp);

        $command = new ImportUsers($user, CsvExtractor::extract($fileName));
        $handler = new ImportUsersHandler(new InMemorySubjectRepository([]), $this->userRepository);

        $users = $handler->handle($command);
        $this->user = $users[0];
        $this->userRepository = new InMemoryUserRepository($users);
    }

    /**
     * @Then user should have name :userName
     */
    public function userShouldHaveName($userName)
    {
        Assert::same($this->user->getUsername(), $userName);
    }

    /**
     * @Then user :userName should have :roleName role
     */
    public function userShouldHaveRole($userName, $roleName)
    {
        $user = $this->userRepository->findByUsername($userName);
        Assert::notNull($user->getRole($roleName));
    }

    /**
     * @Then user :userName should have following qualifications:
     */
    public function userShouldHaveFollowingQualifications($userName, TableNode $table)
    {
        $user = $this->userRepository->findByUsername($userName);
        $rows= $table->getRows();

        foreach ($rows as $row) {
            foreach ($row as $item) {
                Assert::notNull($user->getQualification($item));
            }
        }
    }

    /**
     * @Then user :userName should have calendar that contains:
     */
    public function userShouldHaveCalendarThatContains($userName, TableNode $table)
    {
        $user = $this->userRepository->findByUsername($userName);
        foreach ($table->getRows() as $row) {
            $month = $user->getCalendar()->getMonth($row[0]);
            Assert::same($month->getWorkingHours(), (int)$row[1]);
        }
    }

    /**
     * @Given user :userName has calendar for :semester semester that contains:
     */
    public function userHasCalendarThatContains($userName, $semester, TableNode $table)
    {
        $user = $this->userRepository->findByUsername($userName);
        $calendar = new Calendar($semester);
        foreach ($table->getRows() as $row) {
            $month = new Month($row[0], $row[1]);
            $calendar->addMonth($month);
        }
        $user->setCalendar($calendar);
    }
}
