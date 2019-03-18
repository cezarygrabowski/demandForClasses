<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    const ADMIN = 'Admin';
    const TEACHER = 'Nauczyciel';
    const DISTRICT_MANAGER = 'KierownikZakladu';
    const INSTITUTE_DIRECTOR = 'DyrektorInstytutu';
    const DEAN = 'Dziekan';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=500)
     * @Exclude
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @OneToMany(targetEntity="Lecture", mappedBy="lecturer", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $lectures;

    /**
     * Many Users have many qualifications
     *
     * @ManyToMany(targetEntity="Subject", cascade={"persist", "remove", "merge"})
     * @JoinTable(name="users_qualifications",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="qualification_id", referencedColumnName="id")}
     *      )
     */
    private $qualifications;

    /**
     * @OneToMany(targetEntity="Role", mappedBy="user", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     * @var array
     */
    private $roles;

    public function isAdmin()
    {
        return in_array(self::ADMIN, $this->getRoles());
    }

    public function isTeacher()
    {
        return in_array(self::TEACHER, $this->getRoles());
    }

    public function isDepartmentManager()
    {
        return in_array(self::DISTRICT_MANAGER, $this->getRoles());
    }

    public function isDean()
    {
        return in_array(self::DEAN, $this->getRoles());
    }

    public function isInstituteDirector()
    {
        return in_array(self::INSTITUTE_DIRECTOR, $this->getRoles());
    }

    public function __construct($username)
    {
        $this->isActive = true;
        $this->username = $username;
        $this->qualifications = new ArrayCollection();
        $this->lectures = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function addQualification(Subject $subject): void
    {
        if (!$this->qualifications->contains($subject)) {
            $this->qualifications->add($subject);
        }
    }

    public function addRole(Role $role): void
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->__toString();
        }

        return $roles;
    }

    public function eraseCredentials()
    {

    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return Subject[]
     */
    public function getQualifications(): array
    {
        return $this->qualifications->toArray();
    }

    public function setQualifications($qualifications): void
    {
        $this->qualifications = $qualifications;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Lecture[]
     */
    public function getLectures(): array
    {
        return $this->lectures->toArray();
    }

    public function setLectures($lectures): void
    {
        $this->lectures = $lectures;
    }
}
