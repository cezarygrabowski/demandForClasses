<?php


namespace Demands\Infrastructure\InMemory\Repository;


use Demands\Domain\Repository\SubjectRepository;
use Demands\Domain\Subject;

class InMemorySubjectRepository implements SubjectRepository
{
    public $subjects = [];

    /**
     * InMemorySubjectRepository constructor.
     * @param Subject[] $subjects
     */
    public function __construct(array $subjects)
    {
        foreach ($subjects as $subject) {
            $this->subjects[$subject->getName()] = $subject;
        }
    }

    public function findByName(string $name): ?Subject
    {
        foreach ($this->subjects as $key => $value) {
            if($key === $name) {
                return $value;
            }
        }

        return null;
    }
}