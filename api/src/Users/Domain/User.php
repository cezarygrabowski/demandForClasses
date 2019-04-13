<?php

namespace Users\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User
{

    /** @var string */
    private $username;

    /** @var UuidInterface */
    private $uuid;

    /** @var ArrayCollection<Role> */
    private $roles;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->uuid = Uuid::uuid4();
        $this->roles = new ArrayCollection();
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

    public function getRole(string $name): Role {
        foreach ($this->getRoles() as $role) {
            if($role->getName() === Role::ROLES_STRING_TO_INT[$name]) {
                return $role;
            }
        }

        throw new \Exception("Rola o nazwie " . $name . " nie istnieje");
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
}