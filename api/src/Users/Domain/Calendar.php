<?php


namespace Users\Domain;


use Doctrine\Common\Collections\ArrayCollection;

class Calendar
{
    /**
     * @var ArrayCollection<Month>
     */
    private $months;

    /**
     * @var string
     */
    private $semester;

    /**
     * @var User
     */
    private $user;

    /**
     * Calendar constructor.
     * @param string $semester
     */
    public function __construct(string $semester)
    {
        $this->months = new ArrayCollection();
        $this->semester = $semester;
    }

    public function addMonth(Month $month): self
    {
        if(!$this->months->contains($month)) {
            $this->months->add($month);
        }

        return $this;
    }

    /**
     * @return Month[]
     */
    public function getMonths(): array
    {
        return $this->months->toArray();
    }

    public function getSemester(): string
    {
        return $this->semester;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMonth(int $monthNumber): ?Month
    {
        foreach ($this->getMonths() as $month) {
            if($month->getMonthNumber() === $monthNumber) {
                return $month;
            }
        }

        return null;
    }
}