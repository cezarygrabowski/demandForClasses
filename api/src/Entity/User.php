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
     * Many User have Many qualifications.
     * @ManyToMany(targetEntity="Subject")
     * @JoinTable(name="users_qualifications",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="qualification_id", referencedColumnName="id", unique=true)}
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
    }

    public function isNauczyciel()
    {
    }

    public function isKierownikZakladu()
    {
    }

    public function isDziekan()
    {
    }

    public function isDyrektorInstytutu()
    {
    }


    public function __construct($username)
    {
        $this->isActive = true;
        $this->username = $username;
        $this->qualifications = new ArrayCollection();
        $this->lectures = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function addQualification(Subject $subject)
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

    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getQualifications()
    {
        return $this->qualifications;
    }

    /**
     * @param mixed $qualifications
     */
    public function setQualifications($qualifications): void
    {
        $this->qualifications = $qualifications;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLectures()
    {
        return $this->lectures;
    }

    /**
     * @param mixed $lectures
     */
    public function setLectures($lectures): void
    {
        $this->lectures = $lectures;
    }
}
