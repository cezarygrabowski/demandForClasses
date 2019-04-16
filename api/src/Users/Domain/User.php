<?php

namespace Users\Domain;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    const ROLE_ADMIN = 0;
    const ROLE_TEACHER = 1;
    const ROLE_DISTRICT_MANAGER = 2;
    const ROLE_INSTITUTE_DIRECTOR = 3;
    const ROLE_DEAN = 4;
    const ROLE_PLANNER = 5;

    const ROLES_STRING_TO_INT = [
        'Administrator' => 0,
        'Nauczyciel' => 1,
        'Kierownik zakładu' => 2,
        'Dyrektor instytutu' => 3,
        'Dziekan' => 4,
        'Planista' => 5,
    ];

    const ROLES_INT_TO_STRING = [
        0 => 'Administrator',
        1 => 'Nauczyciel',
        2 => 'Kierownik zakładu',
        3 => 'Dyrektor instytutu',
        4 => 'Dziekan',
        5 => 'Planista'
    ];

    /**
     * @var string
     */
    private $username;

    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var ArrayCollection<Qualification>
     */
    private $qualifications;

    /**
     * @var Calendar
     */
    private $calendar;

    /**
     * @var User
     */
    private $importedBy;

    /**
     * @var DateTime
     */
    private $importedAt;

    /**
     * Hashed password
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $apiToken;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->uuid = Uuid::uuid4();
        $this->roles = new ArrayCollection();
        $this->qualifications = new ArrayCollection();
    }

    public function getUuid(): string
    {
        return $this->uuid->toString();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRole(int $name): ?string
    {
        foreach ($this->getRoles() as $key => $role) {
            if($role === $name) {
                return $role;
            }
        }

        return null;
    }

    public function isAdmin()
    {
        return in_array(self::ROLE_ADMIN, $this->getRoles());
    }

    public function isTeacher()
    {
        return in_array(self::ROLE_TEACHER, $this->getRoles());
    }

    public function isDepartmentManager()
    {
        return in_array(self::ROLE_DISTRICT_MANAGER, $this->getRoles());
    }

    public function isDean()
    {
        return in_array(self::ROLE_DEAN, $this->getRoles());
    }

    public function isInstituteDirector()
    {
        return in_array(self::ROLE_INSTITUTE_DIRECTOR, $this->getRoles());
    }

    public function isPlanner()
    {
        return in_array(self::ROLE_PLANNER, $this->getRoles());
    }

    public function addQualification(Qualification $qualification)
    {
        if(!$this->qualifications->contains($qualification)) {
            $this->qualifications->add($qualification);
        }
    }

    /**
     * @return Qualification[]
     */
    public function getQualifications(): array
    {
        return $this->qualifications->toArray();
    }

    /**
     * @return Calendar
     */
    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     * @return User
     */
    public function setCalendar(Calendar $calendar): User
    {
        $this->calendar = $calendar;
        return $this;
    }

    /**
     * @return User
     */
    public function getImportedBy(): User
    {
        return $this->importedBy;
    }

    /**
     * @param User $importedBy
     * @return User
     */
    public function setImportedBy(User $importedBy): User
    {
        $this->importedBy = $importedBy;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getImportedAt(): DateTime
    {
        return $this->importedAt;
    }

    /**
     * @param DateTime $importedAt
     * @return User
     */
    public function setImportedAt(DateTime $importedAt): User
    {
        $this->importedAt = $importedAt;
        return $this;
    }

    public function getQualification(string $subjectName): ?Qualification
    {
        foreach ($this->getQualifications() as $qualification) {
            if($qualification->getSubject()->getName() === $subjectName){
                return $qualification;
            }
        }

        return null;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     * @return User
     */
    public function setApiToken(string $apiToken): User
    {
        $this->apiToken = $apiToken;
        return $this;
    }
}
