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
     * @ORM\Column(type="string", length=25, unique=true)
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
     * @OneToMany(targetEntity="LectureType", mappedBy="lecturer", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $lectureTypes;

    /**
     * Many User have Many qualifications.
     * @ManyToMany(targetEntity="Subject")
     * @JoinTable(name="users_qualifications",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="qualification_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $qualifications;

    public function __construct($username)
    {
        $this->isActive = true;
        $this->username = $username;
        $this->qualifications = new ArrayCollection();
        $this->lectureTypes = new ArrayCollection();

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
        return array('ROLE_USER');
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
    public function getLectureTypes()
    {
        return $this->lectureTypes;
    }

    /**
     * @param mixed $lectureTypes
     */
    public function setLectureTypes($lectureTypes): void
    {
        $this->lectureTypes = $lectureTypes;
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
}