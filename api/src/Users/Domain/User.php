<?php

namespace Users\Domain;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_TEACHER = 'ROLE_TEACHER';
    const ROLE_DISTRICT_MANAGER = 'ROLE_DISTRICT_MANAGER';
    const ROLE_INSTITUTE_DIRECTOR = 'ROLE_INSTITUTE_DIRECTOR';
    const ROLE_DEAN = 'ROLE_DEAN';
    const ROLE_PLANNER = 'ROLE_PLANNER';

    const ROLES_VALUE_TO_KEY = [
        'Administrator' => self::ROLE_ADMIN,
        'Nauczyciel' => self::ROLE_TEACHER,
        'Kierownik zakładu' => self::ROLE_DISTRICT_MANAGER,
        'Dyrektor instytutu' => self::ROLE_INSTITUTE_DIRECTOR,
        'Dziekan' => self::ROLE_DEAN,
        'Planista' => self::ROLE_PLANNER,
    ];

    const ROLES_KEY_TO_VALUE = [
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_TEACHER => 'Nauczyciel',
        self::ROLE_DISTRICT_MANAGER => 'Kierownik zakładu',
        self::ROLE_INSTITUTE_DIRECTOR => 'Dyrektor instytutu',
        self::ROLE_DEAN => 'Dziekan',
        self::ROLE_PLANNER => 'Planista'
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

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->uuid = Uuid::uuid4();
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
        $roles = !empty($this->roles) ? $this->roles : [];
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
     * Returns the salt that was originally used to encode the password.
     * This can return null if the password was not encoded using a salt.
     * @return string|null The salt
     */
    public function getSalt()
    {
        // not needed in bcrypt
    }

    /**
     * Removes sensitive data from the user.
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
