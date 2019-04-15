<?php


namespace Users\Domain;


use Demands\Domain\Subject;

class Qualification
{
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