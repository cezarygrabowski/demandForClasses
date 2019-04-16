<?php

namespace Users\Domain;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var ArrayCollection<Role>
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

    public function addRole(Role $role)
    {
        if(!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function getRole(string $name): ?Role {
        foreach ($this->getRoles() as $role) {
            if($role->getName() === Role::ROLES_STRING_TO_INT[$name]) {
                return $role;
            }
        }

        return null;
    }

    public function isAdmin()
    {
        return in_array(Role::ADMIN, $this->getRolesIds());
    }

    public function isTeacher()
    {
        return in_array(Role::TEACHER, $this->getRolesIds());
    }

    public function isDepartmentManager()
    {
        return in_array(Role::DISTRICT_MANAGER, $this->getRolesIds());
    }

    public function isDean()
    {
        return in_array(Role::DEAN, $this->getRolesIds());
    }

    public function isInstituteDirector()
    {
        return in_array(Role::INSTITUTE_DIRECTOR, $this->getRolesIds());
    }

    public function isPlanner()
    {
        return in_array(Role::PLANNER, $this->getRolesIds());
    }

    /**
     * @return int[]
     */
    private function getRolesIds(): array
    {
        $ids = [];
        foreach ($this->getRoles() as $role) {
            $ids[] = $role->getName();
        }

        return $ids;
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
