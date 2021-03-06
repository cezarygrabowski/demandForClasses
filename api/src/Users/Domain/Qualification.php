<?php


namespace Users\Domain;


use Demands\Domain\Subject;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Qualification
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Subject
     */
    private $subject;

    /**
     * Qualification constructor.
     * @param Subject $subject
     */
    public function __construct(Subject $subject)
    {
        $this->uuid = Uuid::uuid4();
        $this->subject = $subject;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Qualification
     */
    public function setUser(User $user): Qualification
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Subject
     */
    public function getSubject(): Subject
    {
        return $this->subject;
    }

    /**
     * @param Subject $subject
     * @return Qualification
     */
    public function setSubject(Subject $subject): Qualification
    {
        $this->subject = $subject;
        return $this;
    }
}