<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity
 * @ORM\Table(name="roles")
 */
class Role
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_DZIEKAN = 'ROLE_DZIEKAN';
    const ROLE_KIEROWNIK_ZAKLADU = 'ROLE_KIEROWNIK_ZAKLADU';
    const ROLE_NAUCZYCIEL = 'ROLE_NAUCZYCIEL';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"}, fetch="EAGER", inversedBy="roles")
     */
    private $user;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $name;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->getName();
    }

}