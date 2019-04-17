<?php


namespace Demands\Infrastructure\Doctrine\Repository;


use Demands\Domain\Repository\SubjectRepository;
use Demands\Domain\Subject;

class DoctrineSubjectRepository implements SubjectRepository
{
    public function findByName(string $name): ?Subject
    {
        // TODO: Implement findByName() method.
    }
}
