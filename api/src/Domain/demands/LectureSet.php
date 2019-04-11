<?php


namespace App\Domain\demands;


use App\Domain\users\User;

class LectureSet
{
    const LECTURE_LECTURE_TYPE = 0;
    const PROJECT_LECTURE_TYPE = 1;
    const LABORATORY_LECTURE_TYPE = 2;
    const EXERCISES_LECTURE_TYPE = 3;

    const LECTURE_TYPES_FOR_IMPORT = [
        'Ćwiczenia' => self::EXERCISES_LECTURE_TYPE,
        'Wykład' => self::LECTURE_LECTURE_TYPE,
        'Projekt' => self::PROJECT_LECTURE_TYPE,
        'Laboratoria' => self::LABORATORY_LECTURE_TYPE
    ];

    const LECTURE_TYPES_FOR_DISPLAY = [
        self::EXERCISES_LECTURE_TYPE => 'Ćwiczenia',
        self::LECTURE_LECTURE_TYPE => 'Wykład',
        self::PROJECT_LECTURE_TYPE => 'Projekt',
        self::LABORATORY_LECTURE_TYPE => 'Laboratoria'
    ];

    /**
     * @var int
     */
    private $lectureType;

    /**
     * @var Subject
     */
    private $subject;

    /**
     * @var User
     */
    private $lecturer;

    public function __construct(int $lectureType, Subject $subject)
    {
        $this->lectureType = $lectureType;
        $this->subject = $subject;
    }

    public function getLectureType(): int
    {
        return $this->lectureType;
    }

    public function setLecturer(User $lecturer): self
    {
        $this->lecturer = $lecturer;
        return $this;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getLecturer(): User
    {
        return $this->lecturer;
    }
}